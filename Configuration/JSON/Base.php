<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Configuration\JSON;

use Helper\File;

class Base extends \Numbers\Backend\Configuration\Common\Class2\Base
{
    /**
     * Read file
     *
     * @param string $path
     * @param string|null $environment
     * @param array $options
     * @return array
     */
    public function readFile(string $path, string|null $environment = null, array $options = []): array
    {
        // get current environment
        if (!isset($environment)) {
            $environment = \Configuration::environment();
        }
        // system folders
        $options = array_merge_hard(\Application::get('system_folders'), $options);
        // and finally read json files
        $result = [];
        foreach (\Configuration::getConfigurationPaths($path, $environment) as $v) {
            // no need for checks file exists, function below does it
            $filename = File::path($v);
            if ($filename !== false) {
                $content = file_get_contents($filename);
                if ($content !== false && is_json($content)) {
                    $json = json_decode($content, true);
                    $json = \Configuration::replaceEnvironmentVariables($json);
                    $result = array_merge_hard($result, $json);
                } else {
                    throw new \Exception('Configuration JSON: file must be in json format!');
                }
            }
        }
        return $result;
    }

    /**
     * Read string
     *
     * @param string $str
     * @param string|null $environment
     * @param array $options
     * @return array
     */
    public function readString(string $str, string|null $environment = null, array $options = []): array
    {
        if (is_json($str)) {
            $result = json_decode($str, true);
            return \Configuration::replaceEnvironmentVariables($result);
        }
        throw new \Exception('Configuration JSON: str must be in json format!');
    }
}
