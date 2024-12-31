<?php

namespace Numbers\Backend\Db\Common\Model;
class ModelsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\Db\Common\Model\Models::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_model_id'];
    /**
     * Model #
     *
     *
     *
     * {domain{model_id_sequence}}
     *
     * @var int|null Domain: model_id_sequence Type: serial
     */
    public int|null $sm_model_id = null {
                        get => $this->sm_model_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_id', $value);
                            $this->sm_model_id = $value;
                        }
                    }

    /**
     * Model
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_model_code = null {
                        get => $this->sm_model_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_code', $value);
                            $this->sm_model_code = $value;
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
    public string|null $sm_model_name = null {
                        get => $this->sm_model_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_name', $value);
                            $this->sm_model_name = $value;
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
    public string|null $sm_model_module_code = null {
                        get => $this->sm_model_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_module_code', $value);
                            $this->sm_model_module_code = $value;
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
    public int|null $sm_model_tenant = 0 {
                        get => $this->sm_model_tenant;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_tenant', $value);
                            $this->sm_model_tenant = $value;
                        }
                    }

    /**
     * Period
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_model_period = 0 {
                        get => $this->sm_model_period;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_period', $value);
                            $this->sm_model_period = $value;
                        }
                    }

    /**
     * Widget Attributes
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_model_widget_attributes = 0 {
                        get => $this->sm_model_widget_attributes;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_widget_attributes', $value);
                            $this->sm_model_widget_attributes = $value;
                        }
                    }

    /**
     * Widget Audit
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_model_widget_audit = 0 {
                        get => $this->sm_model_widget_audit;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_widget_audit', $value);
                            $this->sm_model_widget_audit = $value;
                        }
                    }

    /**
     * Widget Addresses
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_model_widget_addressees = 0 {
                        get => $this->sm_model_widget_addressees;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_widget_addressees', $value);
                            $this->sm_model_widget_addressees = $value;
                        }
                    }

    /**
     * Data Asset Classification
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_model_da_classification = null {
                        get => $this->sm_model_da_classification;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_da_classification', $value);
                            $this->sm_model_da_classification = $value;
                        }
                    }

    /**
     * Data Asset Protection
     *
     *
     *
     *
     *
     * @var int|null Type: smallint
     */
    public int|null $sm_model_da_protection = 0 {
                        get => $this->sm_model_da_protection;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_da_protection', $value);
                            $this->sm_model_da_protection = $value;
                        }
                    }

    /**
     * Data Asset Scope
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_model_da_scope = null {
                        get => $this->sm_model_da_scope;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_da_scope', $value);
                            $this->sm_model_da_scope = $value;
                        }
                    }

    /**
     * Optimistic Lock
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_model_optimistic_lock = 0 {
                        get => $this->sm_model_optimistic_lock;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_optimistic_lock', $value);
                            $this->sm_model_optimistic_lock = $value;
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
    public int|null $sm_model_inactive = 0 {
                        get => $this->sm_model_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_model_inactive', $value);
                            $this->sm_model_inactive = $value;
                        }
                    }
}
