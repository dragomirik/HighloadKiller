<?php
class hlkiller_core {
	public static $db;

	public static function connect () {
		self::$db = new mysqli(
			\config::$db_server,
			\config::$db_username,
			\config::$db_username,
			\config::$db_name
		);
	}

	public static function disconnect () {
		self::$db->close ();
	}

	public static function get_view (array $data = array ()) {
		return '0';
	}
}