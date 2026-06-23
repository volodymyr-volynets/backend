<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\SMS\Common\Model\Profile;

use Object\ActiveRecord;

class SendersAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Senders::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_smsprosndr_tenant_id','sm_smsprosndr_sm_smsprofile_id','sm_smsprosndr_id'];

    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_smsprosndr_tenant_id = null {
        get => $this->sm_smsprosndr_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprosndr_tenant_id', $value);
            $this->sm_smsprosndr_tenant_id = $value;
        }
    }

    /**
     * Profile #
     *
     *
     *
     * {domain{profile_id}}
     *
     * @var int|null Domain: profile_id Type: integer
     */
    public int|null $sm_smsprosndr_sm_smsprofile_id = null {
        get => $this->sm_smsprosndr_sm_smsprofile_id;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprosndr_sm_smsprofile_id', $value);
            $this->sm_smsprosndr_sm_smsprofile_id = $value;
        }
    }

    /**
     * Detail #
     *
     *
     *
     * {domain{detail_id}}
     *
     * @var int|null Domain: detail_id Type: integer
     */
    public int|null $sm_smsprosndr_id = null {
        get => $this->sm_smsprosndr_id;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprosndr_id', $value);
            $this->sm_smsprosndr_id = $value;
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
    public string|null $sm_smsprosndr_name = null {
        get => $this->sm_smsprosndr_name;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprosndr_name', $value);
            $this->sm_smsprosndr_name = $value;
        }
    }

    /**
     * Phone
     *
     *
     *
     * {domain{phone}}
     *
     * @var string|null Domain: phone Type: varchar
     */
    public string|null $sm_smsprosndr_phone = null {
        get => $this->sm_smsprosndr_phone;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprosndr_phone', $value);
            $this->sm_smsprosndr_phone = $value;
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
    public int|null $sm_smsprosndr_inactive = 0 {
        get => $this->sm_smsprosndr_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprosndr_inactive', $value);
            $this->sm_smsprosndr_inactive = $value;
        }
    }
}
