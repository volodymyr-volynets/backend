<?php

namespace Numbers\Backend\Log\Db\Model;
class LogsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\Log\Db\Model\Logs::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_log_id'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_log_tenant_id = 0 {
                        get => $this->sm_log_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_tenant_id', $value);
                            $this->sm_log_tenant_id = $value;
                        }
                    }

    /**
     * Log #
     *
     *
     *
     * {domain{uuid}}
     *
     * @var string|null Domain: uuid Type: char
     */
    public string|null $sm_log_id = null {
                        get => $this->sm_log_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_id', $value);
                            $this->sm_log_id = $value;
                        }
                    }

    /**
     * Group #
     *
     *
     *
     * {domain{uuid}}
     *
     * @var string|null Domain: uuid Type: char
     */
    public string|null $sm_log_group_id = null {
                        get => $this->sm_log_group_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_group_id', $value);
                            $this->sm_log_group_id = $value;
                        }
                    }

    /**
     * Originated #
     *
     *
     *
     * {domain{uuid}}
     *
     * @var string|null Domain: uuid Type: char
     */
    public string|null $sm_log_originated_id = null {
                        get => $this->sm_log_originated_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_originated_id', $value);
                            $this->sm_log_originated_id = $value;
                        }
                    }

    /**
     * Host
     *
     *
     *
     * {domain{host}}
     *
     * @var string|null Domain: host Type: varchar
     */
    public string|null $sm_log_host = 'CLI' {
                        get => $this->sm_log_host;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_host', $value);
                            $this->sm_log_host = $value;
                        }
                    }

    /**
     * Year
     *
     *
     *
     * {domain{year}}
     *
     * @var int|null Domain: year Type: smallint
     */
    public int|null $sm_log_year = 0 {
                        get => $this->sm_log_year;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_year', $value);
                            $this->sm_log_year = $value;
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
    public int|null $sm_log_user_id = NULL {
                        get => $this->sm_log_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_user_id', $value);
                            $this->sm_log_user_id = $value;
                        }
                    }

    /**
     * User IP
     *
     *
     *
     * {domain{ip}}
     *
     * @var string|null Domain: ip Type: varchar
     */
    public string|null $sm_log_user_ip = NULL {
                        get => $this->sm_log_user_ip;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_user_ip', $value);
                            $this->sm_log_user_ip = $value;
                        }
                    }

    /**
     * Chanel
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_log_chanel = NULL {
                        get => $this->sm_log_chanel;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_chanel', $value);
                            $this->sm_log_chanel = $value;
                        }
                    }

    /**
     * Type
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_log_type = 'General' {
                        get => $this->sm_log_type;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_type', $value);
                            $this->sm_log_type = $value;
                        }
                    }

    /**
     * Level
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_log_level = 'ALL' {
                        get => $this->sm_log_level;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_level', $value);
                            $this->sm_log_level = $value;
                        }
                    }

    /**
     * Status
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_log_status = null {
                        get => $this->sm_log_status;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_status', $value);
                            $this->sm_log_status = $value;
                        }
                    }

    /**
     * Message
     *
     *
     *
     * {domain{message}}
     *
     * @var string|null Domain: message Type: text
     */
    public string|null $sm_log_message = null {
                        get => $this->sm_log_message;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_message', $value);
                            $this->sm_log_message = $value;
                        }
                    }

    /**
     * Trace
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_log_trace = null {
                        get => $this->sm_log_trace;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_trace', $value);
                            $this->sm_log_trace = $value;
                        }
                    }

    /**
     * Content Type
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_log_content_type = 'text/html' {
                        get => $this->sm_log_content_type;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_content_type', $value);
                            $this->sm_log_content_type = $value;
                        }
                    }

    /**
     * Controller Name
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_log_controller_name = NULL {
                        get => $this->sm_log_controller_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_controller_name', $value);
                            $this->sm_log_controller_name = $value;
                        }
                    }

    /**
     * Form Name
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_log_form_name = NULL {
                        get => $this->sm_log_form_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_form_name', $value);
                            $this->sm_log_form_name = $value;
                        }
                    }

    /**
     * Form Statistics
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_log_form_statistics = null {
                        get => $this->sm_log_form_statistics;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_form_statistics', $value);
                            $this->sm_log_form_statistics = $value;
                        }
                    }

    /**
     * Motifications
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_log_notifications = null {
                        get => $this->sm_log_notifications;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_notifications', $value);
                            $this->sm_log_notifications = $value;
                        }
                    }

    /**
     * Affected Users
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_log_affected_users = null {
                        get => $this->sm_log_affected_users;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_affected_users', $value);
                            $this->sm_log_affected_users = $value;
                        }
                    }

    /**
     * Affected Rows
     *
     *
     *
     * {domain{counter}}
     *
     * @var int|null Domain: counter Type: integer
     */
    public int|null $sm_log_affected_rows = 0 {
                        get => $this->sm_log_affected_rows;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_affected_rows', $value);
                            $this->sm_log_affected_rows = $value;
                        }
                    }

    /**
     * Error Rows
     *
     *
     *
     * {domain{counter}}
     *
     * @var int|null Domain: counter Type: integer
     */
    public int|null $sm_log_error_rows = 0 {
                        get => $this->sm_log_error_rows;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_error_rows', $value);
                            $this->sm_log_error_rows = $value;
                        }
                    }

    /**
     * Operation
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_log_operation = null {
                        get => $this->sm_log_operation;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_operation', $value);
                            $this->sm_log_operation = $value;
                        }
                    }

    /**
     * Duration (Float)
     *
     *
     *
     * {domain{duration_float}}
     *
     * @var mixed Domain: duration_float Type: bcnumeric
     */
    public mixed $sm_log_duration = 0 {
                        get => $this->sm_log_duration;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_duration', $value);
                            $this->sm_log_duration = $value;
                        }
                    }

    /**
     * Request URL
     *
     *
     *
     * {domain{url}}
     *
     * @var string|null Domain: url Type: varchar
     */
    public string|null $sm_log_request_url = 'None' {
                        get => $this->sm_log_request_url;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_request_url', $value);
                            $this->sm_log_request_url = $value;
                        }
                    }

    /**
     * SQL
     *
     *
     *
     * {domain{sql_long_query}}
     *
     * @var string|null Domain: sql_long_query Type: text
     */
    public string|null $sm_log_sql = NULL {
                        get => $this->sm_log_sql;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_sql', $value);
                            $this->sm_log_sql = $value;
                        }
                    }

    /**
     * AJAX
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_log_ajax = 0 {
                        get => $this->sm_log_ajax;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_ajax', $value);
                            $this->sm_log_ajax = $value;
                        }
                    }

    /**
     * Mail Sent
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_log_mail_sent = 0 {
                        get => $this->sm_log_mail_sent;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_mail_sent', $value);
                            $this->sm_log_mail_sent = $value;
                        }
                    }

    /**
     * Other
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_log_other = null {
                        get => $this->sm_log_other;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_other', $value);
                            $this->sm_log_other = $value;
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
    public int|null $sm_log_inactive = 0 {
                        get => $this->sm_log_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_inactive', $value);
                            $this->sm_log_inactive = $value;
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
    public string|null $sm_log_inserted_timestamp = null {
                        get => $this->sm_log_inserted_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_inserted_timestamp', $value);
                            $this->sm_log_inserted_timestamp = $value;
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
    public int|null $sm_log_inserted_user_id = NULL {
                        get => $this->sm_log_inserted_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_log_inserted_user_id', $value);
                            $this->sm_log_inserted_user_id = $value;
                        }
                    }
}
