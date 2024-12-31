<?php

namespace Numbers\Backend\System\Modules\Model;
class ModulesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Modules::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_module_code'];
    /**
     * Module Code
     *
     *
     *
     * {domain{module_code}}
     *
     * @var string|null Domain: module_code Type: char
     */
    public string|null $sm_module_code = null {
                        get => $this->sm_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_module_code', $value);
                            $this->sm_module_code = $value;
                        }
                    }

    /**
     * Type
     *
     *
     * {options_model{\Numbers\Backend\System\Modules\Model\Module\Types}}
     * {domain{type_id}}
     *
     * @var int|null Domain: type_id Type: smallint
     */
    public int|null $sm_module_type = NULL {
                        get => $this->sm_module_type;
                        set {
                            $this->setFullPkAndFilledColumn('sm_module_type', $value);
                            $this->sm_module_type = $value;
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
    public string|null $sm_module_name = null {
                        get => $this->sm_module_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_module_name', $value);
                            $this->sm_module_name = $value;
                        }
                    }

    /**
     * Abbreviation
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_module_abbreviation = null {
                        get => $this->sm_module_abbreviation;
                        set {
                            $this->setFullPkAndFilledColumn('sm_module_abbreviation', $value);
                            $this->sm_module_abbreviation = $value;
                        }
                    }

    /**
     * Name
     *
     *
     *
     * {domain{icon}}
     *
     * @var string|null Domain: icon Type: varchar
     */
    public string|null $sm_module_icon = null {
                        get => $this->sm_module_icon;
                        set {
                            $this->setFullPkAndFilledColumn('sm_module_icon', $value);
                            $this->sm_module_icon = $value;
                        }
                    }

    /**
     * Transactions
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_module_transactions = 0 {
                        get => $this->sm_module_transactions;
                        set {
                            $this->setFullPkAndFilledColumn('sm_module_transactions', $value);
                            $this->sm_module_transactions = $value;
                        }
                    }

    /**
     * Multiple
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_module_multiple = 0 {
                        get => $this->sm_module_multiple;
                        set {
                            $this->setFullPkAndFilledColumn('sm_module_multiple', $value);
                            $this->sm_module_multiple = $value;
                        }
                    }

    /**
     * Activation Model
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_module_activation_model = null {
                        get => $this->sm_module_activation_model;
                        set {
                            $this->setFullPkAndFilledColumn('sm_module_activation_model', $value);
                            $this->sm_module_activation_model = $value;
                        }
                    }

    /**
     * Reset Model
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_module_reset_model = null {
                        get => $this->sm_module_reset_model;
                        set {
                            $this->setFullPkAndFilledColumn('sm_module_reset_model', $value);
                            $this->sm_module_reset_model = $value;
                        }
                    }

    /**
     * Custom Activation
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_module_custom_activation = 0 {
                        get => $this->sm_module_custom_activation;
                        set {
                            $this->setFullPkAndFilledColumn('sm_module_custom_activation', $value);
                            $this->sm_module_custom_activation = $value;
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
    public int|null $sm_module_inactive = 0 {
                        get => $this->sm_module_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_module_inactive', $value);
                            $this->sm_module_inactive = $value;
                        }
                    }
}
