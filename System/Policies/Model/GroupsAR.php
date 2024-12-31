<?php

namespace Numbers\Backend\System\Policies\Model;
class GroupsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Policies\Model\Groups::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_polgroup_tenant_id','sm_polgroup_id'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_polgroup_tenant_id = NULL {
                        get => $this->sm_polgroup_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_tenant_id', $value);
                            $this->sm_polgroup_tenant_id = $value;
                        }
                    }

    /**
     * Group #
     *
     *
     *
     * {domain{group_id_sequence}}
     *
     * @var int|null Domain: group_id_sequence Type: serial
     */
    public int|null $sm_polgroup_id = null {
                        get => $this->sm_polgroup_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_id', $value);
                            $this->sm_polgroup_id = $value;
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
    public string|null $sm_polgroup_code = null {
                        get => $this->sm_polgroup_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_code', $value);
                            $this->sm_polgroup_code = $value;
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
    public string|null $sm_polgroup_name = null {
                        get => $this->sm_polgroup_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_name', $value);
                            $this->sm_polgroup_name = $value;
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
    public string|null $sm_polgroup_description = null {
                        get => $this->sm_polgroup_description;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_description', $value);
                            $this->sm_polgroup_description = $value;
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
    public string|null $sm_polgroup_module_code = null {
                        get => $this->sm_polgroup_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_module_code', $value);
                            $this->sm_polgroup_module_code = $value;
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
    public int|null $sm_polgroup_global = 0 {
                        get => $this->sm_polgroup_global;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_global', $value);
                            $this->sm_polgroup_global = $value;
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
    public int|null $sm_polgroup_weight = NULL {
                        get => $this->sm_polgroup_weight;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_weight', $value);
                            $this->sm_polgroup_weight = $value;
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
    public int|null $sm_polgroup_inactive = 0 {
                        get => $this->sm_polgroup_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_inactive', $value);
                            $this->sm_polgroup_inactive = $value;
                        }
                    }

    /**
     * Optimistic Lock
     *
     *
     *
     * {domain{optimistic_lock}}
     *
     * @var string|null Domain: optimistic_lock Type: timestamp
     */
    public string|null $sm_polgroup_optimistic_lock = 'now()' {
                        get => $this->sm_polgroup_optimistic_lock;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_optimistic_lock', $value);
                            $this->sm_polgroup_optimistic_lock = $value;
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
    public string|null $sm_polgroup_inserted_timestamp = null {
                        get => $this->sm_polgroup_inserted_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_inserted_timestamp', $value);
                            $this->sm_polgroup_inserted_timestamp = $value;
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
    public int|null $sm_polgroup_inserted_user_id = NULL {
                        get => $this->sm_polgroup_inserted_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_inserted_user_id', $value);
                            $this->sm_polgroup_inserted_user_id = $value;
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
    public string|null $sm_polgroup_updated_timestamp = null {
                        get => $this->sm_polgroup_updated_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_updated_timestamp', $value);
                            $this->sm_polgroup_updated_timestamp = $value;
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
    public int|null $sm_polgroup_updated_user_id = NULL {
                        get => $this->sm_polgroup_updated_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgroup_updated_user_id', $value);
                            $this->sm_polgroup_updated_user_id = $value;
                        }
                    }
}
