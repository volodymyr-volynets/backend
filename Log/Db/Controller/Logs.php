<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Db\Controller;

use Object\Controller\Permission;

class Logs extends Permission
{
    public function actionIndex()
    {
        $form = new \Numbers\Backend\Log\Db\Form\List2\Logs([
            'input' => \Request::input()
        ]);
        echo $form->render();
    }
}
