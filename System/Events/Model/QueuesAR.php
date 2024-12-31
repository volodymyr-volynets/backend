<?php

namespace Numbers\Backend\System\Events\Model;
class QueuesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Events\Model\Queues::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_evtqueue_code'];
    /**
     * Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_evtqueue_code = null {
                        get => $this->sm_evtqueue_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtqueue_code', $value);
                            $this->sm_evtqueue_code = $value;
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
    public string|null $sm_evtqueue_name = null {
                        get => $this->sm_evtqueue_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtqueue_name', $value);
                            $this->sm_evtqueue_name = $value;
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
    public int|null $sm_evtqueue_inactive = 0 {
                        get => $this->sm_evtqueue_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtqueue_inactive', $value);
                            $this->sm_evtqueue_inactive = $value;
                        }
                    }
}
