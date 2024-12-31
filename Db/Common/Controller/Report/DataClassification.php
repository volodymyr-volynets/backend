<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Controller\Report;

use Object\Controller\Permission;

class DataClassification extends Permission
{
    public function actionIndex()
    {
        $form = new \Numbers\Backend\Db\Common\Form\Report\DataClassification([
            'input' => \Request::input()
        ]);
        echo $form->render();
    }
}
