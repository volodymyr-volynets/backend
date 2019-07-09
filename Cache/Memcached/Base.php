<?php

/**
 * Memcached
 */
namespace Numbers\Backend\Cache\Memcached;
class Base extends \Numbers\Backend\Cache\Common\Base {

	/**
	 * Memcached
	 *
	 * @var object
	 */
	private $memcached;

	/**
	 * Set tags
	 *
	 * @var array
	 */
	public $set_tags = [];

	/**
	 * Cache key prefix
	 *
	 * @var string
	 */
	public $cache_key_prefix;

	/**
	 * Constructor
	 *
	 * @param string $cache_link
	 * @param array $options
	 */
	public function __construct(string $cache_link, array $options = []) {
		parent::__construct($cache_link, $options);
		// create memcached
		$this->memcached = new \Memcached();
		// determine cache key prefix, used to deferentiate between systems on
		// a shared memcached server
		$this->cache_key_prefix = $options['cache_key'] ?? 'N.A.';
		if (!empty($this->cache_key_prefix)) $this->cache_key_prefix.= '::';
		$this->cache_key_prefix.= $cache_link . '::';
	}

	/**
	 * Connect
	 *
	 * @param array $options
	 *		host
	 *		port
	 * @return array
	 */
	public function connect(array $options) : array {
		if ($this->memcached->addServer($options['host'], $options['port'])) {
			return ['success' => true, 'error' => []];
		} else {
			return ['success' => false, 'error' => ['Memcached: could not connect to server!']];
		}
	}

	/**
	 * Close
	 *
	 * @return array
	 */
	public function close() : array {
		// we must set tags on exit
		$this->setTags();
		// close connection
		if ($this->memcached->quit()) {
			return ['success' => true, 'error' => []];
		} else {
			return ['success' => false, 'error' => ['Memcached: could close server connection!']];
		}
	}

	/**
	 * Get
	 *
	 * @param string $cache_id
	 * @return array
	 */
	public function get(string $cache_id) : array {
		$result = $this->memcached->get($this->cache_key_prefix . $cache_id);
		if ($result !== false) {
			return [
				'success' => true,
				'error' => [],
				'data' => $this->storageConvert('get', $result),
			];
		} else {
			return [
				'success' => false,
				'error' => [],
				'data' => null
			];
		}
	}

	/**
	 * Set
	 *
	 * @param string $cache_id
	 * @param mixed $data
	 * @param int $expire
	 * @param array $tags
	 * @return array
	 */
	public function set(string $cache_id, $data, int $expire = null, array $tags = []) : array {
		$result = $this->memcached->set($this->cache_key_prefix . $cache_id, $this->storageConvert('set', $data), $expire ?? $this->options['expire']);
		// we need to process tags
		if (!empty($tags)) {
			$this->set_tags[$this->cache_link][$this->cache_key_prefix . $cache_id] = $tags;
		}
		if ($result) {
			return ['success' => true, 'error' => []];
		} else {
			return ['success' => false, 'error' => []];
		}
	}

	/**
	 * Set tags
	 */
	private function setTags() {
		if (empty($this->set_tags)) return;
		// merge new tags
		$values = [];
		foreach ($this->set_tags as $k => $v) {
			foreach ($v as $k2 => $v2) {
				$v2 = array_unique($v2);
				foreach ($v2 as $v3) {
					$values[$k . '::' . $k2 . '::' . $v3] = [
						'sm_memcached_cache_link' => $k,
						'sm_memcached_cache_id' => $k2,
						'sm_memcached_tag' => $v3
					];
				}
			}
		}
		\Numbers\Backend\Cache\Memcached\Model\Tags::collectionStatic()->mergeMultiple($values);
		// a must to zero out
		$this->set_tags = [];
	}

	/**
	 * Garbage collector
	 *
	 * @param int $mode
	 *		1 - old
	 *		2 - all
	 *		3 - tags
	 * @param array $tags
	 * @return array
	 */
	public function gc(int $mode = 1, array $tags = []) : array {
		// remove all caches
		if ($mode == 2) {
			$this->memcached->flush();
		} else if ($mode == 3 && !empty($tags)) { // tags
			// we must set tags
			$this->setTags();
			$model = new \Numbers\Backend\Cache\Memcached\Model\Tags();
			// get all tags from database
			$combined_tags = [];
			foreach ($tags as $v) {
				foreach ($v as $v2) {
					$combined_tags[] = $v2;
				}
			}
			$select_query = $model->queryBuilder()->select();
			$select_query->columns([
				'a.sm_memcached_cache_id',
				'a.sm_memcached_tag'
			]);
			$cache_link = $this->cache_link;
			$select_query->where('AND', function (& $query) use ($combined_tags, $cache_link) {
				$query = \Numbers\Backend\Cache\Memcached\Model\Tags::queryBuilderStatic(['alias' => 'inner_a'])->select();
				$query->columns(1);
				$query->where('AND', ['inner_a.sm_memcached_cache_link', '=', 'a.sm_memcached_cache_link', true]);
				$query->where('AND', ['inner_a.sm_memcached_cache_id', '=', 'a.sm_memcached_cache_id', true]);
				$query->where('AND', ['inner_a.sm_memcached_cache_link', '=', $cache_link, false]);
				$query->where('AND', ['inner_a.sm_memcached_tag', 'IN', $combined_tags, false]);
			}, true);
			$select_data = $select_query->query();
			$all_caches = [];
			foreach ($select_data['rows'] as $v) {
				if (!isset($all_caches[$v['sm_memcached_cache_id']])) {
					$all_caches[$v['sm_memcached_cache_id']] = [];
				}
				$all_caches[$v['sm_memcached_cache_id']][] = $v['sm_memcached_tag'];
			}
			// determine if we have caches to be deleted
			$to_delete_caches = [];
			foreach ($all_caches as $k => $v) {
				if ($this->shouldDeleteACacheBasedOnTags($tags, $v)) {
					$to_delete_caches[$k] = $k;
				}
			}
			if (!empty($to_delete_caches)) {
				$this->memcached->deleteMulti($to_delete_caches);
				// delete ids from database
				$delete_query = $model->queryBuilder()->delete();
				$delete_query->where('AND', ['sm_memcached_cache_link', '=', $this->cache_link, false]);
				$delete_query->where('AND', ['sm_memcached_cache_id', 'IN', $to_delete_caches]);
				$delete_query->query();
			}
		}
		return [
			'success' => true,
			'error' => []
		];
	}
}