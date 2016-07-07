<?php

interface numbers_backend_documents_interface_base {
	public static function save($options);
	public static function delete($document_id);
}