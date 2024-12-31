<?php

namespace Numbers\Backend\System\Modules\Model\System;
class FlagsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\System\Flags::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_sysflag_id'];
    /**
     * Flag #
     *
     *
     *
     * {domain{resource_id_sequence}}
     *
     * @var int|null Domain: resource_id_sequence Type: serial
     */
    public int|null $sm_sysflag_id = null {
                        get => $this->sm_sysflag_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflag_id', $value);
                            $this->sm_sysflag_id = $value;
                        }
                    }

    /**
     * Parent Flag #
     *
     *
     *
     * {domain{resource_id}}
     *
     * @var int|null Domain: resource_id Type: integer
     */
    public int|null $sm_sysflag_parent_sysflag_id = 0 {
                        get => $this->sm_sysflag_parent_sysflag_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflag_parent_sysflag_id', $value);
                            $this->sm_sysflag_parent_sysflag_id = $value;
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
    public string|null $sm_sysflag_code = null {
                        get => $this->sm_sysflag_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflag_code', $value);
                            $this->sm_sysflag_code = $value;
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
    public string|null $sm_sysflag_name = null {
                        get => $this->sm_sysflag_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflag_name', $value);
                            $this->sm_sysflag_name = $value;
                        }
                    }

    /**
     * Icon
     *
     *
     *
     * {domain{icon}}
     *
     * @var string|null Domain: icon Type: varchar
     */
    public string|null $sm_sysflag_icon = null {
                        get => $this->sm_sysflag_icon;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflag_icon', $value);
                            $this->sm_sysflag_icon = $value;
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
    public string|null $sm_sysflag_module_code = null {
                        get => $this->sm_sysflag_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflag_module_code', $value);
                            $this->sm_sysflag_module_code = $value;
                        }
                    }

    /**
     * Disabled
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_sysflag_disabled = 0 {
                        get => $this->sm_sysflag_disabled;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflag_disabled', $value);
                            $this->sm_sysflag_disabled = $value;
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
    public int|null $sm_sysflag_inactive = 0 {
                        get => $this->sm_sysflag_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflag_inactive', $value);
                            $this->sm_sysflag_inactive = $value;
                        }
                    }
}
