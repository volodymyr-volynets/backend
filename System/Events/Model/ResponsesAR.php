<?php

namespace Numbers\Backend\System\Events\Model;
class ResponsesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Events\Model\Responses::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_evtresponse_tenant_id','sm_evtresponse_id'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_evtresponse_tenant_id = NULL {
                        get => $this->sm_evtresponse_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_tenant_id', $value);
                            $this->sm_evtresponse_tenant_id = $value;
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
    public string|null $sm_evtresponse_id = null {
                        get => $this->sm_evtresponse_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_id', $value);
                            $this->sm_evtresponse_id = $value;
                        }
                    }

    /**
     * Request UUID
     *
     *
     *
     * {domain{uuid}}
     *
     * @var string|null Domain: uuid Type: char
     */
    public string|null $sm_evtresponse_sm_evtrequest_id = null {
                        get => $this->sm_evtresponse_sm_evtrequest_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_sm_evtrequest_id', $value);
                            $this->sm_evtresponse_sm_evtrequest_id = $value;
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
    public int|null $sm_evtresponse_um_user_id = NULL {
                        get => $this->sm_evtresponse_um_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_um_user_id', $value);
                            $this->sm_evtresponse_um_user_id = $value;
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
    public string|null $sm_evtresponse_sm_event_code = null {
                        get => $this->sm_evtresponse_sm_event_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_sm_event_code', $value);
                            $this->sm_evtresponse_sm_event_code = $value;
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
    public string|null $sm_evtresponse_sm_event_name = null {
                        get => $this->sm_evtresponse_sm_event_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_sm_event_name', $value);
                            $this->sm_evtresponse_sm_event_name = $value;
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
    public string|null $sm_evtresponse_sm_evtqueue_code = null {
                        get => $this->sm_evtresponse_sm_evtqueue_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_sm_evtqueue_code', $value);
                            $this->sm_evtresponse_sm_evtqueue_code = $value;
                        }
                    }

    /**
     * Subscriber Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_evtresponse_sm_evtsubscriber_code = null {
                        get => $this->sm_evtresponse_sm_evtsubscriber_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_sm_evtsubscriber_code', $value);
                            $this->sm_evtresponse_sm_evtsubscriber_code = $value;
                        }
                    }

    /**
     * Subscriber Name
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_evtresponse_sm_evtsubscriber_name = null {
                        get => $this->sm_evtresponse_sm_evtsubscriber_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_sm_evtsubscriber_name', $value);
                            $this->sm_evtresponse_sm_evtsubscriber_name = $value;
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
    public int|null $sm_evtresponse_status_id = NULL {
                        get => $this->sm_evtresponse_status_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_status_id', $value);
                            $this->sm_evtresponse_status_id = $value;
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
    public mixed $sm_evtresponse_result = null {
                        get => $this->sm_evtresponse_result;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_result', $value);
                            $this->sm_evtresponse_result = $value;
                        }
                    }

    /**
     * Retry
     *
     *
     *
     * {domain{counter}}
     *
     * @var int|null Domain: counter Type: integer
     */
    public int|null $sm_evtresponse_retry = 0 {
                        get => $this->sm_evtresponse_retry;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_retry', $value);
                            $this->sm_evtresponse_retry = $value;
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
    public int|null $sm_evtresponse_inactive = 0 {
                        get => $this->sm_evtresponse_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_inactive', $value);
                            $this->sm_evtresponse_inactive = $value;
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
    public string|null $sm_evtresponse_inserted_timestamp = null {
                        get => $this->sm_evtresponse_inserted_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_inserted_timestamp', $value);
                            $this->sm_evtresponse_inserted_timestamp = $value;
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
    public int|null $sm_evtresponse_inserted_user_id = NULL {
                        get => $this->sm_evtresponse_inserted_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_inserted_user_id', $value);
                            $this->sm_evtresponse_inserted_user_id = $value;
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
    public string|null $sm_evtresponse_updated_timestamp = null {
                        get => $this->sm_evtresponse_updated_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_updated_timestamp', $value);
                            $this->sm_evtresponse_updated_timestamp = $value;
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
    public int|null $sm_evtresponse_updated_user_id = NULL {
                        get => $this->sm_evtresponse_updated_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtresponse_updated_user_id', $value);
                            $this->sm_evtresponse_updated_user_id = $value;
                        }
                    }
}
