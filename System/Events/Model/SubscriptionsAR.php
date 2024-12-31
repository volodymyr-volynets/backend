<?php

namespace Numbers\Backend\System\Events\Model;
class SubscriptionsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Events\Model\Subscriptions::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_evtsubscription_sm_evtsubscriber_code','sm_evtsubscription_sm_event_code','sm_evtsubscription_sm_evtqueue_code'];
    /**
     * Subscriber Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_evtsubscription_sm_evtsubscriber_code = null {
                        get => $this->sm_evtsubscription_sm_evtsubscriber_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscription_sm_evtsubscriber_code', $value);
                            $this->sm_evtsubscription_sm_evtsubscriber_code = $value;
                        }
                    }

    /**
     * Event Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_evtsubscription_sm_event_code = null {
                        get => $this->sm_evtsubscription_sm_event_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscription_sm_event_code', $value);
                            $this->sm_evtsubscription_sm_event_code = $value;
                        }
                    }

    /**
     * Queue Code
     *
     *
     *
     * {domain{group_code}}
     *
     * @var string|null Domain: group_code Type: varchar
     */
    public string|null $sm_evtsubscription_sm_evtqueue_code = null {
                        get => $this->sm_evtsubscription_sm_evtqueue_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscription_sm_evtqueue_code', $value);
                            $this->sm_evtsubscription_sm_evtqueue_code = $value;
                        }
                    }

    /**
     * Type
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_evtsubscription_type_code = null {
                        get => $this->sm_evtsubscription_type_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscription_type_code', $value);
                            $this->sm_evtsubscription_type_code = $value;
                        }
                    }

    /**
     * Cron
     *
     *
     *
     * {domain{cron_expression}}
     *
     * @var string|null Domain: cron_expression Type: varchar
     */
    public string|null $sm_evtsubscription_cron = null {
                        get => $this->sm_evtsubscription_cron;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscription_cron', $value);
                            $this->sm_evtsubscription_cron = $value;
                        }
                    }

    /**
     * Max Retries
     *
     *
     *
     * {domain{counter}}
     *
     * @var int|null Domain: counter Type: integer
     */
    public int|null $sm_evtsubscription_max_retries = 0 {
                        get => $this->sm_evtsubscription_max_retries;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscription_max_retries', $value);
                            $this->sm_evtsubscription_max_retries = $value;
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
    public int|null $sm_evtsubscription_inactive = 0 {
                        get => $this->sm_evtsubscription_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_evtsubscription_inactive', $value);
                            $this->sm_evtsubscription_inactive = $value;
                        }
                    }
}
