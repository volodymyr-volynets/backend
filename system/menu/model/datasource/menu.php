<?php

class numbers_backend_system_menu_model_datasource_menu extends object_datasource {
	public $pk;
	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;
	public function query($options = []) {
		$type = $options['where']['type'] ?? 1;
		return <<<TTT
			SELECT
				a.sm_menuitm_code,
				a.sm_menuitm_name,
				a.sm_menuitm_icon,
				a.sm_menuitm_type_id,
				a.sm_menuitm_order, 
				a.sm_menuitm_acl_controller_id,
				a.sm_menuitm_acl_action_id,
				a.sm_menuitm_url,
				-- group 1
				a.sm_menuitm_group1_code g1_code, 
				g1.sm_menugrp_name g1_name,
				g1.sm_menugrp_order g1_order,
				-- group 2
				a.sm_menuitm_group2_code g2_code,
				g2.sm_menugrp_name g2_name,
				g2.sm_menugrp_order g2_order,
				-- group 3
				a.sm_menuitm_group3_code g3_code,
				g3.sm_menugrp_name g3_name,
				g3.sm_menugrp_order g3_order
			FROM [table[numbers_backend_system_menu_model_items]] a
			LEFT JOIN [table[numbers_backend_system_menu_model_groups]] g1 ON a.sm_menuitm_group1_code = g1.sm_menugrp_code
			LEFT JOIN [table[numbers_backend_system_menu_model_groups]] g2 ON a.sm_menuitm_group2_code = g2.sm_menugrp_code
			LEFT JOIN [table[numbers_backend_system_menu_model_groups]] g3 ON a.sm_menuitm_group3_code = g3.sm_menugrp_code
			WHERE 1=1
				AND a.sm_menuitm_type_id = $type
				AND a.sm_menuitm_inactive = 0
				AND (CASE WHEN g1.sm_menugrp_inactive IS NULL THEN true ELSE g1.sm_menugrp_inactive = 0 END)
				AND (CASE WHEN g2.sm_menugrp_inactive IS NULL THEN true ELSE g2.sm_menugrp_inactive = 0 END)
				AND (CASE WHEN g3.sm_menugrp_inactive IS NULL THEN true ELSE g3.sm_menugrp_inactive = 0 END)
			ORDER BY a.sm_menuitm_order
TTT;
	}
	public function process($data, $options = []) {
		$temp = [];
		// loop though data
		foreach ($data as $k => $v) {
			// determine acl
			if (!empty($v['sm_menuitm_acl_controller_id']) && !helper_acl::can_see_this_controller($v['sm_menuitm_acl_controller_id'])) {
				continue;
			}
			// loop though groups and add them to menu
			$key = [];
			for ($i = 1; $i <= 3; $i++) {
				if (!empty($v['g' . $i . '_code'])) {
					$key[] = $v['g' . $i . '_code'];
					// we need to set all groups
					$temp2 = array_key_get($temp, $key);
					if (is_null($temp2)) {
						array_key_set($temp, $key, [
							'name' => $v['g' . $i . '_name'],
							'order' => $v['g' . $i . '_order'],
							'options' => []
						]);
					}
					$key[] = 'options';
				}
			}
			// finali we need to add menu item to the array
			$key[] = $v['sm_menuitm_code'];
			array_key_set($temp, $key, [
				'name' => $v['sm_menuitm_name'],
				'icon' => $v['sm_menuitm_icon'],
				'url' => $v['sm_menuitm_url'],
				'order' => $v['sm_menuitm_order']
			]);
		}
		// sorting
		foreach ($temp as $k => $v) {
			if (!empty($v['options'])) {
				foreach ($v['options'] as $k2 => $v2) {
					if (!empty($v2['options'])) {
						foreach ($v2['options'] as $k3 => $v3) {
							if (!empty($v3['options'])) {
								array_key_sort($temp[$k]['options'][$k2]['options'][$k3]['options'], ['order' => SORT_ASC], ['order' => SORT_NUMERIC]);
							}
						}
						array_key_sort($temp[$k]['options'][$k2]['options'], ['order' => SORT_ASC], ['order' => SORT_NUMERIC]);
					}
				}
				array_key_sort($temp[$k]['options'], ['order' => SORT_ASC], ['order' => SORT_NUMERIC]);
			}
		}
		// sort root
		array_key_sort($temp, ['order' => SORT_ASC], ['order' => SORT_NUMERIC]);
		return $temp;
	}
}