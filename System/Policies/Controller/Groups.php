<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Policies\Controller;

use Object\Controller\Permission;

class Groups extends Permission
{
    public function actionIndex()
    {
        $form = new \Numbers\Backend\System\Policies\Form\List2\Groups([
            'input' => \Request::input()
        ]);
        echo $form->render();
    }
    public function actionEdit()
    {
        $form = new \Numbers\Backend\System\Policies\Form\Groups([
            'input' => \Request::input()
        ]);
        echo $form->render();
    }
}
