<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Model\Shareable;

use Object\ActiveRecord;

class GroupsAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Groups::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_sharegrp_code'];

    /**
     * Field Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_sharegrp_code = null {
        get => $this->sm_sharegrp_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharegrp_code', $value);
            $this->sm_sharegrp_code = $value;
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
    public string|null $sm_sharegrp_name = null {
        get => $this->sm_sharegrp_name;
        set {
            $this->setFullPkAndFilledColumn('sm_sharegrp_name', $value);
            $this->sm_sharegrp_name = $value;
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
    public string|null $sm_sharegrp_module_code = null {
        get => $this->sm_sharegrp_module_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharegrp_module_code', $value);
            $this->sm_sharegrp_module_code = $value;
        }
    }

    /**
     * T/9 Forms
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_sharegrp_t9_forms = 0 {
        get => $this->sm_sharegrp_t9_forms;
        set {
            $this->setFullPkAndFilledColumn('sm_sharegrp_t9_forms', $value);
            $this->sm_sharegrp_t9_forms = $value;
        }
    }

    /**
     * Collection Model
     *
     *
     *
     * {domain{model}}
     *
     * @var string|null Domain: model Type: varchar
     */
    public string|null $sm_sharegrp_collection_model_code = null {
        get => $this->sm_sharegrp_collection_model_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharegrp_collection_model_code', $value);
            $this->sm_sharegrp_collection_model_code = $value;
        }
    }

    /**
     * Disabled
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_sharegrp_disabled = 0 {
        get => $this->sm_sharegrp_disabled;
        set {
            $this->setFullPkAndFilledColumn('sm_sharegrp_disabled', $value);
            $this->sm_sharegrp_disabled = $value;
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
    public int|null $sm_sharegrp_inactive = 0 {
        get => $this->sm_sharegrp_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_sharegrp_inactive', $value);
            $this->sm_sharegrp_inactive = $value;
        }
    }
}
