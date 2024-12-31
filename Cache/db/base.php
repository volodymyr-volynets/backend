<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

class numbers_backend_cache_db_base extends numbers_backend_cache_class_base implements numbers_backend_cache_interface_base
{
    /**
     * Complete result
     */
    public const complete_result = [
        'success' => false,
        'error' => [],
        'errno' => null,
        /* statistics */
        'statistics' => [
            'query_string' => null,
            'query_start' => null,
            'response_string' => '',
            'response_parts' => [],
            'response_duration' => null,
        ]
    ];

    /**
     * Model
     *
     * @var object
     */
    private $model_cache;

    /**
     * Constructor
     *
     * @param string $cache_link
     * @param array $options
     */
    public function __construct(string $cache_link, array $options = [])
    {
        parent::__construct($cache_link, $options);
        // initialize model
        $this->model_cache = new numbers_backend_cache_db_model_cache();
    }

    /**
     * Constructing cache object
     *
     * @param string $cache_link
     * @param array $options
     */
    public function __construct($cache_link)
    {
        $this->cache_link = $cache_link;
        $this->cache_key = null;
        // initialize model
        $this->model_cache = new numbers_backend_cache_db_model_cache();
    }

    /**
     * Connect
     *
     * @param array $options
     * @return array
     */
    public function connect(array $options): array
    {
        $this->options = $options;
        // expiration
        $this->options['expire'] = $this->options['expire'] ?? 7200;
        return ['success' => true, 'error' => []];
    }

    /**
     * Close
     */
    public function close()
    {
        // 5 percent chance to call garbage collector
        if (chance(5)) {
            $this->gc(1);
        }
        return ['success' => true, 'error' => []];
    }

    /**
     * Get data from cache
     *
     * @param string $cache_id
     * @return mixed
     */
    public function get($cache_id)
    {
        $data = $this->model_cache->get(['columns' => ['sm_cache_data'], 'limit' => 1, 'pk' => null, 'where' => ['sm_cache_id' => $cache_id, 'sm_cache_expire,>=' => Format::now('timestamp')]]);
        if (isset($data[0])) {
            return json_decode($data[0]['sm_cache_data'], true);
        } else {
            return false;
        }
    }

    /**
     * Put data into cache
     *
     * @param string $cache_id
     * @param mixed $data
     * @param mixed $tags
     * @param int $expire
     * @return boolean
     */
    public function set($cache_id, $data, $tags = [], $expire = null)
    {
        if (!empty($tags)) {
            $tags = ' ' . implode(' ', array_fix($tags)) . ' ';
        } else {
            $tags = null;
        }
        // processing expire
        if (empty($expire)) {
            $expire = Format::now('timestamp', ['add_seconds' => $this->options['expire']]);
        } else {
            $expire = Format::read_date($expire, 'datetime');
        }
        // generating array for saving
        $save = [
            'sm_cache_id' => $cache_id . '',
            'sm_cache_time' => Format::now('timestamp'),
            'sm_cache_expire' => $expire,
            'sm_cache_data' => json_encode($data),
            'sm_cache_tags' => $tags
        ];
        $save_result = $this->model_cache->save($save);
        return $save_result['success'];
    }

    /**
     * Garbage collector
     *
     * @param int $mode - 1 - old, 2 - all
     * @param array $tags
     * @return boolean
     */
    public function gc($mode = 1, $tags = [])
    {
        if ($mode == 2) {
            $sql = 'DELETE FROM ' . $this->model_cache->name;
        } elseif ($mode == 1) {
            $sql = 'DELETE FROM ' . $this->model_cache->name . ' WHERE sm_cache_expire < \'' . Format::now('timestamp') . '\'';
            if (!empty($tags)) {
                $tags2 = array_fix($tags);
                $temp = [];
                foreach ($tags2 as $v) {
                    $temp[] = "sm_cache_tags LIKE '% {$v} %'";
                }
                $sql .= ' OR (' . implode(' OR ', $temp) . ')';
            }
        }
        $db = new db($this->model_cache->db_link);
        $result = $db->query($sql);
        return $result['success'];
    }
}
