<?php

class numbers_backend_system_menu_model_datasource_menu extends object_datasource {
	public $db_link;
	public $db_link_flag = 'flag.numbers.data.entities.default_db_link';
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
		if (!empty($options['where']['group4_code'])) {
			$groups_filtering.= " AND a.sm_menuitm_group4_code = '" . $db->escape($options['where']['group4_code']) . "'";
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
				a.sm_menuitm_options_generator,
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
				g3.sm_menugrp_url g3_url,
				-- group 4
				a.sm_menuitm_group4_code g4_code,
				g4.sm_menugrp_name g4_name,
				g4.sm_menugrp_order g4_order,
				g4.sm_menugrp_icon g4_icon,
				g4.sm_menugrp_url g4_url
			FROM sm_menu_items a
			LEFT JOIN sm_menu_groups g1 ON a.sm_menuitm_group1_code = g1.sm_menugrp_code
			LEFT JOIN sm_menu_groups g2 ON a.sm_menuitm_group2_code = g2.sm_menugrp_code
			LEFT JOIN sm_menu_groups g3 ON a.sm_menuitm_group3_code = g3.sm_menugrp_code
			LEFT JOIN sm_menu_groups g4 ON a.sm_menuitm_group4_code = g4.sm_menugrp_code
			WHERE 1=1
				{$type}
				{$groups_filtering}
				AND a.sm_menuitm_inactive = 0
				AND coalesce(g1.sm_menugrp_inactive, 0) = 0
				AND coalesce(g2.sm_menugrp_inactive, 0) = 0
				AND coalesce(g3.sm_menugrp_inactive, 0) = 0
				AND coalesce(g4.sm_menugrp_inactive, 0) = 0
			ORDER BY a.sm_menuitm_order
TTT;
	}
	public function process($data, $options = []) {
		$temp = [];
		// we need to precess items that are controller and suboptions at the same time
		$subgroups = [];
		foreach ($data as $k => $v) {
			// determine acl
			if (!empty($v['sm_menuitm_acl_controller_id']) && !helper_acl::can_see_this_controller($v['sm_menuitm_acl_controller_id'], $v['sm_menuitm_acl_action_id'])) {
				unset($data[$k]);
				continue;
			}
			// go though each group
			for ($i = 1; $i <= 4; $i++) {
				if (!empty($v["g{$i}_code"])) {
					$subgroups[$v["g{$i}_code"]] = true;
				}
			}
		}
		$subgroup_items = [];
		foreach ($data as $k => $v) {
			if (isset($subgroups[$v['sm_menuitm_code']])) {
				$subgroup_items[$v['sm_menuitm_code']]= $v;
				unset($data[$k]);
			}
		}
		// loop though data
		foreach ($data as $k => $v) {
			// loop though groups and add them to menu
			$key = [];
			for ($i = 1; $i <= 4; $i++) {
				if (!empty($v['g' . $i . '_code'])) {
					$key[] = $v['g' . $i . '_code'];
					// we need to set all groups
					$temp2 = array_key_get($temp, $key);
					if (is_null($temp2)) {
						// if we have a controller that acts as submenu
						if (!empty($subgroup_items[$v['g' . $i . '_code']])) {
							$v9 = $subgroup_items[$v['g' . $i . '_code']];
							array_key_set($temp, $key, [
								'code' => $v9['sm_menuitm_code'],
								'name' => $v9['sm_menuitm_name'],
								'name_extension' => null,
								'icon' => $v9['sm_menuitm_icon'],
								'url' => $v9['sm_menuitm_url'],
								'order' => $v9['sm_menuitm_order'],
								'options' => [] // a must
							]);
						} else {
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
				'order' => $v['sm_menuitm_order'],
				'options' => [] // a must
			]);
			// options generator
			if (!empty($v['sm_menuitm_options_generator'])) {
				$temp3 = explode('::', $v['sm_menuitm_options_generator']);
				$temp_data = factory::model($temp3[0])->{$temp3[1]}();
				$temp_key = $key;
				$temp_key[] = 'options';
				foreach ($temp_data as $k2 => $v2) {
					$temp_key2 = $temp_key;
					$temp_key2[] = $k2;
					array_key_set($temp, $temp_key2, $v2);
				}
			}
		}
		// sorting
		foreach ($temp as $k => $v) {
			if (!empty($v['options'])) {
				foreach ($v['options'] as $k2 => $v2) {
					if (!empty($v2['options'])) {
						foreach ($v2['options'] as $k3 => $v3) {
							if (!empty($v3['options'])) {
								foreach ($v3['options'] as $k4 => $v4) {
									if (!empty($v4['options'])) {
										array_key_sort($temp[$k]['options'][$k2]['options'][$k3]['options'][$k4]['options'], ['order' => SORT_ASC], ['order' => SORT_NUMERIC]);
									}
								}
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