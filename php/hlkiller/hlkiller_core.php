<?php
class hlkiller_core {
	public static $db;

	public static function connect () {
		self::$db = new mysqli('localhost', 'root', '', 'awm_023_hk');
	}

	public static function disconnect () {
		self::$db->close ();
	}
}