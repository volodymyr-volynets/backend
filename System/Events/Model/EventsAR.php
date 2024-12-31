<?php

namespace Numbers\Backend\System\Events\Model;
class EventsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Events\Model\Events::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_event_code'];
    /**
     * Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_event_code = null {
                        get => $this->sm_event_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_event_code', $value);
                            $this->sm_event_code = $value;
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
    public string|null $sm_event_name = null {
                        get => $this->sm_event_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_event_name', $value);
                            $this->sm_event_name = $value;
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
    public string|null $sm_event_description = null {
                        get => $this->sm_event_description;
                        set {
                            $this->setFullPkAndFilledColumn('sm_event_description', $value);
                            $this->sm_event_description = $value;
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
    public string|null $sm_event_model = null {
                        get => $this->sm_event_model;
                        set {
                            $this->setFullPkAndFilledColumn('sm_event_model', $value);
                            $this->sm_event_model = $value;
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
    public string|null $sm_event_module_code = null {
                        get => $this->sm_event_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_event_module_code', $value);
                            $this->sm_event_module_code = $value;
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
    public int|null $sm_event_inactive = 0 {
                        get => $this->sm_event_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_event_inactive', $value);
                            $this->sm_event_inactive = $value;
                        }
                    }
}
