<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IP\Simple\Controller;

use Object\Controller\Permission;
use Object\Form\Wrapper\Import;

class IPv4 extends Permission
{
    public function actionIndex()
    {
        $form = new \Numbers\Backend\IP\Simple\Form\List2\IPv4([
            'input' => \Request::input()
        ]);
        echo $form->render();
    }
    public function actionEdit()
    {
        $form = new \Numbers\Backend\IP\Simple\Form\IPv4([
            'input' => \Request::input()
        ]);
        echo $form->render();
    }
    public function actionImport()
    {
        $form = new Import([
            'model' => '\Numbers\Backend\IP\Simple\Form\IPv4',
            'reset_table' => true,
            'input' => \Request::input(),
        ]);
        echo $form->render();
    }
}
