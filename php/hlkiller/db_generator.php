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
			$a = substr($buf, $a + 1,$b - $a);
			\hlkiller_core::$db->query($a);
			$a = $b;
		}

		exit ($i.' tables loaded');
	}

	public function generate_fish () {}
	public function clear_db () {}
}