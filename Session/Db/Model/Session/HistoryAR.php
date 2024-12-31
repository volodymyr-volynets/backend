<?php

namespace Numbers\Backend\Session\Db\Model\Session;
class HistoryAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\Session\Db\Model\Session\History::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_sesshist_id'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_sesshist_tenant_id = NULL {
                        get => $this->sm_sesshist_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sesshist_tenant_id', $value);
                            $this->sm_sesshist_tenant_id = $value;
                        }
                    }

    /**
     * Login #
     *
     *
     *
     *
     *
     * @var int|null Type: bigserial
     */
    public int|null $sm_sesshist_id = null {
                        get => $this->sm_sesshist_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sesshist_id', $value);
                            $this->sm_sesshist_id = $value;
                        }
                    }

    /**
     * Datetime Started
     *
     *
     *
     *
     *
     * @var string|null Type: timestamp
     */
    public string|null $sm_sesshist_started = null {
                        get => $this->sm_sesshist_started;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sesshist_started', $value);
                            $this->sm_sesshist_started = $value;
                        }
                    }

    /**
     * Datetime Last Requested
     *
     *
     *
     *
     *
     * @var string|null Type: timestamp
     */
    public string|null $sm_sesshist_last_requested = null {
                        get => $this->sm_sesshist_last_requested;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sesshist_last_requested', $value);
                            $this->sm_sesshist_last_requested = $value;
                        }
                    }

    /**
     * Pages Count
     *
     *
     *
     * {domain{counter}}
     *
     * @var int|null Domain: counter Type: integer
     */
    public int|null $sm_sesshist_pages_count = 0 {
                        get => $this->sm_sesshist_pages_count;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sesshist_pages_count', $value);
                            $this->sm_sesshist_pages_count = $value;
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
    public int|null $sm_sesshist_user_id = NULL {
                        get => $this->sm_sesshist_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sesshist_user_id', $value);
                            $this->sm_sesshist_user_id = $value;
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
    public string|null $sm_sesshist_user_ip = null {
                        get => $this->sm_sesshist_user_ip;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sesshist_user_ip', $value);
                            $this->sm_sesshist_user_ip = $value;
                        }
                    }

    /**
     * Country Code
     *
     *
     *
     * {domain{country_code}}
     *
     * @var string|null Domain: country_code Type: char
     */
    public string|null $sm_sesshist_country_code = null {
                        get => $this->sm_sesshist_country_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sesshist_country_code', $value);
                            $this->sm_sesshist_country_code = $value;
                        }
                    }

    /**
     * Request Count
     *
     *
     *
     * {domain{counter}}
     *
     * @var int|null Domain: counter Type: integer
     */
    public int|null $sm_sesshist_request_count = 0 {
                        get => $this->sm_sesshist_request_count;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sesshist_request_count', $value);
                            $this->sm_sesshist_request_count = $value;
                        }
                    }

    /**
     * Session #
     *
     *
     *
     * {domain{session_id}}
     *
     * @var string|null Domain: session_id Type: varchar
     */
    public string|null $sm_sesshist_session_id = null {
                        get => $this->sm_sesshist_session_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sesshist_session_id', $value);
                            $this->sm_sesshist_session_id = $value;
                        }
                    }

    /**
     * Bearer Token
     *
     *
     *
     * {domain{token}}
     *
     * @var string|null Domain: token Type: varchar
     */
    public string|null $sm_sesshist_bearer_token = null {
                        get => $this->sm_sesshist_bearer_token;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sesshist_bearer_token', $value);
                            $this->sm_sesshist_bearer_token = $value;
                        }
                    }
}
