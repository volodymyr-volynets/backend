<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\SMS\Common\Model;

use Object\ActiveRecord;

class ProfilesAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Profiles::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_smsprofile_tenant_id','sm_smsprofile_id'];

    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_smsprofile_tenant_id = null {
        get => $this->sm_smsprofile_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprofile_tenant_id', $value);
            $this->sm_smsprofile_tenant_id = $value;
        }
    }

    /**
     * Profile #
     *
     *
     *
     * {domain{profile_id_sequence}}
     *
     * @var int|null Domain: profile_id_sequence Type: serial
     */
    public int|null $sm_smsprofile_id = null {
        get => $this->sm_smsprofile_id;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprofile_id', $value);
            $this->sm_smsprofile_id = $value;
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
    public string|null $sm_smsprofile_name = null {
        get => $this->sm_smsprofile_name;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprofile_name', $value);
            $this->sm_smsprofile_name = $value;
        }
    }

    /**
     * Type
     *
     *
     * {options_model{\Numbers\Backend\SMS\Common\Model\Profile\ProfileTypes}}
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_smsprofile_sm_smsproftype_code = null {
        get => $this->sm_smsprofile_sm_smsproftype_code;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprofile_sm_smsproftype_code', $value);
            $this->sm_smsprofile_sm_smsproftype_code = $value;
        }
    }

    /**
     * Account SID
     *
     *
     *
     * {domain{encrypted_password}}
     *
     * @var string|null Domain: encrypted_password Type: bytea
     */
    public string|null $sm_smsprofile_account_sid = null {
        get => $this->sm_smsprofile_account_sid;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprofile_account_sid', $value);
            $this->sm_smsprofile_account_sid = $value;
        }
    }

    /**
     * Auth Token
     *
     *
     *
     * {domain{encrypted_password}}
     *
     * @var string|null Domain: encrypted_password Type: bytea
     */
    public string|null $sm_smsprofile_auth_token = null {
        get => $this->sm_smsprofile_auth_token;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprofile_auth_token', $value);
            $this->sm_smsprofile_auth_token = $value;
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
    public int|null $sm_smsprofile_inactive = 0 {
        get => $this->sm_smsprofile_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprofile_inactive', $value);
            $this->sm_smsprofile_inactive = $value;
        }
    }

    /**
     * Optimistic Lock
     *
     *
     *
     * {domain{optimistic_lock}}
     *
     * @var string|null Domain: optimistic_lock Type: timestamp
     */
    public string|null $sm_smsprofile_optimistic_lock = 'now()' {
        get => $this->sm_smsprofile_optimistic_lock;
        set {
            $this->setFullPkAndFilledColumn('sm_smsprofile_optimistic_lock', $value);
            $this->sm_smsprofile_optimistic_lock = $value;
        }
    }
}
