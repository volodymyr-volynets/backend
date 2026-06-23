<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Configuration\Ini;

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
        // if its file we need to fix path
        if (empty($options['as_ini_string'])) {
            $path = File::path($path);
        }
        // and finally read ini file
        return Config::ini($path, $environment, $options);
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
}
