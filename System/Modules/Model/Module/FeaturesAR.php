<?php

namespace Numbers\Backend\System\Modules\Model\Module;
class FeaturesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Module\Features::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_feature_code'];
    /**
     * Module Code
     *
     *
     *
     * {domain{module_code}}
     *
     * @var string|null Domain: module_code Type: char
     */
    public string|null $sm_feature_module_code = null {
                        get => $this->sm_feature_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_module_code', $value);
                            $this->sm_feature_module_code = $value;
                        }
                    }

    /**
     * Feature Code
     *
     *
     *
     * {domain{feature_code}}
     *
     * @var string|null Domain: feature_code Type: varchar
     */
    public string|null $sm_feature_code = null {
                        get => $this->sm_feature_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_code', $value);
                            $this->sm_feature_code = $value;
                        }
                    }

    /**
     * Type
     *
     *
     * {options_model{\Numbers\Backend\System\Modules\Model\Module\Feature\Types}}
     * {domain{type_id}}
     *
     * @var int|null Domain: type_id Type: smallint
     */
    public int|null $sm_feature_type = NULL {
                        get => $this->sm_feature_type;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_type', $value);
                            $this->sm_feature_type = $value;
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
    public string|null $sm_feature_name = null {
                        get => $this->sm_feature_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_name', $value);
                            $this->sm_feature_name = $value;
                        }
                    }

    /**
     * Name
     *
     *
     *
     * {domain{icon}}
     *
     * @var string|null Domain: icon Type: varchar
     */
    public string|null $sm_feature_icon = null {
                        get => $this->sm_feature_icon;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_icon', $value);
                            $this->sm_feature_icon = $value;
                        }
                    }

    /**
     * Activation Model
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_feature_activation_model = null {
                        get => $this->sm_feature_activation_model;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_activation_model', $value);
                            $this->sm_feature_activation_model = $value;
                        }
                    }

    /**
     * Reset Model
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_feature_reset_model = null {
                        get => $this->sm_feature_reset_model;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_reset_model', $value);
                            $this->sm_feature_reset_model = $value;
                        }
                    }

    /**
     * Activated By Default
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_feature_activated_by_default = 0 {
                        get => $this->sm_feature_activated_by_default;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_activated_by_default', $value);
                            $this->sm_feature_activated_by_default = $value;
                        }
                    }

    /**
     * Common Notification Code
     *
     *
     *
     * {domain{feature_code}}
     *
     * @var string|null Domain: feature_code Type: varchar
     */
    public string|null $sm_feature_common_notification_feature_code = null {
                        get => $this->sm_feature_common_notification_feature_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_common_notification_feature_code', $value);
                            $this->sm_feature_common_notification_feature_code = $value;
                        }
                    }

    /**
     * Prohibitive
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_feature_prohibitive = 0 {
                        get => $this->sm_feature_prohibitive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_prohibitive', $value);
                            $this->sm_feature_prohibitive = $value;
                        }
                    }

    /**
     * Role codes (comma separated)
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_feature_role_codes = null {
                        get => $this->sm_feature_role_codes;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_role_codes', $value);
                            $this->sm_feature_role_codes = $value;
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
    public int|null $sm_feature_inactive = 0 {
                        get => $this->sm_feature_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_feature_inactive', $value);
                            $this->sm_feature_inactive = $value;
                        }
                    }
}
