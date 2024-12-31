<?php

namespace Numbers\Backend\System\Modules\Model\Task;
class ProgressesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Task\Progresses::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_tskprogress_tenant_id','sm_tskprogress_id'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_tskprogress_tenant_id = NULL {
                        get => $this->sm_tskprogress_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_tenant_id', $value);
                            $this->sm_tskprogress_tenant_id = $value;
                        }
                    }

    /**
     * Progress #
     *
     *
     *
     * {domain{big_id_sequence}}
     *
     * @var int|null Domain: big_id_sequence Type: bigserial
     */
    public int|null $sm_tskprogress_id = null {
                        get => $this->sm_tskprogress_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_id', $value);
                            $this->sm_tskprogress_id = $value;
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
    public string|null $sm_tskprogress_name = null {
                        get => $this->sm_tskprogress_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_name', $value);
                            $this->sm_tskprogress_name = $value;
                        }
                    }

    /**
     * Percent
     *
     *
     *
     *
     *
     * @var float|null Type: numeric
     */
    public float|null $sm_tskprogress_percent = 0 {
                        get => $this->sm_tskprogress_percent;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_percent', $value);
                            $this->sm_tskprogress_percent = $value;
                        }
                    }

    /**
     * Tasks Total
     *
     *
     *
     *
     *
     * @var int|null Type: integer
     */
    public int|null $sm_tskprogress_task_total = 1 {
                        get => $this->sm_tskprogress_task_total;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_task_total', $value);
                            $this->sm_tskprogress_task_total = $value;
                        }
                    }

    /**
     * Tasks Completed
     *
     *
     *
     *
     *
     * @var int|null Type: integer
     */
    public int|null $sm_tskprogress_task_completed = 0 {
                        get => $this->sm_tskprogress_task_completed;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_task_completed', $value);
                            $this->sm_tskprogress_task_completed = $value;
                        }
                    }

    /**
     * Finish
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_tskprogress_finish = 0 {
                        get => $this->sm_tskprogress_finish;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_finish', $value);
                            $this->sm_tskprogress_finish = $value;
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
    public int|null $sm_tskprogress_inactive = 0 {
                        get => $this->sm_tskprogress_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_inactive', $value);
                            $this->sm_tskprogress_inactive = $value;
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
    public string|null $sm_tskprogress_inserted_timestamp = null {
                        get => $this->sm_tskprogress_inserted_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_inserted_timestamp', $value);
                            $this->sm_tskprogress_inserted_timestamp = $value;
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
    public int|null $sm_tskprogress_inserted_user_id = NULL {
                        get => $this->sm_tskprogress_inserted_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_inserted_user_id', $value);
                            $this->sm_tskprogress_inserted_user_id = $value;
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
    public string|null $sm_tskprogress_updated_timestamp = null {
                        get => $this->sm_tskprogress_updated_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_updated_timestamp', $value);
                            $this->sm_tskprogress_updated_timestamp = $value;
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
    public int|null $sm_tskprogress_updated_user_id = NULL {
                        get => $this->sm_tskprogress_updated_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_tskprogress_updated_user_id', $value);
                            $this->sm_tskprogress_updated_user_id = $value;
                        }
                    }
}
