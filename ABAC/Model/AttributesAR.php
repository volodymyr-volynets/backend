<?php

namespace Numbers\Backend\ABAC\Model;
class AttributesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\ABAC\Model\Attributes::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_abacattr_id'];
    /**
     * Attribute #
     *
     *
     *
     * {domain{attribute_id_sequence}}
     *
     * @var int|null Domain: attribute_id_sequence Type: serial
     */
    public int|null $sm_abacattr_id = null {
                        get => $this->sm_abacattr_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_id', $value);
                            $this->sm_abacattr_id = $value;
                        }
                    }

    /**
     * Code
     *
     *
     *
     * {domain{field_code}}
     *
     * @var string|null Domain: field_code Type: varchar
     */
    public string|null $sm_abacattr_code = null {
                        get => $this->sm_abacattr_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_code', $value);
                            $this->sm_abacattr_code = $value;
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
    public string|null $sm_abacattr_name = null {
                        get => $this->sm_abacattr_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_name', $value);
                            $this->sm_abacattr_name = $value;
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
    public string|null $sm_abacattr_module_code = null {
                        get => $this->sm_abacattr_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_module_code', $value);
                            $this->sm_abacattr_module_code = $value;
                        }
                    }

    /**
     * Parent Attribute #
     *
     *
     *
     * {domain{attribute_id}}
     *
     * @var int|null Domain: attribute_id Type: integer
     */
    public int|null $sm_abacattr_parent_abacattr_id = NULL {
                        get => $this->sm_abacattr_parent_abacattr_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_parent_abacattr_id', $value);
                            $this->sm_abacattr_parent_abacattr_id = $value;
                        }
                    }

    /**
     * Tenant
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_abacattr_tenant = 0 {
                        get => $this->sm_abacattr_tenant;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_tenant', $value);
                            $this->sm_abacattr_tenant = $value;
                        }
                    }

    /**
     * Module
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_abacattr_module = 0 {
                        get => $this->sm_abacattr_module;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_module', $value);
                            $this->sm_abacattr_module = $value;
                        }
                    }

    /**
     * Flag ABAC
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_abacattr_flag_abac = 0 {
                        get => $this->sm_abacattr_flag_abac;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_flag_abac', $value);
                            $this->sm_abacattr_flag_abac = $value;
                        }
                    }

    /**
     * Flag Assignment
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_abacattr_flag_assingment = 0 {
                        get => $this->sm_abacattr_flag_assingment;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_flag_assingment', $value);
                            $this->sm_abacattr_flag_assingment = $value;
                        }
                    }

    /**
     * Flag Attribute
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_abacattr_flag_attribute = 0 {
                        get => $this->sm_abacattr_flag_attribute;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_flag_attribute', $value);
                            $this->sm_abacattr_flag_attribute = $value;
                        }
                    }

    /**
     * Flag Link
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_abacattr_flag_link = 0 {
                        get => $this->sm_abacattr_flag_link;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_flag_link', $value);
                            $this->sm_abacattr_flag_link = $value;
                        }
                    }

    /**
     * Flag Other Table
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_abacattr_flag_other_table = 0 {
                        get => $this->sm_abacattr_flag_other_table;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_flag_other_table', $value);
                            $this->sm_abacattr_flag_other_table = $value;
                        }
                    }

    /**
     * Model #
     *
     *
     *
     * {domain{model_id}}
     *
     * @var int|null Domain: model_id Type: integer
     */
    public int|null $sm_abacattr_model_id = NULL {
                        get => $this->sm_abacattr_model_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_model_id', $value);
                            $this->sm_abacattr_model_id = $value;
                        }
                    }

    /**
     * Link Model #
     *
     *
     *
     * {domain{model_id}}
     *
     * @var int|null Domain: model_id Type: integer
     */
    public int|null $sm_abacattr_link_model_id = NULL {
                        get => $this->sm_abacattr_link_model_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_link_model_id', $value);
                            $this->sm_abacattr_link_model_id = $value;
                        }
                    }

    /**
     * Domain
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_abacattr_domain = null {
                        get => $this->sm_abacattr_domain;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_domain', $value);
                            $this->sm_abacattr_domain = $value;
                        }
                    }

    /**
     * Type
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_abacattr_type = null {
                        get => $this->sm_abacattr_type;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_type', $value);
                            $this->sm_abacattr_type = $value;
                        }
                    }

    /**
     * Is Numeric Key
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_abacattr_is_numeric_key = 0 {
                        get => $this->sm_abacattr_is_numeric_key;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_is_numeric_key', $value);
                            $this->sm_abacattr_is_numeric_key = $value;
                        }
                    }

    /**
     * Environment Method
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_abacattr_environment_method = null {
                        get => $this->sm_abacattr_environment_method;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_environment_method', $value);
                            $this->sm_abacattr_environment_method = $value;
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
    public int|null $sm_abacattr_inactive = 0 {
                        get => $this->sm_abacattr_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacattr_inactive', $value);
                            $this->sm_abacattr_inactive = $value;
                        }
                    }
}
