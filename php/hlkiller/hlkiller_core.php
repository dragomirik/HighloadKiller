<?php
class hlkiller_core {
	public static $db;

	/**
	 * db connect by mysqli
	 */
	public static function connect () {
		self::$db = new mysqli(
			\config::$db_server,
			\config::$db_username,
			\config::$db_userpass,
			\config::$db_name
		);
	}

	/**
	 * db disconnect
	 */
	public static function disconnect () {
		self::$db->close ();
	}

	/**
	 * return default view template
	 *
	 * @return string
	 */
	public static function get_index_view () {
		$files = array(
			'footer' => 'footer',
			'index'  => 'index',
			'template'  => 'main_template'
		);
		foreach ($files as $var_name => $filename) {
			ob_start();
			$full_filename = \config::VIEW_PATH.DIRSEP.$filename.EXT;
			if (is_file($full_filename)) {
				include $full_filename;
				$$var_name = ob_get_clean ();
			} else {
				ob_end_clean();
			}
		}
		if (isset ($template)) {
			return $template;
		} else
			return 'Error: view not found';
	}
}