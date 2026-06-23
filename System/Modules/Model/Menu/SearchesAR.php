<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Menu;

use Object\ActiveRecord;

class SearchesAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Searches::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_menusearch_tenant_id','sm_menusearch_code'];

    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_menusearch_tenant_id = null {
        get => $this->sm_menusearch_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_menusearch_tenant_id', $value);
            $this->sm_menusearch_tenant_id = $value;
        }
    }

    /**
     * Search Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_menusearch_code = null {
        get => $this->sm_menusearch_code;
        set {
            $this->setFullPkAndFilledColumn('sm_menusearch_code', $value);
            $this->sm_menusearch_code = $value;
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
    public string|null $sm_menusearch_name = null {
        get => $this->sm_menusearch_name;
        set {
            $this->setFullPkAndFilledColumn('sm_menusearch_name', $value);
            $this->sm_menusearch_name = $value;
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
    public string|null $sm_menusearch_module_code = null {
        get => $this->sm_menusearch_module_code;
        set {
            $this->setFullPkAndFilledColumn('sm_menusearch_module_code', $value);
            $this->sm_menusearch_module_code = $value;
        }
    }

    /**
     * Model
     *
     *
     *
     * {domain{model}}
     *
     * @var string|null Domain: model Type: varchar
     */
    public string|null $sm_menusearch_model = null {
        get => $this->sm_menusearch_model;
        set {
            $this->setFullPkAndFilledColumn('sm_menusearch_model', $value);
            $this->sm_menusearch_model = $value;
        }
    }

    /**
     * Db Model
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_menusearch_sm_model_code = null {
        get => $this->sm_menusearch_sm_model_code;
        set {
            $this->setFullPkAndFilledColumn('sm_menusearch_sm_model_code', $value);
            $this->sm_menusearch_sm_model_code = $value;
        }
    }

    /**
     * Resource Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_menusearch_sm_resource_code = null {
        get => $this->sm_menusearch_sm_resource_code;
        set {
            $this->setFullPkAndFilledColumn('sm_menusearch_sm_resource_code', $value);
            $this->sm_menusearch_sm_resource_code = $value;
        }
    }

    /**
     * Icon
     *
     *
     *
     * {domain{icon}}
     *
     * @var string|null Domain: icon Type: varchar
     */
    public string|null $sm_menusearch_icon = null {
        get => $this->sm_menusearch_icon;
        set {
            $this->setFullPkAndFilledColumn('sm_menusearch_icon', $value);
            $this->sm_menusearch_icon = $value;
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
    public int|null $sm_menusearch_inactive = 0 {
        get => $this->sm_menusearch_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_menusearch_inactive', $value);
            $this->sm_menusearch_inactive = $value;
        }
    }
}
