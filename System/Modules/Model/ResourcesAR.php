<?php

namespace Numbers\Backend\System\Modules\Model;
class ResourcesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Resources::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_resource_id'];
    /**
     * Resource #
     *
     *
     *
     * {domain{resource_id_sequence}}
     *
     * @var int|null Domain: resource_id_sequence Type: serial
     */
    public int|null $sm_resource_id = null {
                        get => $this->sm_resource_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_id', $value);
                            $this->sm_resource_id = $value;
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
    public string|null $sm_resource_code = null {
                        get => $this->sm_resource_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_code', $value);
                            $this->sm_resource_code = $value;
                        }
                    }

    /**
     * Type
     *
     *
     * {options_model{\Numbers\Backend\System\Modules\Model\Resource\Types}}
     * {domain{type_id}}
     *
     * @var int|null Domain: type_id Type: smallint
     */
    public int|null $sm_resource_type = NULL {
                        get => $this->sm_resource_type;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_type', $value);
                            $this->sm_resource_type = $value;
                        }
                    }

    /**
     * Classification
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_classification = null {
                        get => $this->sm_resource_classification;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_classification', $value);
                            $this->sm_resource_classification = $value;
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
    public string|null $sm_resource_name = null {
                        get => $this->sm_resource_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_name', $value);
                            $this->sm_resource_name = $value;
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
    public string|null $sm_resource_description = null {
                        get => $this->sm_resource_description;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_description', $value);
                            $this->sm_resource_description = $value;
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
    public string|null $sm_resource_icon = null {
                        get => $this->sm_resource_icon;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_icon', $value);
                            $this->sm_resource_icon = $value;
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
    public string|null $sm_resource_module_code = null {
                        get => $this->sm_resource_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_module_code', $value);
                            $this->sm_resource_module_code = $value;
                        }
                    }

    /**
     * Extra Module Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_resource_extra_module_code = null {
                        get => $this->sm_resource_extra_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_extra_module_code', $value);
                            $this->sm_resource_extra_module_code = $value;
                        }
                    }

    /**
     * Group 1
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_group1_name = null {
                        get => $this->sm_resource_group1_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_group1_name', $value);
                            $this->sm_resource_group1_name = $value;
                        }
                    }

    /**
     * Group 2
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_group2_name = null {
                        get => $this->sm_resource_group2_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_group2_name', $value);
                            $this->sm_resource_group2_name = $value;
                        }
                    }

    /**
     * Group 3
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_group3_name = null {
                        get => $this->sm_resource_group3_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_group3_name', $value);
                            $this->sm_resource_group3_name = $value;
                        }
                    }

    /**
     * Group 4
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_group4_name = null {
                        get => $this->sm_resource_group4_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_group4_name', $value);
                            $this->sm_resource_group4_name = $value;
                        }
                    }

    /**
     * Group 5
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_group5_name = null {
                        get => $this->sm_resource_group5_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_group5_name', $value);
                            $this->sm_resource_group5_name = $value;
                        }
                    }

    /**
     * Group 6
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_group6_name = null {
                        get => $this->sm_resource_group6_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_group6_name', $value);
                            $this->sm_resource_group6_name = $value;
                        }
                    }

    /**
     * Group 7
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_group7_name = null {
                        get => $this->sm_resource_group7_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_group7_name', $value);
                            $this->sm_resource_group7_name = $value;
                        }
                    }

    /**
     * Group 8
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_group8_name = null {
                        get => $this->sm_resource_group8_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_group8_name', $value);
                            $this->sm_resource_group8_name = $value;
                        }
                    }

    /**
     * Group 9
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_group9_name = null {
                        get => $this->sm_resource_group9_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_group9_name', $value);
                            $this->sm_resource_group9_name = $value;
                        }
                    }

    /**
     * Version Code
     *
     *
     *
     * {domain{version_code}}
     *
     * @var string|null Domain: version_code Type: varchar
     */
    public string|null $sm_resource_version_code = null {
                        get => $this->sm_resource_version_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_version_code', $value);
                            $this->sm_resource_version_code = $value;
                        }
                    }

    /**
     * API Method Counter
     *
     *
     *
     * {domain{counter}}
     *
     * @var int|null Domain: counter Type: integer
     */
    public int|null $sm_resource_api_method_counter = 0 {
                        get => $this->sm_resource_api_method_counter;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_api_method_counter', $value);
                            $this->sm_resource_api_method_counter = $value;
                        }
                    }

    /**
     * Acl Public
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_resource_acl_public = 0 {
                        get => $this->sm_resource_acl_public;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_acl_public', $value);
                            $this->sm_resource_acl_public = $value;
                        }
                    }

    /**
     * Acl Authorized
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_resource_acl_authorized = 0 {
                        get => $this->sm_resource_acl_authorized;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_acl_authorized', $value);
                            $this->sm_resource_acl_authorized = $value;
                        }
                    }

    /**
     * Acl Permission
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_resource_acl_permission = 0 {
                        get => $this->sm_resource_acl_permission;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_acl_permission', $value);
                            $this->sm_resource_acl_permission = $value;
                        }
                    }

    /**
     * Acl Resource #
     *
     *
     *
     * {domain{resource_id}}
     *
     * @var int|null Domain: resource_id Type: integer
     */
    public int|null $sm_resource_menu_acl_resource_id = 0 {
                        get => $this->sm_resource_menu_acl_resource_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_menu_acl_resource_id', $value);
                            $this->sm_resource_menu_acl_resource_id = $value;
                        }
                    }

    /**
     * Acl Action Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_resource_menu_acl_method_code = null {
                        get => $this->sm_resource_menu_acl_method_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_menu_acl_method_code', $value);
                            $this->sm_resource_menu_acl_method_code = $value;
                        }
                    }

    /**
     * Acl Action #
     *
     *
     *
     * {domain{action_id}}
     *
     * @var int|null Domain: action_id Type: smallint
     */
    public int|null $sm_resource_menu_acl_action_id = 0 {
                        get => $this->sm_resource_menu_acl_action_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_menu_acl_action_id', $value);
                            $this->sm_resource_menu_acl_action_id = $value;
                        }
                    }

    /**
     * URL
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_resource_menu_url = null {
                        get => $this->sm_resource_menu_url;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_menu_url', $value);
                            $this->sm_resource_menu_url = $value;
                        }
                    }

    /**
     * Options Generator
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_resource_menu_options_generator = null {
                        get => $this->sm_resource_menu_options_generator;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_menu_options_generator', $value);
                            $this->sm_resource_menu_options_generator = $value;
                        }
                    }

    /**
     * Name Generator
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_resource_menu_name_generator = null {
                        get => $this->sm_resource_menu_name_generator;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_menu_name_generator', $value);
                            $this->sm_resource_menu_name_generator = $value;
                        }
                    }

    /**
     * Child Ordered
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_resource_menu_child_ordered = 0 {
                        get => $this->sm_resource_menu_child_ordered;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_menu_child_ordered', $value);
                            $this->sm_resource_menu_child_ordered = $value;
                        }
                    }

    /**
     * Order
     *
     *
     *
     *
     *
     * @var int|null Type: integer
     */
    public int|null $sm_resource_menu_order = 0 {
                        get => $this->sm_resource_menu_order;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_menu_order', $value);
                            $this->sm_resource_menu_order = $value;
                        }
                    }

    /**
     * Separator
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_resource_menu_separator = 0 {
                        get => $this->sm_resource_menu_separator;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_menu_separator', $value);
                            $this->sm_resource_menu_separator = $value;
                        }
                    }

    /**
     * Class
     *
     *
     *
     *
     *
     * @var string|null Type: text
     */
    public string|null $sm_resource_menu_class = null {
                        get => $this->sm_resource_menu_class;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_menu_class', $value);
                            $this->sm_resource_menu_class = $value;
                        }
                    }

    /**
     * Template Name
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_template_name = 'default' {
                        get => $this->sm_resource_template_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_template_name', $value);
                            $this->sm_resource_template_name = $value;
                        }
                    }

    /**
     * Badge
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_resource_badge = null {
                        get => $this->sm_resource_badge;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_badge', $value);
                            $this->sm_resource_badge = $value;
                        }
                    }

    /**
     * Requires Tenants
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_resource_requires_tenants = 0 {
                        get => $this->sm_resource_requires_tenants;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_requires_tenants', $value);
                            $this->sm_resource_requires_tenants = $value;
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
    public int|null $sm_resource_inactive = 0 {
                        get => $this->sm_resource_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_resource_inactive', $value);
                            $this->sm_resource_inactive = $value;
                        }
                    }
}
