<?php

namespace Numbers\Backend\System\Policies\Model;
class TypesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Policies\Model\Types::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_poltype_code'];
    /**
     * Code
     *
     *
     *
     * {domain{big_code}}
     *
     * @var string|null Domain: big_code Type: varchar
     */
    public string|null $sm_poltype_code = null {
                        get => $this->sm_poltype_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_code', $value);
                            $this->sm_poltype_code = $value;
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
    public string|null $sm_poltype_name = null {
                        get => $this->sm_poltype_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_name', $value);
                            $this->sm_poltype_name = $value;
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
    public string|null $sm_poltype_parent_sm_poltype_code = null {
                        get => $this->sm_poltype_parent_sm_poltype_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_parent_sm_poltype_code', $value);
                            $this->sm_poltype_parent_sm_poltype_code = $value;
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
    public string|null $sm_poltype_description = null {
                        get => $this->sm_poltype_description;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_description', $value);
                            $this->sm_poltype_description = $value;
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
    public mixed $sm_poltype_external_json = null {
                        get => $this->sm_poltype_external_json;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_external_json', $value);
                            $this->sm_poltype_external_json = $value;
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
    public mixed $sm_poltype_internal_json = null {
                        get => $this->sm_poltype_internal_json;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_internal_json', $value);
                            $this->sm_poltype_internal_json = $value;
                        }
                    }

    /**
     * For Main Groups/Policies
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_poltype_for_main = 0 {
                        get => $this->sm_poltype_for_main;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_for_main', $value);
                            $this->sm_poltype_for_main = $value;
                        }
                    }

    /**
     * Model
     *
     *
     *
     * {domain{model}}
     *
     * @var string|null Domain: model Type: varchar
     */
    public string|null $sm_poltype_model = null {
                        get => $this->sm_poltype_model;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_model', $value);
                            $this->sm_poltype_model = $value;
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
    public int|null $sm_poltype_inactive = 0 {
                        get => $this->sm_poltype_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_inactive', $value);
                            $this->sm_poltype_inactive = $value;
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
    public string|null $sm_poltype_inserted_timestamp = null {
                        get => $this->sm_poltype_inserted_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_inserted_timestamp', $value);
                            $this->sm_poltype_inserted_timestamp = $value;
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
    public int|null $sm_poltype_inserted_user_id = NULL {
                        get => $this->sm_poltype_inserted_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_inserted_user_id', $value);
                            $this->sm_poltype_inserted_user_id = $value;
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
    public string|null $sm_poltype_updated_timestamp = null {
                        get => $this->sm_poltype_updated_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_updated_timestamp', $value);
                            $this->sm_poltype_updated_timestamp = $value;
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
    public int|null $sm_poltype_updated_user_id = NULL {
                        get => $this->sm_poltype_updated_user_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_poltype_updated_user_id', $value);
                            $this->sm_poltype_updated_user_id = $value;
                        }
                    }
}
