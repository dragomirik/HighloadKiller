<?php
class machine_gun {
	public function init () {}

	public function make_select_attack ($count = 10) {
		$query_text = '

			SELECT
				`users_id`,
				`user_name`,
				`users_password`
			FROM
				`users`
			WHERE
				1

		';
		\hlkiller_core::db()->query ($query_text);
	}
	public function make_mixed_attack () {}
	public function high_load_emulation () {}
	public function get_statistics () {}
}