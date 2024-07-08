<?php

namespace Numbers\Backend\System\Modules\Model;
class ResourcesAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\System\Modules\Model\Resources::class;

	/**
	 * Constructing object
	 *
	 * @param array $options
	 *		skip_db_object
	 *		skip_table_object
	 */
	public function __construct($options = []) {
		if (empty($options['skip_table_object'])) {
			$this->object_table_object = new $this->object_table_class($options);
		}
	}
	/**
	 * Resource #
	 *
	 *
	 *
	 * {domain{resource_id_sequence}}
	 *
	 * @var int Domain: resource_id_sequence Type: serial
	 */
	public ?int $sm_resource_id = null;

	/**
	 * Code
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_resource_code = null;

	/**
	 * Type
	 *
	 *
	 * {options_model{\Numbers\Backend\System\Modules\Model\Resource\Types}}
	 * {domain{type_id}}
	 *
	 * @var int Domain: type_id Type: smallint
	 */
	public ?int $sm_resource_type = NULL;

	/**
	 * Classification
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_resource_classification = null;

	/**
	 * Name
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_resource_name = null;

	/**
	 * Description
	 *
	 *
	 *
	 * {domain{description}}
	 *
	 * @var string Domain: description Type: varchar
	 */
	public ?string $sm_resource_description = null;

	/**
	 * Icon
	 *
	 *
	 *
	 * {domain{icon}}
	 *
	 * @var string Domain: icon Type: varchar
	 */
	public ?string $sm_resource_icon = null;

	/**
	 * Module Code
	 *
	 *
	 *
	 * {domain{module_code}}
	 *
	 * @var string Domain: module_code Type: char
	 */
	public ?string $sm_resource_module_code = null;

	/**
	 * Group 1
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_resource_group1_name = null;

	/**
	 * Group 2
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_resource_group2_name = null;

	/**
	 * Group 3
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_resource_group3_name = null;

	/**
	 * Group 4
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_resource_group4_name = null;

	/**
	 * Group 5
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_resource_group5_name = null;

	/**
	 * Group 6
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_resource_group6_name = null;

	/**
	 * Group 7
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_resource_group7_name = null;

	/**
	 * Group 8
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_resource_group8_name = null;

	/**
	 * Group 9
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_resource_group9_name = null;

	/**
	 * Version Code
	 *
	 *
	 *
	 * {domain{version_code}}
	 *
	 * @var string Domain: version_code Type: varchar
	 */
	public ?string $sm_resource_version_code = null;

	/**
	 * API Method Counter
	 *
	 *
	 *
	 * {domain{counter}}
	 *
	 * @var int Domain: counter Type: integer
	 */
	public ?int $sm_resource_api_method_counter = 0;

	/**
	 * Acl Public
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_resource_acl_public = 0;

	/**
	 * Acl Authorized
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_resource_acl_authorized = 0;

	/**
	 * Acl Permission
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_resource_acl_permission = 0;

	/**
	 * Acl Resource #
	 *
	 *
	 *
	 * {domain{resource_id}}
	 *
	 * @var int Domain: resource_id Type: integer
	 */
	public ?int $sm_resource_menu_acl_resource_id = 0;

	/**
	 * Acl Action Code
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_resource_menu_acl_method_code = null;

	/**
	 * Acl Action #
	 *
	 *
	 *
	 * {domain{action_id}}
	 *
	 * @var int Domain: action_id Type: smallint
	 */
	public ?int $sm_resource_menu_acl_action_id = 0;

	/**
	 * URL
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: text
	 */
	public ?string $sm_resource_menu_url = null;

	/**
	 * Options Generator
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: text
	 */
	public ?string $sm_resource_menu_options_generator = null;

	/**
	 * Name Generator
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: text
	 */
	public ?string $sm_resource_menu_name_generator = null;

	/**
	 * Child Ordered
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_resource_menu_child_ordered = 0;

	/**
	 * Order
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: integer
	 */
	public ?int $sm_resource_menu_order = 0;

	/**
	 * Separator
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_resource_menu_separator = 0;

	/**
	 * Class
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: text
	 */
	public ?string $sm_resource_menu_class = null;

	/**
	 * Inactive
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_resource_inactive = 0;
}