<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model;

use Object\ActiveRecord;

class FormsAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Forms::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_form_code'];

    /**
     * Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_form_code = null {
        get => $this->sm_form_code;
        set {
            $this->setFullPkAndFilledColumn('sm_form_code', $value);
            $this->sm_form_code = $value;
        }
    }

    /**
     * Type
     *
     *
     * {options_model{\Numbers\Backend\System\Modules\Model\Form\Types}}
     * {domain{type_id}}
     *
     * @var int|null Domain: type_id Type: smallint
     */
    public int|null $sm_form_type = null {
        get => $this->sm_form_type;
        set {
            $this->setFullPkAndFilledColumn('sm_form_type', $value);
            $this->sm_form_type = $value;
        }
    }

    /**
     * Module Code
     *
     *
     *
     * {domain{module_code}}
     *
     * @var string|null Domain: module_code Type: char
     */
    public string|null $sm_form_module_code = null {
        get => $this->sm_form_module_code;
        set {
            $this->setFullPkAndFilledColumn('sm_form_module_code', $value);
            $this->sm_form_module_code = $value;
        }
    }

    /**
     * Name
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_form_name = null {
        get => $this->sm_form_name;
        set {
            $this->setFullPkAndFilledColumn('sm_form_name', $value);
            $this->sm_form_name = $value;
        }
    }

    /**
     * Inactive
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_form_inactive = 0 {
        get => $this->sm_form_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_form_inactive', $value);
            $this->sm_form_inactive = $value;
        }
    }
}
