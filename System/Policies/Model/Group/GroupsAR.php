<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Policies\Model\Group;

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
    public array $object_table_pk = ['sm_polgrogroup_tenant_id','sm_polgrogroup_sm_polgroup_id','sm_polgrogroup_child_sm_polgroup_tenant_id','sm_polgrogroup_child_sm_polgroup_id'];

    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_polgrogroup_tenant_id = null {
        get => $this->sm_polgrogroup_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_polgrogroup_tenant_id', $value);
            $this->sm_polgrogroup_tenant_id = $value;
        }
    }

    /**
     * Group #
     *
     *
     *
     * {domain{group_id}}
     *
     * @var int|null Domain: group_id Type: integer
     */
    public int|null $sm_polgrogroup_sm_polgroup_id = null {
        get => $this->sm_polgrogroup_sm_polgroup_id;
        set {
            $this->setFullPkAndFilledColumn('sm_polgrogroup_sm_polgroup_id', $value);
            $this->sm_polgrogroup_sm_polgroup_id = $value;
        }
    }

    /**
     * Child Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_polgrogroup_child_sm_polgroup_tenant_id = null {
        get => $this->sm_polgrogroup_child_sm_polgroup_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_polgrogroup_child_sm_polgroup_tenant_id', $value);
            $this->sm_polgrogroup_child_sm_polgroup_tenant_id = $value;
        }
    }

    /**
     * Child Group #
     *
     *
     *
     * {domain{group_id}}
     *
     * @var int|null Domain: group_id Type: integer
     */
    public int|null $sm_polgrogroup_child_sm_polgroup_id = null {
        get => $this->sm_polgrogroup_child_sm_polgroup_id;
        set {
            $this->setFullPkAndFilledColumn('sm_polgrogroup_child_sm_polgroup_id', $value);
            $this->sm_polgrogroup_child_sm_polgroup_id = $value;
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
    public int|null $sm_polgrogroup_inactive = 0 {
        get => $this->sm_polgrogroup_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_polgrogroup_inactive', $value);
            $this->sm_polgrogroup_inactive = $value;
        }
    }
}
