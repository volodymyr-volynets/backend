<?php

namespace Numbers\Backend\System\ShellCommand\Model;
class ShellCommandsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\ShellCommand\Model\ShellCommands::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_shellcommand_code'];
    /**
     * Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_shellcommand_code = null {
                        get => $this->sm_shellcommand_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcommand_code', $value);
                            $this->sm_shellcommand_code = $value;
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
    public string|null $sm_shellcommand_name = null {
                        get => $this->sm_shellcommand_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcommand_name', $value);
                            $this->sm_shellcommand_name = $value;
                        }
                    }

    /**
     * Description
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_shellcommand_description = null {
                        get => $this->sm_shellcommand_description;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcommand_description', $value);
                            $this->sm_shellcommand_description = $value;
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
    public string|null $sm_shellcommand_model = null {
                        get => $this->sm_shellcommand_model;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcommand_model', $value);
                            $this->sm_shellcommand_model = $value;
                        }
                    }

    /**
     * Command
     *
     *
     *
     * {domain{command}}
     *
     * @var string|null Domain: command Type: varchar
     */
    public string|null $sm_shellcommand_command = null {
                        get => $this->sm_shellcommand_command;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcommand_command', $value);
                            $this->sm_shellcommand_command = $value;
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
    public string|null $sm_shellcommand_module_code = null {
                        get => $this->sm_shellcommand_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcommand_module_code', $value);
                            $this->sm_shellcommand_module_code = $value;
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
    public int|null $sm_shellcommand_inactive = 0 {
                        get => $this->sm_shellcommand_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcommand_inactive', $value);
                            $this->sm_shellcommand_inactive = $value;
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
    public string|null $sm_shellcommand_optimistic_lock = 'now()' {
                        get => $this->sm_shellcommand_optimistic_lock;
                        set {
                            $this->setFullPkAndFilledColumn('sm_shellcommand_optimistic_lock', $value);
                            $this->sm_shellcommand_optimistic_lock = $value;
                        }
                    }
}
