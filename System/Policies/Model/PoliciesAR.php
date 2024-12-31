<?php

namespace Numbers\Backend\System\Policies\Model;
class PoliciesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Policies\Model\Policies::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_policy_tenant_id','sm_policy_code'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_policy_tenant_id = NULL {
                        get => $this->sm_policy_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_tenant_id', $value);
                            $this->sm_policy_tenant_id = $value;
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
    public string|null $sm_policy_code = null {
                        get => $this->sm_policy_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_code', $value);
                            $this->sm_policy_code = $value;
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
    public string|null $sm_policy_name = null {
                        get => $this->sm_policy_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_name', $value);
                            $this->sm_policy_name = $value;
                        }
                    }

    /**
     * Code
     *
     *
     *
     * {domain{big_code}}
     *
     * @var string|null Domain: big_code Type: varchar
     */
    public string|null $sm_policy_sm_poltype_code = null {
                        get => $this->sm_policy_sm_poltype_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_sm_poltype_code', $value);
                            $this->sm_policy_sm_poltype_code = $value;
                        }
                    }

    /**
     * Description
     *
     *
     *
     * {domain{description}}
     *
     * @var string|null Domain: description Type: varchar
     */
    public string|null $sm_policy_description = null {
                        get => $this->sm_policy_description;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_description', $value);
                            $this->sm_policy_description = $value;
                        }
                    }

    /**
     * Module Code
     *
     *
     *
     * {domain{module_code}}
     *
     * @var string|null Domain: module_code Type: char
     */
    public string|null $sm_policy_module_code = null {
                        get => $this->sm_policy_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_module_code', $value);
                            $this->sm_policy_module_code = $value;
                        }
                    }

    /**
     * External Json
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_policy_external_json = null {
                        get => $this->sm_policy_external_json;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_external_json', $value);
                            $this->sm_policy_external_json = $value;
                        }
                    }

    /**
     * Internal Json
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_policy_internal_json = null {
                        get => $this->sm_policy_internal_json;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_internal_json', $value);
                            $this->sm_policy_internal_json = $value;
                        }
                    }

    /**
     * Global
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_policy_global = 0 {
                        get => $this->sm_policy_global;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_global', $value);
                            $this->sm_policy_global = $value;
                        }
                    }

    /**
     * Weight
     *
     *
     *
     * {domain{weight}}
     *
     * @var int|null Domain: weight Type: integer
     */
    public int|null $sm_policy_weight = NULL {
                        get => $this->sm_policy_weight;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_weight', $value);
                            $this->sm_policy_weight = $value;
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
    public int|null $sm_policy_inactive = 0 {
                        get => $this->sm_policy_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_inactive', $value);
                            $this->sm_policy_inactive = $value;
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
    public string|null $sm_policy_inserted_timestamp = null {
                        get => $this->sm_policy_inserted_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_inserted_timestamp', $value);
                            $this->sm_policy_inserted_timestamp = $value;
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
    public int|null $sm_policy_inserted_user_id = NULL {
                        get => $this->sm_policy_inserted_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_inserted_user_id', $value);
                            $this->sm_policy_inserted_user_id = $value;
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
    public string|null $sm_policy_updated_timestamp = null {
                        get => $this->sm_policy_updated_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_updated_timestamp', $value);
                            $this->sm_policy_updated_timestamp = $value;
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
    public int|null $sm_policy_updated_user_id = NULL {
                        get => $this->sm_policy_updated_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_policy_updated_user_id', $value);
                            $this->sm_policy_updated_user_id = $value;
                        }
                    }
}
