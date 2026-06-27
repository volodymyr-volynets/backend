<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Model\Datastore;

use Object\ActiveRecord;

class ColumnsAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Columns::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_datastcolumn_tenant_id','sm_datastcolumn_sm_datastore_code','sm_datastcolumn_code'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_datastcolumn_tenant_id = null {
        get => $this->sm_datastcolumn_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_datastcolumn_tenant_id', $value);
            $this->sm_datastcolumn_tenant_id = $value;
        }
    }

    /**
     * Datastore Code
     *
     *
     *
     * {domain{lcode}}
     *
     * @var string|null Domain: lcode Type: varchar
     */
    public string|null $sm_datastcolumn_sm_datastore_code = null {
        get => $this->sm_datastcolumn_sm_datastore_code;
        set {
            $this->setFullPkAndFilledColumn('sm_datastcolumn_sm_datastore_code', $value);
            $this->sm_datastcolumn_sm_datastore_code = $value;
        }
    }

    /**
     * Code
     *
     *
     *
     * {domain{lcode}}
     *
     * @var string|null Domain: lcode Type: varchar
     */
    public string|null $sm_datastcolumn_code = null {
        get => $this->sm_datastcolumn_code;
        set {
            $this->setFullPkAndFilledColumn('sm_datastcolumn_code', $value);
            $this->sm_datastcolumn_code = $value;
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
    public string|null $sm_datastcolumn_name = null {
        get => $this->sm_datastcolumn_name;
        set {
            $this->setFullPkAndFilledColumn('sm_datastcolumn_name', $value);
            $this->sm_datastcolumn_name = $value;
        }
    }

    /**
     * Domain
     *
     *
     *
     * {domain{ldomain}}
     *
     * @var string|null Domain: ldomain Type: varchar
     */
    public string|null $sm_datastcolumn_domain = null {
        get => $this->sm_datastcolumn_domain;
        set {
            $this->setFullPkAndFilledColumn('sm_datastcolumn_domain', $value);
            $this->sm_datastcolumn_domain = $value;
        }
    }

    /**
     * Type
     *
     *
     *
     * {domain{ltype}}
     *
     * @var string|null Domain: ltype Type: varchar
     */
    public string|null $sm_datastcolumn_type = null {
        get => $this->sm_datastcolumn_type;
        set {
            $this->setFullPkAndFilledColumn('sm_datastcolumn_type', $value);
            $this->sm_datastcolumn_type = $value;
        }
    }

    /**
     * Null
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_datastcolumn_null = 0 {
        get => $this->sm_datastcolumn_null;
        set {
            $this->setFullPkAndFilledColumn('sm_datastcolumn_null', $value);
            $this->sm_datastcolumn_null = $value;
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
    public int|null $sm_datastcolumn_order = 0 {
        get => $this->sm_datastcolumn_order;
        set {
            $this->setFullPkAndFilledColumn('sm_datastcolumn_order', $value);
            $this->sm_datastcolumn_order = $value;
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
    public int|null $sm_datastcolumn_inactive = 0 {
        get => $this->sm_datastcolumn_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_datastcolumn_inactive', $value);
            $this->sm_datastcolumn_inactive = $value;
        }
    }
}
