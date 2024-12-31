<?php

namespace Numbers\Backend\System\Modules\Model\Form;
class FieldsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Form\Fields::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_frmfield_form_code','sm_frmfield_code'];
    /**
     * Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_frmfield_form_code = null {
                        get => $this->sm_frmfield_form_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_frmfield_form_code', $value);
                            $this->sm_frmfield_form_code = $value;
                        }
                    }

    /**
     * Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_frmfield_code = null {
                        get => $this->sm_frmfield_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_frmfield_code', $value);
                            $this->sm_frmfield_code = $value;
                        }
                    }

    /**
     * Type
     *
     *
     * {options_model{\Numbers\Backend\System\Modules\Model\Form\Field\Types}}
     * {domain{type_id}}
     *
     * @var int|null Domain: type_id Type: smallint
     */
    public int|null $sm_frmfield_type = NULL {
                        get => $this->sm_frmfield_type;
                        set {
                            $this->setFullPkAndFilledColumn('sm_frmfield_type', $value);
                            $this->sm_frmfield_type = $value;
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
    public string|null $sm_frmfield_name = null {
                        get => $this->sm_frmfield_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_frmfield_name', $value);
                            $this->sm_frmfield_name = $value;
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
    public int|null $sm_frmfield_inactive = 0 {
                        get => $this->sm_frmfield_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_frmfield_inactive', $value);
                            $this->sm_frmfield_inactive = $value;
                        }
                    }
}
