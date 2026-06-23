<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Mail\Common\Model;

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
    public array $object_table_pk = ['sm_mailprofile_tenant_id','sm_mailprofile_id'];

    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_mailprofile_tenant_id = null {
        get => $this->sm_mailprofile_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_tenant_id', $value);
            $this->sm_mailprofile_tenant_id = $value;
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
    public int|null $sm_mailprofile_id = null {
        get => $this->sm_mailprofile_id;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_id', $value);
            $this->sm_mailprofile_id = $value;
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
    public string|null $sm_mailprofile_name = null {
        get => $this->sm_mailprofile_name;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_name', $value);
            $this->sm_mailprofile_name = $value;
        }
    }

    /**
     * Type
     *
     *
     * {options_model{\Numbers\Backend\Mail\Common\Model\Profile\ProfileTypes}}
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_mailprofile_sm_mailproftype_code = null {
        get => $this->sm_mailprofile_sm_mailproftype_code;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_sm_mailproftype_code', $value);
            $this->sm_mailprofile_sm_mailproftype_code = $value;
        }
    }

    /**
     * Host
     *
     *
     *
     * {domain{host2}}
     *
     * @var string|null Domain: host2 Type: varchar
     */
    public string|null $sm_mailprofile_host = null {
        get => $this->sm_mailprofile_host;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_host', $value);
            $this->sm_mailprofile_host = $value;
        }
    }

    /**
     * Port
     *
     *
     *
     * {domain{port}}
     *
     * @var int|null Domain: port Type: integer
     */
    public int|null $sm_mailprofile_port = 0 {
        get => $this->sm_mailprofile_port;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_port', $value);
            $this->sm_mailprofile_port = $value;
        }
    }

    /**
     * Auth
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_mailprofile_auth = 0 {
        get => $this->sm_mailprofile_auth;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_auth', $value);
            $this->sm_mailprofile_auth = $value;
        }
    }

    /**
     * Secure
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_mailprofile_secure = null {
        get => $this->sm_mailprofile_secure;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_secure', $value);
            $this->sm_mailprofile_secure = $value;
        }
    }

    /**
     * Username
     *
     *
     *
     * {domain{encrypted_password}}
     *
     * @var string|null Domain: encrypted_password Type: bytea
     */
    public string|null $sm_mailprofile_username = null {
        get => $this->sm_mailprofile_username;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_username', $value);
            $this->sm_mailprofile_username = $value;
        }
    }

    /**
     * Password
     *
     *
     *
     * {domain{encrypted_password}}
     *
     * @var string|null Domain: encrypted_password Type: bytea
     */
    public string|null $sm_mailprofile_password = null {
        get => $this->sm_mailprofile_password;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_password', $value);
            $this->sm_mailprofile_password = $value;
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
    public int|null $sm_mailprofile_inactive = 0 {
        get => $this->sm_mailprofile_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_inactive', $value);
            $this->sm_mailprofile_inactive = $value;
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
    public string|null $sm_mailprofile_optimistic_lock = 'now()' {
        get => $this->sm_mailprofile_optimistic_lock;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprofile_optimistic_lock', $value);
            $this->sm_mailprofile_optimistic_lock = $value;
        }
    }
}
