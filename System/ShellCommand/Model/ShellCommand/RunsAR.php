<?php

namespace Numbers\Backend\System\ShellCommand\Model\ShellCommand;
class RunsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\ShellCommand\Model\ShellCommand\Runs::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_shellcomrun_tenant_id','sm_shellcomrun_id'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_shellcomrun_tenant_id = 0 {
                        get => $this->sm_shellcomrun_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcomrun_tenant_id', $value);
                            $this->sm_shellcomrun_tenant_id = $value;
                        }
                    }

    /**
     * Run #
     *
     *
     *
     * {domain{big_id_sequence}}
     *
     * @var int|null Domain: big_id_sequence Type: bigserial
     */
    public int|null $sm_shellcomrun_id = null {
                        get => $this->sm_shellcomrun_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcomrun_id', $value);
                            $this->sm_shellcomrun_id = $value;
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
    public int|null $sm_shellcomrun_status_id = NULL {
                        get => $this->sm_shellcomrun_status_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcomrun_status_id', $value);
                            $this->sm_shellcomrun_status_id = $value;
                        }
                    }

    /**
     * Start Timestamp
     *
     *
     *
     * {domain{timestamp_now}}
     *
     * @var string|null Domain: timestamp_now Type: timestamp
     */
    public string|null $sm_shellcomrun_start_timestamp = 'now()' {
                        get => $this->sm_shellcomrun_start_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcomrun_start_timestamp', $value);
                            $this->sm_shellcomrun_start_timestamp = $value;
                        }
                    }

    /**
     * Finish Timestamp
     *
     *
     *
     *
     *
     * @var string|null Type: timestamp
     */
    public string|null $sm_shellcomrun_finish_timestamp = NULL {
                        get => $this->sm_shellcomrun_finish_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcomrun_finish_timestamp', $value);
                            $this->sm_shellcomrun_finish_timestamp = $value;
                        }
                    }

    /**
     * Percent Complate
     *
     *
     *
     * {domain{percent_float}}
     *
     * @var mixed Domain: percent_float Type: bcnumeric
     */
    public mixed $sm_shellcomrun_percent_complete = 0 {
                        get => $this->sm_shellcomrun_percent_complete;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcomrun_percent_complete', $value);
                            $this->sm_shellcomrun_percent_complete = $value;
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
    public int|null $sm_shellcomrun_user_id = NULL {
                        get => $this->sm_shellcomrun_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcomrun_user_id', $value);
                            $this->sm_shellcomrun_user_id = $value;
                        }
                    }

    /**
     * Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_shellcomrun_shellcommand_code = null {
                        get => $this->sm_shellcomrun_shellcommand_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcomrun_shellcommand_code', $value);
                            $this->sm_shellcomrun_shellcommand_code = $value;
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
    public string|null $sm_shellcomrun_shellcommand_name = null {
                        get => $this->sm_shellcomrun_shellcommand_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcomrun_shellcommand_name', $value);
                            $this->sm_shellcomrun_shellcommand_name = $value;
                        }
                    }

    /**
     * Shell Output
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_shellcomrun_shell_output = NULL {
                        get => $this->sm_shellcomrun_shell_output;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcomrun_shell_output', $value);
                            $this->sm_shellcomrun_shell_output = $value;
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
    public int|null $sm_shellcomrun_inactive = 0 {
                        get => $this->sm_shellcomrun_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcomrun_inactive', $value);
                            $this->sm_shellcomrun_inactive = $value;
                        }
                    }
}
