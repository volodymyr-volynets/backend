<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\File;

class Base extends \Numbers\Backend\Log\Common\Base
{
    /**
     * Save
     *
     * @param array $data
     * @return bool
     */
    public function save(string $log_link, array $data): array
    {
        if (!empty($this->options['logs']['enabled']) && count($data) > 0) {
            $result = '';
            foreach ($data as $v) {
                // for file logs we record only errors
                if (!in_array($v['type'], self::ERROR_TYPES)) {
                    continue;
                }
                // assemeble the message
                $result .= '[' . $v['inserted_timestamp'] . '] [Level:' . $v['level'] . '] [Status:' . $v['status'] . ']';
                $result .= $v['message'];
                $result .= "\n";
                // clean and convert to json for processing
                $json = json_encode(array_remove_empty_values($v), JSON_PRETTY_PRINT);
                foreach (explode("\n", $json) as $v2) {
                    $result .= "\t\t" . $v2 . "\n";
                }
            }
            $file = rtrim($this->options['logs']['dir'], '/') . DIRECTORY_SEPARATOR . 'application_' . $log_link . '.log';
            file_put_contents($file, $result, FILE_APPEND);
        }
        // if we enable SQL query dumps
        if (!empty($this->options['sql']['enabled'])) {
            $this->dumpDbLogs();
        }
        return ['success' => true, 'error' => []];
    }

    /**
     * Dump database logs.
     */
    public function dumpDbLogs(): void
    {
        $file = rtrim($this->options['sql']['dir'], '/') . DIRECTORY_SEPARATOR;
        $file .= 'tenant_' . \Tenant::id() . '_' . \Format::now('timestamp_file') . '.sql';
        // assemble
        $log = "";
        foreach (\Debug::$data['sql'] as $v) {
            $log .= '---------------------------------------------------------------------------------------------------';
            $log .= "\n";
            $log .= $v['sql'];
            $log .= "\n";
            $log .= print_r($v['backtrace'], true);
        }
        // dump to file
        file_put_contents($file, $log);
    }
}
