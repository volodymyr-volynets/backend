<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Configuration\Db\Model;

use Object\ActiveRecord;

class ConfigurationValuesAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = ConfigurationValues::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_confdbvalue_tenant_id','sm_confdbvalue_section','sm_confdbvalue_key'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_confdbvalue_tenant_id = null {
        get => $this->sm_confdbvalue_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_confdbvalue_tenant_id', $value);
            $this->sm_confdbvalue_tenant_id = $value;
        }
    }

    /**
     * Section
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_confdbvalue_section = null {
        get => $this->sm_confdbvalue_section;
        set {
            $this->setFullPkAndFilledColumn('sm_confdbvalue_section', $value);
            $this->sm_confdbvalue_section = $value;
        }
    }

    /**
     * Key
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_confdbvalue_key = null {
        get => $this->sm_confdbvalue_key;
        set {
            $this->setFullPkAndFilledColumn('sm_confdbvalue_key', $value);
            $this->sm_confdbvalue_key = $value;
        }
    }

    /**
     * Value
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_confdbvalue_value = null {
        get => $this->sm_confdbvalue_value;
        set {
            $this->setFullPkAndFilledColumn('sm_confdbvalue_value', $value);
            $this->sm_confdbvalue_value = $value;
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
    public int|null $sm_confdbvalue_inactive = 0 {
        get => $this->sm_confdbvalue_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_confdbvalue_inactive', $value);
            $this->sm_confdbvalue_inactive = $value;
        }
    }

    /**
     * Inserted Datetime
     *
     *
     *
     *
     *
     * @var string|null Type: timestamp
     */
    public string|null $sm_confdbvalue_inserted_timestamp = null {
        get => $this->sm_confdbvalue_inserted_timestamp;
        set {
            $this->setFullPkAndFilledColumn('sm_confdbvalue_inserted_timestamp', $value);
            $this->sm_confdbvalue_inserted_timestamp = $value;
        }
    }

    /**
     * Inserted User #
     *
     *
     *
     * {domain{user_id}}
     *
     * @var int|null Domain: user_id Type: bigint
     */
    public int|null $sm_confdbvalue_inserted_user_id = null {
        get => $this->sm_confdbvalue_inserted_user_id;
        set {
            $this->setFullPkAndFilledColumn('sm_confdbvalue_inserted_user_id', $value);
            $this->sm_confdbvalue_inserted_user_id = $value;
        }
    }

    /**
     * Updated Datetime
     *
     *
     *
     *
     *
     * @var string|null Type: timestamp
     */
    public string|null $sm_confdbvalue_updated_timestamp = null {
        get => $this->sm_confdbvalue_updated_timestamp;
        set {
            $this->setFullPkAndFilledColumn('sm_confdbvalue_updated_timestamp', $value);
            $this->sm_confdbvalue_updated_timestamp = $value;
        }
    }

    /**
     * Updated User #
     *
     *
     *
     * {domain{user_id}}
     *
     * @var int|null Domain: user_id Type: bigint
     */
    public int|null $sm_confdbvalue_updated_user_id = null {
        get => $this->sm_confdbvalue_updated_user_id;
        set {
            $this->setFullPkAndFilledColumn('sm_confdbvalue_updated_user_id', $value);
            $this->sm_confdbvalue_updated_user_id = $value;
        }
    }
}
