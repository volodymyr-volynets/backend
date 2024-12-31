<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Cache\File;

use Helper\File;

class Base extends \Numbers\Backend\Cache\Common\Base
{
    /**
     * Constructor
     *
     * @param string $cache_link
     * @param array $options
     */
    public function __construct(string $cache_link, array $options = [])
    {
        parent::__construct($cache_link, $options);
    }

    /**
     * Connect
     *
     * @param array $options
     *		dir
     * @return array
     */
    public function connect(array $options): array
    {
        $result = [
            'success' => false,
            'error' => [],
        ];
        // check if we have valid directory
        if (empty($options['dir'])) {
            $result['error'][] = 'Cache directory does not exists or not provided!';
        } else {
            // fixing path
            $options['dir'] = rtrim($options['dir'], '/') . DIRECTORY_SEPARATOR;
            // handle cache key
            if (!empty($this->options['cache_key'])) {
                $options['dir'] .= $this->options['cache_key'] . DIRECTORY_SEPARATOR;
            }
            // add cache link
            $options['dir'] .= $this->cache_link . DIRECTORY_SEPARATOR;
            // we need to create cache directory
            if (!is_dir($options['dir'])) {
                if (!File::mkdir($options['dir'], 0777)) {
                    $result['error'][] = 'Unable to create caching directory!';
                }
                File::chmod($options['dir'], 0777);
            }
            // add directory to the options
            $this->options['dir'] = $options['dir'];
        }
        if (empty($result['error'])) {
            $result['success'] = true;
        }
        return $result;
    }

    /**
     * Close
     *
     * @return array
     */
    public function close(): array
    {
        return [
            'success' => true,
            'error' => [],
        ];
    }

    /**
     * Get
     *
     * @param string $cache_id
     * @return array
     */
    public function get(string $cache_id): array
    {
        $result = [
            'success' => false,
            'error' => [],
            'data' => null,
            'time' => microtime(true),
        ];
        do {
            // load cookie
            $cookie_name = $this->options['dir'] . 'cache--cookie--' . $cache_id . '.data';
            if (!file_exists($cookie_name)) {
                break;
            }
            $cookie_data = file_get_contents($cookie_name);
            if ($cookie_data === false) {
                $result['error'][] = 'File cache: Failed to read cookie file!';
                break;
            }
            // convert data to array
            $cookie_data = $this->storageConvert('get', $cookie_data);
            // remove cookie files if expired
            if ($cookie_data['expire'] < time()) {
                File::delete($cookie_name);
                File::delete($cookie_data['file']);
                break;
            }
            // load cache file
            $cache_data = file_get_contents($cookie_data['file']);
            if ($cache_data === false) {
                $result['error'][] = 'File cache: Failed to read cache file!';
                break;
            }
            // success if we got here
            $result['data'] = $this->storageConvert('get', $cache_data);
            $result['success'] = true;
        } while (0);
        // log
        $error_message = $result['error'] ? (', error' . implode(', ', $result['error'])) : '';
        \Log::add([
            'type' => 'Cache',
            'only_chanel' => 'default',
            'message' => 'Getting item from cache!',
            'other' => 'Cache # ' . $cache_id . $error_message,
            'affected_rows' => isset($result['data']['rows']) ? count($result['data']['rows']) : 0,
            'error_rows' => $result['error'] ? 1 : 0,
            'trace' => $result['error'] ? \Object\Error\Base::debugBacktraceString(null, ['skip_params' => true]) : null,
            'duration' => microtime(true) - $result['time'],
            'operation' => 'GET',
        ]);
        return $result;
    }

    /**
     * Set
     *
     * @param string $cache_id
     * @param mixed $data
     * @param int|null $expire
     * @param array $tags
     * @return array
     */
    public function set(string $cache_id, $data, ?int $expire = null, array $tags = []): array
    {
        $result = [
            'success' => false,
            'error' => []
        ];
        do {
            // writing data first
            $data_name = $this->options['dir'] . 'cache--' . $cache_id . '.data';
            if (file_put_contents($data_name, $this->storageConvert('set', $data), LOCK_EX) === false) {
                $result['error'][] = 'Failed to write cache file!';
                break;
            }
            // prepare cookie data
            $time = time();
            $cookie_data = [
                'time' => $time,
                'expire' => $this->calculateExpireTimestamp($time, $expire),
                'tags' => $tags,
                'file' => $data_name
            ];
            // writing cookie
            $cookie_name = $this->options['dir'] . 'cache--cookie--' . $cache_id . '.data';
            if (file_put_contents($cookie_name, $this->storageConvert('set', $cookie_data), LOCK_EX) === false) {
                $result['error'][] = 'Failed to write cookie file!';
                break;
            }
            // set file permission
            @chmod($data_name, 0777);
            @chmod($cookie_name, 0777);
            // success if we got here
            $result['success'] = true;
        } while (0);
        // log
        $error_message = $result['error'] ? (', error' . implode(', ', $result['error'])) : '';
        \Log::add([
            'type' => 'Cache',
            'only_chanel' => 'default',
            'message' => 'Setting item in cache!',
            'other' => 'Cache # ' . $cache_id . $error_message,
            'affected_rows' => isset($data['rows']) && is_array($data['rows']) ? count($data['rows']) : 0,
            'error_rows' => $result['error'] ? 1 : 0,
            'trace' => $result['error'] ? \Object\Error\Base::debugBacktraceString(null, ['skip_params' => true]) : null,
            'operation' => 'SET',
        ]);
        return $result;
    }

    /**
     * Garbage collector
     *
     * @param int $mode
     *		1 - old
     *		2 - all
     *		3 - tag
     * @param array $tags
     * @return array
     */
    public function gc(int $mode = 1, array $tags = []): array
    {
        $result = [
            'success' => false,
            'error' => []
        ];
        // get a list of cache cookies
        if (($cookies = glob($this->options['dir'] . 'cache--cookie--*')) === false) {
            $result['success'] = true;
        } else {
            $time = time();
            foreach ($cookies as $cookie) {
                if (!file_exists($cookie)) {
                    continue;
                }
                // read cookie
                $cookie_data = file_get_contents($cookie);
                if ($cookie_data === false) {
                    continue;
                }
                $cookie_data = $this->storageConvert('get', $cookie_data);
                $flag_delete = false;
                // all
                if ($mode == 2) {
                    goto delete;
                }
                // tags
                if ($mode == 3 && !empty($tags) && !empty($cookie_data['tags'])) {
                    if ($this->shouldDeleteACacheBasedOnTags($tags, $cookie_data['tags'])) {
                        goto delete;
                    }
                }
                // old
                if ($mode == 1 && $time > $cookie_data['expire']) {
                    goto delete;
                }
                // if we need to delete
                if ($flag_delete) {
                    delete:
                                        // just in case we need to check if files exists
                                        if (file_exists($cookie)) {
                                            unlink($cookie);
                                        }
                    if (file_exists($cookie_data['file'])) {
                        unlink($cookie_data['file']);
                    }
                }
            }
            // success if we got here
            $result['success'] = true;
        }
        // log
        \Log::add([
            'type' => 'Cache',
            'only_chanel' => 'default',
            'message' => 'Garbage collect in cache!' . ($result['success'] ? 'success' : 'fail'),
            'other' => 'Cache result: ' . ($result['success'] ? 'success' : 'fail'),
            'operation' => 'GC',
        ]);
        return $result;
    }
}
