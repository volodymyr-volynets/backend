<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Resource;

use Object\ActiveRecord;

class TenantsAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Tenants::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_rsrctenant_resource_id','sm_rsrctenant_tenant_code'];
    /**
     * Resource #
     *
     *
     *
     * {domain{resource_id}}
     *
     * @var int|null Domain: resource_id Type: integer
     */
    public int|null $sm_rsrctenant_resource_id = 0 {
        get => $this->sm_rsrctenant_resource_id;
        set {
            $this->setFullPkAndFilledColumn('sm_rsrctenant_resource_id', $value);
            $this->sm_rsrctenant_resource_id = $value;
        }
    }

    /**
     * Tenant Code
     *
     *
     *
     * {domain{domain_part}}
     *
     * @var string|null Domain: domain_part Type: varchar
     */
    public string|null $sm_rsrctenant_tenant_code = null {
        get => $this->sm_rsrctenant_tenant_code;
        set {
            $this->setFullPkAndFilledColumn('sm_rsrctenant_tenant_code', $value);
            $this->sm_rsrctenant_tenant_code = $value;
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
    public int|null $sm_rsrctenant_inactive = 0 {
        get => $this->sm_rsrctenant_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_rsrctenant_inactive', $value);
            $this->sm_rsrctenant_inactive = $value;
        }
    }
}
