<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Configuration\Environment;

use System\Config;
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
        // and finally read env files
        $result = [];
        foreach (\Configuration::getConfigurationPaths($path, $environment) as $v) {
            // no need for checks file exists, function below does it
            $filename = File::path($v);
            if ($filename !== false) {
                $content = file_get_contents($filename);
                $env = $this->parseEnvContent($content);
                $env = \Configuration::replaceEnvironmentVariables($env);
                $result = array_merge_hard($result, $env);
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
        $options['as_ini_string'] = true;
        return $this->readFile($str, $environment, $options);
    }

    /**
     * Parse env content
     *
     * @param string $content
     * @return array
     */
    protected function parseEnvContent(string $content): array
    {
        $result = [];
        $lines = explode("\n", $content);
        if ($lines) {
            foreach ($lines as $line) {
                if ($line !== "") {
                    $separator = strpos($line, '=');
                    if ($separator === false) {
                        continue;
                    }
                    $key = substr($line, 0, $separator);
                    $value = substr($line, ($separator + 1), strlen($line));
                    // process type
                    if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                        $value = trim($value, '"');
                    } elseif (is_int($value)) {
                        $value = intval($value);
                    } elseif (is_float($value)) {
                        $value = floatval($value);
                    } elseif ($value == 'false') {
                        $value = false;
                    } elseif ($value == 'true') {
                        $value = true;
                    }
                    array_key_set($result, $key, $value);
                }
            }
        }
        return $result;
    }
}
