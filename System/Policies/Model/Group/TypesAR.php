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

class TypesAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Types::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_polgrotype_tenant_id','sm_polgrotype_sm_polgroup_id','sm_polgrotype_sm_poltype_code'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_polgrotype_tenant_id = null {
        get => $this->sm_polgrotype_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_polgrotype_tenant_id', $value);
            $this->sm_polgrotype_tenant_id = $value;
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
    public int|null $sm_polgrotype_sm_polgroup_id = null {
        get => $this->sm_polgrotype_sm_polgroup_id;
        set {
            $this->setFullPkAndFilledColumn('sm_polgrotype_sm_polgroup_id', $value);
            $this->sm_polgrotype_sm_polgroup_id = $value;
        }
    }

    /**
     * Type Code
     *
     *
     *
     * {domain{big_code}}
     *
     * @var string|null Domain: big_code Type: varchar
     */
    public string|null $sm_polgrotype_sm_poltype_code = null {
        get => $this->sm_polgrotype_sm_poltype_code;
        set {
            $this->setFullPkAndFilledColumn('sm_polgrotype_sm_poltype_code', $value);
            $this->sm_polgrotype_sm_poltype_code = $value;
        }
    }

    /**
     * Timestamp
     *
     *
     *
     * {domain{timestamp_now}}
     *
     * @var string|null Domain: timestamp_now Type: timestamp
     */
    public string|null $sm_polgrotype_timestamp = 'now()' {
        get => $this->sm_polgrotype_timestamp;
        set {
            $this->setFullPkAndFilledColumn('sm_polgrotype_timestamp', $value);
            $this->sm_polgrotype_timestamp = $value;
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
    public int|null $sm_polgrotype_inactive = 0 {
        get => $this->sm_polgrotype_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_polgrotype_inactive', $value);
            $this->sm_polgrotype_inactive = $value;
        }
    }
}
