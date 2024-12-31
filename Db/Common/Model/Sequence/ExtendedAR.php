<?php

namespace Numbers\Backend\Db\Common\Model\Sequence;
class ExtendedAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\Db\Common\Model\Sequence\Extended::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_sequence_name','sm_sequence_tenant_id','sm_sequence_module_id'];
    /**
     * Name
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_sequence_name = null {
                        get => $this->sm_sequence_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sequence_name', $value);
                            $this->sm_sequence_name = $value;
                        }
                    }

    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_sequence_tenant_id = NULL {
                        get => $this->sm_sequence_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sequence_tenant_id', $value);
                            $this->sm_sequence_tenant_id = $value;
                        }
                    }

    /**
     * Module #
     *
     *
     *
     * {domain{module_id}}
     *
     * @var int|null Domain: module_id Type: integer
     */
    public int|null $sm_sequence_module_id = NULL {
                        get => $this->sm_sequence_module_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sequence_module_id', $value);
                            $this->sm_sequence_module_id = $value;
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
    public string|null $sm_sequence_description = null {
                        get => $this->sm_sequence_description;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sequence_description', $value);
                            $this->sm_sequence_description = $value;
                        }
                    }

    /**
     * Type
     *
     *
     * {options_model{\Numbers\Backend\Db\Common\Model\Sequence\Types}}
     * {domain{type_code}}
     *
     * @var string|null Domain: type_code Type: varchar
     */
    public string|null $sm_sequence_type = null {
                        get => $this->sm_sequence_type;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sequence_type', $value);
                            $this->sm_sequence_type = $value;
                        }
                    }

    /**
     * Prefix
     *
     *
     *
     *
     *
     * @var string|null Type: varchar
     */
    public string|null $sm_sequence_prefix = null {
                        get => $this->sm_sequence_prefix;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sequence_prefix', $value);
                            $this->sm_sequence_prefix = $value;
                        }
                    }

    /**
     * Length
     *
     *
     *
     *
     *
     * @var int|null Type: smallint
     */
    public int|null $sm_sequence_length = 0 {
                        get => $this->sm_sequence_length;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sequence_length', $value);
                            $this->sm_sequence_length = $value;
                        }
                    }

    /**
     * Suffix
     *
     *
     *
     *
     *
     * @var string|null Type: varchar
     */
    public string|null $sm_sequence_suffix = null {
                        get => $this->sm_sequence_suffix;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sequence_suffix', $value);
                            $this->sm_sequence_suffix = $value;
                        }
                    }

    /**
     * Counter
     *
     *
     *
     *
     *
     * @var int|null Type: bigint
     */
    public int|null $sm_sequence_counter = 0 {
                        get => $this->sm_sequence_counter;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sequence_counter', $value);
                            $this->sm_sequence_counter = $value;
                        }
                    }
}
