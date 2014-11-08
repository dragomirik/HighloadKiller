<?php
class hlkiller_core {
	public static $db;

	public static function connect () {
		self::$db = new mysqli(
			\config::DB_SERVER,
			\config::DB_USERNAME,
			\config::DB_USERPASS,
			\config::DB_NAME
		);
	}

	public static function disconnect () {
		self::$db->close ();
	}
}