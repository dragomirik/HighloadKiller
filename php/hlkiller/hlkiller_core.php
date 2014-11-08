<?php
abstract class hlkiller_core {
	private static $db;

	/**
	 * db connect by mysqli
	 */
	public static function connect () {
		//connect to db
		self::$db = new mysqli(
			\config::$db_server,
			\config::$db_username,
			\config::$db_userpass,
			\config::$db_name
		);

		// check connect
		if (mysqli_connect_errno())
			die ('MySQLi cann\'t connect with DataBase');
	}

	/**
	 * db disconnect
	 */
	public static function disconnect () {
		self::$db->close ();
	}

	public static function db () {
		return self::$db;
	}
}