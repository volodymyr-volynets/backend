<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Configuration\Common\Class2;

abstract class Base
{
    abstract public function readFile(string $path, ?string $environment = null, array $options = []): array;
    abstract public function readString(string $str, ?string $environment = null, array $options = []): array;
}
