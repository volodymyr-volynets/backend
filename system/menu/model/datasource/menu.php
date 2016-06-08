<?php

class numbers_backend_system_menu_model_datasource_menu extends object_datasource {
	public $pk;
	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;
	public function query($options = []) {
		$type = '';
		if (!empty($options['where']['type'])) {
			if (is_array($options['where']['type'])) {
				$type = ' AND a.sm_menuitm_type_id IN (' . implode(',', $options['where']['type']) . ')';
			} else {
				$type = ' AND a.sm_menuitm_type_id = ' . $options['where']['type'];
			}
		}
		$db = factory::model('numbers_backend_system_menu_model_groups')->db_object();
		$groups_filtering = '';
		if (!empty($options['where']['group1_code'])) {
			$groups_filtering.= " AND a.sm_menuitm_group1_code = '" . $db->escape($options['where']['group1_code']) . "'";
		}
		if (!empty($options['where']['group2_code'])) {
			$groups_filtering.= " AND a.sm_menuitm_group2_code = '" . $db->escape($options['where']['group2_code']) . "'";
		}
		if (!empty($options['where']['group3_code'])) {
			$groups_filtering.= " AND a.sm_menuitm_group3_code = '" . $db->escape($options['where']['group3_code']) . "'";
		}
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
				g1.sm_menugrp_icon g1_icon,
				g1.sm_menugrp_url g1_url,
				-- group 2
				a.sm_menuitm_group2_code g2_code,
				g2.sm_menugrp_name g2_name,
				g2.sm_menugrp_order g2_order,
				g2.sm_menugrp_icon g2_icon,
				g2.sm_menugrp_url g2_url,
				-- group 3
				a.sm_menuitm_group3_code g3_code,
				g3.sm_menugrp_name g3_name,
				g3.sm_menugrp_order g3_order,
				g3.sm_menugrp_icon g3_icon,
				g3.sm_menugrp_url g3_url
			FROM [table[numbers_backend_system_menu_model_items]] a
			LEFT JOIN [table[numbers_backend_system_menu_model_groups]] g1 ON a.sm_menuitm_group1_code = g1.sm_menugrp_code
			LEFT JOIN [table[numbers_backend_system_menu_model_groups]] g2 ON a.sm_menuitm_group2_code = g2.sm_menugrp_code
			LEFT JOIN [table[numbers_backend_system_menu_model_groups]] g3 ON a.sm_menuitm_group3_code = g3.sm_menugrp_code
			WHERE 1=1
				{$type}
				{$groups_filtering}
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
			if (!empty($v['sm_menuitm_acl_controller_id']) && !helper_acl::can_see_this_controller($v['sm_menuitm_acl_controller_id'], $v['sm_menuitm_acl_action_id'])) {
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
						// if we do not have url we assume visitor wants to see extended menu
						if (empty($v['g' . $i . '_url'])) {
							$params = [];
							for ($j = 1; $j <= $i; $j++) {
								$params['group' . $j . '_code'] = $v['g' . $j . '_code'];
							}
							$v['g' . $i . '_url'] = '/numbers/backend/system/menu/controller/menu?' . http_build_query2($params);
						}
						array_key_set($temp, $key, [
							'code' => $v['g' . $i . '_code'],
							'name' => $v['g' . $i . '_name'],
							'icon' => $v['g' . $i . '_icon'],
							'order' => $v['g' . $i . '_order'],
							'url' => $v['g' . $i . '_url'],
							'options' => []
						]);
					}
					$key[] = 'options';
				}
			}
			// some replaces
			$name_extension = null;
			if ($v['sm_menuitm_code'] == 'entites.authorization.__entity_name') {
				$name_extension = '<b>' . session::get(['numbers', 'entity', 'em_entity_name']) . '</b>';
			}
			// finally we need to add menu item to the array
			$key[] = $v['sm_menuitm_code'];
			array_key_set($temp, $key, [
				'code' => $v['sm_menuitm_code'],
				'name' => $v['sm_menuitm_name'],
				'name_extension' => $name_extension,
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