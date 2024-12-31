<?php

namespace Numbers\Backend\Session\Db\Model;
class SessionsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\Session\Db\Model\Sessions::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_session_id'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_session_tenant_id = NULL {
                        get => $this->sm_session_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_tenant_id', $value);
                            $this->sm_session_tenant_id = $value;
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
    public string|null $sm_session_id = null {
                        get => $this->sm_session_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_id', $value);
                            $this->sm_session_id = $value;
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
    public string|null $sm_session_started = null {
                        get => $this->sm_session_started;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_started', $value);
                            $this->sm_session_started = $value;
                        }
                    }

    /**
     * Datetime Expires
     *
     *
     *
     *
     *
     * @var string|null Type: timestamp
     */
    public string|null $sm_session_expires = null {
                        get => $this->sm_session_expires;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_expires', $value);
                            $this->sm_session_expires = $value;
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
    public string|null $sm_session_last_requested = null {
                        get => $this->sm_session_last_requested;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_last_requested', $value);
                            $this->sm_session_last_requested = $value;
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
    public int|null $sm_session_pages_count = 0 {
                        get => $this->sm_session_pages_count;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_pages_count', $value);
                            $this->sm_session_pages_count = $value;
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
    public int|null $sm_session_user_id = NULL {
                        get => $this->sm_session_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_user_id', $value);
                            $this->sm_session_user_id = $value;
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
    public string|null $sm_session_user_ip = null {
                        get => $this->sm_session_user_ip;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_user_ip', $value);
                            $this->sm_session_user_ip = $value;
                        }
                    }

    /**
     * Session Data
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_session_data = null {
                        get => $this->sm_session_data;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_data', $value);
                            $this->sm_session_data = $value;
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
    public string|null $sm_session_country_code = null {
                        get => $this->sm_session_country_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_country_code', $value);
                            $this->sm_session_country_code = $value;
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
    public int|null $sm_session_request_count = 0 {
                        get => $this->sm_session_request_count;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_request_count', $value);
                            $this->sm_session_request_count = $value;
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
    public string|null $sm_session_bearer_token = null {
                        get => $this->sm_session_bearer_token;
                        set {
                            $this->setFullPkAndFilledColumn('sm_session_bearer_token', $value);
                            $this->sm_session_bearer_token = $value;
                        }
                    }
}
