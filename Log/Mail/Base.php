<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Mail;

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

        return RESULT_SUCCESS;
    }
}
