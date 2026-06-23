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

class FieldsAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Fields::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_sharefield_code'];

    /**
     * Field Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_sharefield_code = null {
        get => $this->sm_sharefield_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_code', $value);
            $this->sm_sharefield_code = $value;
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
    public string|null $sm_sharefield_name = null {
        get => $this->sm_sharefield_name;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_name', $value);
            $this->sm_sharefield_name = $value;
        }
    }

    /**
     * Type Code
     *
     *
     * {options_model{\Numbers\Backend\Db\Common\Model\Shareable\Types}}
     * {domain{type_code}}
     *
     * @var string|null Domain: type_code Type: varchar
     */
    public string|null $sm_sharefield_type_code = null {
        get => $this->sm_sharefield_type_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_type_code', $value);
            $this->sm_sharefield_type_code = $value;
        }
    }

    /**
     * Types
     *
     *
     *
     * {domain{types}}
     *
     * @var string|null Domain: types Type: varchar
     */
    public string|null $sm_sharefield_types = null {
        get => $this->sm_sharefield_types;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_types', $value);
            $this->sm_sharefield_types = $value;
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
    public string|null $sm_sharefield_module_code = null {
        get => $this->sm_sharefield_module_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_module_code', $value);
            $this->sm_sharefield_module_code = $value;
        }
    }

    /**
     * Shareable Group
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_sharefield_sm_sharegrp_code = null {
        get => $this->sm_sharefield_sm_sharegrp_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_sm_sharegrp_code', $value);
            $this->sm_sharefield_sm_sharegrp_code = $value;
        }
    }

    /**
     * Parent Field Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_sharefield_parent_sm_sharefield_code = null {
        get => $this->sm_sharefield_parent_sm_sharefield_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_parent_sm_sharefield_code', $value);
            $this->sm_sharefield_parent_sm_sharefield_code = $value;
        }
    }

    /**
     * Options Model
     *
     *
     *
     * {domain{model}}
     *
     * @var string|null Domain: model Type: varchar
     */
    public string|null $sm_sharefield_originated_model_code = null {
        get => $this->sm_sharefield_originated_model_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_originated_model_code', $value);
            $this->sm_sharefield_originated_model_code = $value;
        }
    }

    /**
     * Options Model
     *
     *
     *
     * {domain{model}}
     *
     * @var string|null Domain: model Type: varchar
     */
    public string|null $sm_sharefield_options_model_code = null {
        get => $this->sm_sharefield_options_model_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_options_model_code', $value);
            $this->sm_sharefield_options_model_code = $value;
        }
    }

    /**
     * Detail Model
     *
     *
     *
     * {domain{model}}
     *
     * @var string|null Domain: model Type: varchar
     */
    public string|null $sm_sharefield_detail_model_code = null {
        get => $this->sm_sharefield_detail_model_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_detail_model_code', $value);
            $this->sm_sharefield_detail_model_code = $value;
        }
    }

    /**
     * Global Get Model
     *
     *
     *
     * {domain{model}}
     *
     * @var string|null Domain: model Type: varchar
     */
    public string|null $sm_sharefield_global_get_model_code = null {
        get => $this->sm_sharefield_global_get_model_code;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_global_get_model_code', $value);
            $this->sm_sharefield_global_get_model_code = $value;
        }
    }

    /**
     * Order
     *
     *
     *
     * {domain{order}}
     *
     * @var int|null Domain: order Type: integer
     */
    public int|null $sm_sharefield_order = 0 {
        get => $this->sm_sharefield_order;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_order', $value);
            $this->sm_sharefield_order = $value;
        }
    }

    /**
     * Placeholder
     *
     *
     *
     * {domain{placeholder}}
     *
     * @var string|null Domain: placeholder Type: varchar
     */
    public string|null $sm_sharefield_placeholder = null {
        get => $this->sm_sharefield_placeholder;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_placeholder', $value);
            $this->sm_sharefield_placeholder = $value;
        }
    }

    /**
     * SQL Name
     *
     *
     *
     * {domain{field_code}}
     *
     * @var string|null Domain: field_code Type: varchar
     */
    public string|null $sm_sharefield_sql_name = null {
        get => $this->sm_sharefield_sql_name;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_sql_name', $value);
            $this->sm_sharefield_sql_name = $value;
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
    public int|null $sm_sharefield_disabled = 0 {
        get => $this->sm_sharefield_disabled;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_disabled', $value);
            $this->sm_sharefield_disabled = $value;
        }
    }

    /**
     * Global
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_sharefield_global = 0 {
        get => $this->sm_sharefield_global;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_global', $value);
            $this->sm_sharefield_global = $value;
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
    public int|null $sm_sharefield_inactive = 0 {
        get => $this->sm_sharefield_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_sharefield_inactive', $value);
            $this->sm_sharefield_inactive = $value;
        }
    }
}
