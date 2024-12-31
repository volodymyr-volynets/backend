<?php

namespace Numbers\Backend\System\Modules\Model;
class FormsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Forms::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_form_code'];
    /**
     * Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_form_code = null {
                        get => $this->sm_form_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_form_code', $value);
                            $this->sm_form_code = $value;
                        }
                    }

    /**
     * Type
     *
     *
     * {options_model{\Numbers\Backend\System\Modules\Model\Form\Types}}
     * {domain{type_id}}
     *
     * @var int|null Domain: type_id Type: smallint
     */
    public int|null $sm_form_type = NULL {
                        get => $this->sm_form_type;
                        set {
                            $this->setFullPkAndFilledColumn('sm_form_type', $value);
                            $this->sm_form_type = $value;
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
    public string|null $sm_form_module_code = null {
                        get => $this->sm_form_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_form_module_code', $value);
                            $this->sm_form_module_code = $value;
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
    public string|null $sm_form_name = null {
                        get => $this->sm_form_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_form_name', $value);
                            $this->sm_form_name = $value;
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
    public int|null $sm_form_inactive = 0 {
                        get => $this->sm_form_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_form_inactive', $value);
                            $this->sm_form_inactive = $value;
                        }
                    }
}
