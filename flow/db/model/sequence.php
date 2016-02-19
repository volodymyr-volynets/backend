<?php

class numbers_backend_flow_db_model_sequence extends object_sequence {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.flow.db.default_db_link';
	public $name = 'sm.sm_flow_id_seq';
	public $type = "simple";
}