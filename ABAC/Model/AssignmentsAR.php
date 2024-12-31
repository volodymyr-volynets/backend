<?php

namespace Numbers\Backend\ABAC\Model;
class AssignmentsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\ABAC\Model\Assignments::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_abacassign_id'];
    /**
     * Attribute #
     *
     *
     *
     * {domain{attribute_id_sequence}}
     *
     * @var int|null Domain: attribute_id_sequence Type: serial
     */
    public int|null $sm_abacassign_id = null {
                        get => $this->sm_abacassign_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacassign_id', $value);
                            $this->sm_abacassign_id = $value;
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
    public string|null $sm_abacassign_code = null {
                        get => $this->sm_abacassign_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacassign_code', $value);
                            $this->sm_abacassign_code = $value;
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
    public string|null $sm_abacassign_name = null {
                        get => $this->sm_abacassign_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacassign_name', $value);
                            $this->sm_abacassign_name = $value;
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
    public string|null $sm_abacassign_module_code = null {
                        get => $this->sm_abacassign_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacassign_module_code', $value);
                            $this->sm_abacassign_module_code = $value;
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
    public int|null $sm_abacassign_model_id = NULL {
                        get => $this->sm_abacassign_model_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacassign_model_id', $value);
                            $this->sm_abacassign_model_id = $value;
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
    public string|null $sm_abacassign_model_code = null {
                        get => $this->sm_abacassign_model_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacassign_model_code', $value);
                            $this->sm_abacassign_model_code = $value;
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
    public int|null $sm_abacassign_inactive = 0 {
                        get => $this->sm_abacassign_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_abacassign_inactive', $value);
                            $this->sm_abacassign_inactive = $value;
                        }
                    }
}
