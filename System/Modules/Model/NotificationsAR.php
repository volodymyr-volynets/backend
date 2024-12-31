<?php

namespace Numbers\Backend\System\Modules\Model;
class NotificationsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Notifications::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_notification_code'];
    /**
     * Code
     *
     *
     *
     * {domain{feature_code}}
     *
     * @var string|null Domain: feature_code Type: varchar
     */
    public string|null $sm_notification_code = null {
                        get => $this->sm_notification_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_notification_code', $value);
                            $this->sm_notification_code = $value;
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
    public string|null $sm_notification_name = null {
                        get => $this->sm_notification_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_notification_name', $value);
                            $this->sm_notification_name = $value;
                        }
                    }

    /**
     * Subject
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_notification_subject = null {
                        get => $this->sm_notification_subject;
                        set {
                            $this->setFullPkAndFilledColumn('sm_notification_subject', $value);
                            $this->sm_notification_subject = $value;
                        }
                    }

    /**
     * Body
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_notification_body = null {
                        get => $this->sm_notification_body;
                        set {
                            $this->setFullPkAndFilledColumn('sm_notification_body', $value);
                            $this->sm_notification_body = $value;
                        }
                    }

    /**
     * Important
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_notification_important = 0 {
                        get => $this->sm_notification_important;
                        set {
                            $this->setFullPkAndFilledColumn('sm_notification_important', $value);
                            $this->sm_notification_important = $value;
                        }
                    }

    /**
     * Email Model
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_notification_email_model_code = null {
                        get => $this->sm_notification_email_model_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_notification_email_model_code', $value);
                            $this->sm_notification_email_model_code = $value;
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
    public int|null $sm_notification_inactive = 0 {
                        get => $this->sm_notification_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_notification_inactive', $value);
                            $this->sm_notification_inactive = $value;
                        }
                    }
}
