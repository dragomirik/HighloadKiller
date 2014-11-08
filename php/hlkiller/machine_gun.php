<?php
class machine_gun {
	public function init () {}

	public function make_select_attack ($count = 10) {
		/*$fields = '
			`users_id`,
			`users_username`,
			`users_password`
		';
		$tablename = 'users';*/

		$fields = '
			`users_id`,
			`users_username`,
			`users_password`
		';
		$tablename = 'posts';

		$query_text = "

			SELECT
				$fields
			FROM
				`$tablename`
			WHERE
				1

		";
		\hlkiller_core::db()->query ($query_text);
	}
	public function make_mixed_attack () {}
	public function high_load_emulation () {}
	public function get_statistics () {}
}