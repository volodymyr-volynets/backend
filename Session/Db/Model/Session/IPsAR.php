<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Session\Db\Model\Session;

use Object\ActiveRecord;

class IPsAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = IPs::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_sessips_user_ip'];

    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_sessips_tenant_id = null {
        get => $this->sm_sessips_tenant_id;
        set {
            $this->setFullPkAndFilledColumn('sm_sessips_tenant_id', $value);
            $this->sm_sessips_tenant_id = $value;
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
    public string|null $sm_sessips_session_id = null {
        get => $this->sm_sessips_session_id;
        set {
            $this->setFullPkAndFilledColumn('sm_sessips_session_id', $value);
            $this->sm_sessips_session_id = $value;
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
    public string|null $sm_sessips_last_requested = null {
        get => $this->sm_sessips_last_requested;
        set {
            $this->setFullPkAndFilledColumn('sm_sessips_last_requested', $value);
            $this->sm_sessips_last_requested = $value;
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
    public int|null $sm_sessips_user_id = null {
        get => $this->sm_sessips_user_id;
        set {
            $this->setFullPkAndFilledColumn('sm_sessips_user_id', $value);
            $this->sm_sessips_user_id = $value;
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
    public string|null $sm_sessips_user_ip = null {
        get => $this->sm_sessips_user_ip;
        set {
            $this->setFullPkAndFilledColumn('sm_sessips_user_ip', $value);
            $this->sm_sessips_user_ip = $value;
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
    public int|null $sm_sessips_pages_count = 0 {
        get => $this->sm_sessips_pages_count;
        set {
            $this->setFullPkAndFilledColumn('sm_sessips_pages_count', $value);
            $this->sm_sessips_pages_count = $value;
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
    public int|null $sm_sessips_request_count = 0 {
        get => $this->sm_sessips_request_count;
        set {
            $this->setFullPkAndFilledColumn('sm_sessips_request_count', $value);
            $this->sm_sessips_request_count = $value;
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
    public string|null $sm_sessips_bearer_token = null {
        get => $this->sm_sessips_bearer_token;
        set {
            $this->setFullPkAndFilledColumn('sm_sessips_bearer_token', $value);
            $this->sm_sessips_bearer_token = $value;
        }
    }
}
