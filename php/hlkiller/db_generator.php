<?php
class db_generator {
	public function init () {

	}

	/**
	 * db dump file import
	 *
	 * @param $file_path
	 */
	public function add_structure_dump ($file_path) {
		try {
			$handle = fopen ($file_path, 'r');
			if (!$handle) {
				throw new \Exceptions\File ("Could not open the file!");
			}
		}
		catch (\Exceptions\File $e) {
			die ("Error (File: ".$e->getFile().", line ".
				$e->getLine()."): ".$e->getMessage());
		}
		$buf = fread($handle, filesize($file_path));
		fclose ($handle);

		$a = 0;
		$i = 0;
		while ($b = strpos($buf, ';', $a + 1)) {
			$i++;
			try {
				$a = substr($buf, $a + 1,$b - $a);
				$result = \hlkiller_core::db ()->query($a);
				if ($result === true) {
					throw new \Exceptions\MySQLQuery ('Mysqli died.');
				}
			}
			catch (\Exceptions\MySQLQuery $e) {
				die ($e->getMessage());
			}
			$a = $b;
		}

		exit ($i.' tables loaded');
	}

	public function generate_fish ($php_multiplier = 10, $mysql_multiplier = 100) {
		$mysql_multiplier = (int) $mysql_multiplier;
		for ($i = 0; $i < $php_multiplier; $i ++) {
			try {
				$result = \hlkiller_core::db ()->multi_query (
					"DECLARE i INT DEFAULT $mysql_multiplier;
						WHILE i>0 DO
								INSERT ...;
							SET i=i-1;
						END WHILE;"
				);
				if ($result === true) {
					throw new \Exceptions\MySQLQuery (
						'Mysqli died. '.(($i + 1) / $php_multiplier).'% completed'
					);
				}
			}
			catch (\Exceptions\MySQLQuery $e) {
				die ($e->getMessage());
			}
			sleep (1);
		}

	}
	public function clear_db () {}
}