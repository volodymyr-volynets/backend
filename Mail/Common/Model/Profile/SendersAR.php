<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Mail\Common\Model\Profile;

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
    public array $object_table_pk = ['sm_mailprosndr_tenant_id','sm_mailprosndr_sm_mailprofile_id','sm_mailprosndr_id'];

    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_mailprosndr_tenant_id = null {
        get => $this->sm_mailprosndr_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprosndr_tenant_id', $value);
            $this->sm_mailprosndr_tenant_id = $value;
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
    public int|null $sm_mailprosndr_sm_mailprofile_id = null {
        get => $this->sm_mailprosndr_sm_mailprofile_id;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprosndr_sm_mailprofile_id', $value);
            $this->sm_mailprosndr_sm_mailprofile_id = $value;
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
    public int|null $sm_mailprosndr_id = null {
        get => $this->sm_mailprosndr_id;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprosndr_id', $value);
            $this->sm_mailprosndr_id = $value;
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
    public string|null $sm_mailprosndr_name = null {
        get => $this->sm_mailprosndr_name;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprosndr_name', $value);
            $this->sm_mailprosndr_name = $value;
        }
    }

    /**
     * Email
     *
     *
     *
     * {domain{email}}
     *
     * @var string|null Domain: email Type: varchar
     */
    public string|null $sm_mailprosndr_email = null {
        get => $this->sm_mailprosndr_email;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprosndr_email', $value);
            $this->sm_mailprosndr_email = $value;
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
    public int|null $sm_mailprosndr_inactive = 0 {
        get => $this->sm_mailprosndr_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_mailprosndr_inactive', $value);
            $this->sm_mailprosndr_inactive = $value;
        }
    }
}
