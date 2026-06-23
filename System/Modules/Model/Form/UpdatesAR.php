<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Form;

use Object\ActiveRecord;

class UpdatesAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Updates::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_frmupdate_tenant_id','sm_frmupdate_id'];

    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_frmupdate_tenant_id = null {
        get => $this->sm_frmupdate_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_frmupdate_tenant_id', $value);
            $this->sm_frmupdate_tenant_id = $value;
        }
    }

    /**
     * Form Update #
     *
     *
     *
     * {domain{big_id_sequence}}
     *
     * @var int|null Domain: big_id_sequence Type: bigserial
     */
    public int|null $sm_frmupdate_id = null {
        get => $this->sm_frmupdate_id;
        set {
            $this->setFullPkAndFilledColumn('sm_frmupdate_id', $value);
            $this->sm_frmupdate_id = $value;
        }
    }

    /**
     * Form Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_frmupdate_form_code = null {
        get => $this->sm_frmupdate_form_code;
        set {
            $this->setFullPkAndFilledColumn('sm_frmupdate_form_code', $value);
            $this->sm_frmupdate_form_code = $value;
        }
    }

    /**
     * Form Pk
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_frmupdate_form_pk = null {
        get => $this->sm_frmupdate_form_pk;
        set {
            $this->setFullPkAndFilledColumn('sm_frmupdate_form_pk', $value);
            $this->sm_frmupdate_form_pk = $value;
        }
    }

    /**
     * Subform Pk
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_frmupdate_subform_pk = null {
        get => $this->sm_frmupdate_subform_pk;
        set {
            $this->setFullPkAndFilledColumn('sm_frmupdate_subform_pk', $value);
            $this->sm_frmupdate_subform_pk = $value;
        }
    }

    /**
     * Operation Name
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_frmupdate_operation_name = null {
        get => $this->sm_frmupdate_operation_name;
        set {
            $this->setFullPkAndFilledColumn('sm_frmupdate_operation_name', $value);
            $this->sm_frmupdate_operation_name = $value;
        }
    }

    /**
     * Operation Details
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_frmupdate_operation_details = null {
        get => $this->sm_frmupdate_operation_details;
        set {
            $this->setFullPkAndFilledColumn('sm_frmupdate_operation_details', $value);
            $this->sm_frmupdate_operation_details = $value;
        }
    }

    /**
     * Message
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_frmupdate_message = null {
        get => $this->sm_frmupdate_message;
        set {
            $this->setFullPkAndFilledColumn('sm_frmupdate_message', $value);
            $this->sm_frmupdate_message = $value;
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
    public int|null $sm_frmupdate_inactive = 0 {
        get => $this->sm_frmupdate_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_frmupdate_inactive', $value);
            $this->sm_frmupdate_inactive = $value;
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
    public string|null $sm_frmupdate_inserted_timestamp = null {
        get => $this->sm_frmupdate_inserted_timestamp;
        set {
            $this->setFullPkAndFilledColumn('sm_frmupdate_inserted_timestamp', $value);
            $this->sm_frmupdate_inserted_timestamp = $value;
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
    public int|null $sm_frmupdate_inserted_user_id = null {
        get => $this->sm_frmupdate_inserted_user_id;
        set {
            $this->setFullPkAndFilledColumn('sm_frmupdate_inserted_user_id', $value);
            $this->sm_frmupdate_inserted_user_id = $value;
        }
    }
}
