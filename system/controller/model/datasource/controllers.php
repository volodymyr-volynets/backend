<?php

class numbers_backend_system_controller_model_datasource_controllers extends object_datasource {
	public $pk = ['sm_controller_id'];
	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;
	public function query($options = []) {
		$model = new numbers_backend_system_controller_model_controllers();
		$db = new db($model->db_link);
		$string_agg = $db->sql_helper('string_agg', ['expression' => "concat_ws(sm_cntrmap_action_code, ',', sm_cntrmap_action_id)", 'delimiter' => ';']);
		return <<<TTT
			SELECT
				a.sm_controller_id,
				a.sm_controller_code,
				a.sm_controller_name,
				a.sm_controller_icon,
				a.sm_controller_acl_public,
				a.sm_controller_acl_authorized,
				a.sm_controller_acl_permission, 
				a.sm_controller_inactive,
				g1.sm_cntrgrp_name g1_name,
				g2.sm_cntrgrp_name g2_name,
				g3.sm_cntrgrp_name g3_name,
				m.actions
			FROM [table[numbers_backend_system_controller_model_controllers]] a
			LEFT JOIN [table[numbers_backend_system_controller_model_groups]] g1 ON a.sm_controller_group1_id = g1.sm_cntrgrp_id
			LEFT JOIN [table[numbers_backend_system_controller_model_groups]] g2 ON a.sm_controller_group2_id = g2.sm_cntrgrp_id
			LEFT JOIN [table[numbers_backend_system_controller_model_groups]] g3 ON a.sm_controller_group3_id = g3.sm_cntrgrp_id
			LEFT JOIN (
				SELECT
					sm_cntrmap_controller_id,
					{$string_agg} actions
				FROM [table[numbers_backend_system_controller_model_map]]
				WHERE 1=1
					AND sm_cntrmap_inactive = 0
				GROUP BY sm_cntrmap_controller_id
			) m ON m.sm_cntrmap_controller_id = a.sm_controller_id
			WHERE 1=1
				AND a.sm_controller_inactive = 0
				AND coalesce(g1.sm_cntrgrp_inactive, 0) = 0
				AND coalesce(g2.sm_cntrgrp_inactive, 0) = 0
				AND coalesce(g3.sm_cntrgrp_inactive, 0) = 0
TTT;
	}
	/*
	public function process($data, $options = []) {

	}
	*/
}