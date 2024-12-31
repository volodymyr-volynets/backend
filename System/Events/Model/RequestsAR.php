<?php

namespace Numbers\Backend\System\Events\Model;
class RequestsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Events\Model\Requests::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_evtrequest_tenant_id','sm_evtrequest_id'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_evtrequest_tenant_id = NULL {
                        get => $this->sm_evtrequest_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_tenant_id', $value);
                            $this->sm_evtrequest_tenant_id = $value;
                        }
                    }

    /**
     * UUID
     *
     *
     *
     * {domain{uuid}}
     *
     * @var string|null Domain: uuid Type: char
     */
    public string|null $sm_evtrequest_id = null {
                        get => $this->sm_evtrequest_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_id', $value);
                            $this->sm_evtrequest_id = $value;
                        }
                    }

    /**
     * User #
     *
     *
     *
     * {domain{user_id}}
     *
     * @var int|null Domain: user_id Type: bigint
     */
    public int|null $sm_evtrequest_um_user_id = NULL {
                        get => $this->sm_evtrequest_um_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_um_user_id', $value);
                            $this->sm_evtrequest_um_user_id = $value;
                        }
                    }

    /**
     * Event Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_evtrequest_sm_event_code = null {
                        get => $this->sm_evtrequest_sm_event_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_sm_event_code', $value);
                            $this->sm_evtrequest_sm_event_code = $value;
                        }
                    }

    /**
     * Event Name
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_evtrequest_sm_event_name = null {
                        get => $this->sm_evtrequest_sm_event_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_sm_event_name', $value);
                            $this->sm_evtrequest_sm_event_name = $value;
                        }
                    }

    /**
     * Queue Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_evtrequest_sm_evtqueue_code = null {
                        get => $this->sm_evtrequest_sm_evtqueue_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_sm_evtqueue_code', $value);
                            $this->sm_evtrequest_sm_evtqueue_code = $value;
                        }
                    }

    /**
     * Type
     *
     *
     *
     * {domain{type_code}}
     *
     * @var string|null Domain: type_code Type: varchar
     */
    public string|null $sm_evtrequest_sm_evttype_code = null {
                        get => $this->sm_evtrequest_sm_evttype_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_sm_evttype_code', $value);
                            $this->sm_evtrequest_sm_evttype_code = $value;
                        }
                    }

    /**
     * Status
     *
     *
     *
     * {domain{type_id}}
     *
     * @var int|null Domain: type_id Type: smallint
     */
    public int|null $sm_evtrequest_status_id = NULL {
                        get => $this->sm_evtrequest_status_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_status_id', $value);
                            $this->sm_evtrequest_status_id = $value;
                        }
                    }

    /**
     * Data
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_evtrequest_data = null {
                        get => $this->sm_evtrequest_data;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_data', $value);
                            $this->sm_evtrequest_data = $value;
                        }
                    }

    /**
     * Options
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_evtrequest_options = null {
                        get => $this->sm_evtrequest_options;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_options', $value);
                            $this->sm_evtrequest_options = $value;
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
    public int|null $sm_evtrequest_inactive = 0 {
                        get => $this->sm_evtrequest_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_inactive', $value);
                            $this->sm_evtrequest_inactive = $value;
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
    public string|null $sm_evtrequest_inserted_timestamp = null {
                        get => $this->sm_evtrequest_inserted_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_inserted_timestamp', $value);
                            $this->sm_evtrequest_inserted_timestamp = $value;
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
    public int|null $sm_evtrequest_inserted_user_id = NULL {
                        get => $this->sm_evtrequest_inserted_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_inserted_user_id', $value);
                            $this->sm_evtrequest_inserted_user_id = $value;
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
    public string|null $sm_evtrequest_updated_timestamp = null {
                        get => $this->sm_evtrequest_updated_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_updated_timestamp', $value);
                            $this->sm_evtrequest_updated_timestamp = $value;
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
    public int|null $sm_evtrequest_updated_user_id = NULL {
                        get => $this->sm_evtrequest_updated_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtrequest_updated_user_id', $value);
                            $this->sm_evtrequest_updated_user_id = $value;
                        }
                    }
}
