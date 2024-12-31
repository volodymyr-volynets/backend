<?php

namespace Numbers\Backend\System\Events\Model;
class SubscribersAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Events\Model\Subscribers::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_evtsubscriber_code'];
    /**
     * Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_evtsubscriber_code = null {
                        get => $this->sm_evtsubscriber_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscriber_code', $value);
                            $this->sm_evtsubscriber_code = $value;
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
    public string|null $sm_evtsubscriber_name = null {
                        get => $this->sm_evtsubscriber_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscriber_name', $value);
                            $this->sm_evtsubscriber_name = $value;
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
    public string|null $sm_evtsubscriber_model = null {
                        get => $this->sm_evtsubscriber_model;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscriber_model', $value);
                            $this->sm_evtsubscriber_model = $value;
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
    public string|null $sm_evtsubscriber_module_code = null {
                        get => $this->sm_evtsubscriber_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscriber_module_code', $value);
                            $this->sm_evtsubscriber_module_code = $value;
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
    public int|null $sm_evtsubscriber_inactive = 0 {
                        get => $this->sm_evtsubscriber_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscriber_inactive', $value);
                            $this->sm_evtsubscriber_inactive = $value;
                        }
                    }
}
