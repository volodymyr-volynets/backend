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

class PoliciesAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Policies::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_polgropolicy_tenant_id','sm_polgropolicy_sm_polgroup_id','sm_polgropolicy_sm_policy_tenant_id','sm_polgropolicy_sm_policy_code'];

    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_polgropolicy_tenant_id = null {
        get => $this->sm_polgropolicy_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_polgropolicy_tenant_id', $value);
            $this->sm_polgropolicy_tenant_id = $value;
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
    public int|null $sm_polgropolicy_sm_polgroup_id = null {
        get => $this->sm_polgropolicy_sm_polgroup_id;
        set {
            $this->setFullPkAndFilledColumn('sm_polgropolicy_sm_polgroup_id', $value);
            $this->sm_polgropolicy_sm_polgroup_id = $value;
        }
    }

    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_polgropolicy_sm_policy_tenant_id = null {
        get => $this->sm_polgropolicy_sm_policy_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_polgropolicy_sm_policy_tenant_id', $value);
            $this->sm_polgropolicy_sm_policy_tenant_id = $value;
        }
    }

    /**
     * Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_polgropolicy_sm_policy_code = null {
        get => $this->sm_polgropolicy_sm_policy_code;
        set {
            $this->setFullPkAndFilledColumn('sm_polgropolicy_sm_policy_code', $value);
            $this->sm_polgropolicy_sm_policy_code = $value;
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
    public int|null $sm_polgropolicy_inactive = 0 {
        get => $this->sm_polgropolicy_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_polgropolicy_inactive', $value);
            $this->sm_polgropolicy_inactive = $value;
        }
    }
}
