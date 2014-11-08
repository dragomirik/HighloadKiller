<?php
class machine_gun {
	public function init () {}

	public function make_select_attack () {
		$current_user_id = (int) \testing_config::$select ['current_user_id'];
		$count           = (int) \testing_config::$select ['count'];

		$fields = '
			`posts`.`posts_id`,
			`posts_created_time`,
			`posts_title`,
			`posts_html`,

			`users_username`,
			`users_password`,

			`cats_names_string`,
			`cats_id_string`,

			`last_comments_users_id`,
			`last_comments_username`,

			`likes_count`
		';

		$JOINs = "

			LEFT JOIN `users` ON `users`.`users_id`=`posts`.`users_id`
			LEFT JOIN (
					SELECT
						`rel_posts_categories`.`posts_id`,
						GROUP_CONCAT(`categories_name` ORDER BY `categories_name` ASC SEPARATOR '[;]') as `cats_names_string`,
						GROUP_CONCAT(`rel_posts_categories`.`categories_id`   ORDER BY `categories_name` ASC SEPARATOR '[;]') as `cats_id_string`
					FROM
						`categories`
					LEFT JOIN
						`rel_posts_categories` ON
						`categories`.`categories_id`=`rel_posts_categories`.`categories_id`
					GROUP BY
						`rel_posts_categories`.`posts_id`
				) `posts_cats_strings` ON `posts_cats_strings`.`posts_id`=`posts`.`posts_id`
			LEFT JOIN `rel_users_following` ON `rel_users_following`.`users_supplier_id`=`users`.`users_id`
			LEFT JOIN (
					SELECT
						`rel_users_posts_comments`.`posts_id`,
						`users`.`users_id` as `last_comments_users_id`,
						`users`.`users_username` as `last_comments_username`,
						MAX(`rel_users_posts_comments_id`),
						`comments_text`
					FROM
						`rel_users_posts_comments`
					LEFT JOIN
						`users` ON
						`users`.`users_id`=`rel_users_posts_comments`.`users_id`
					GROUP BY
						`rel_users_posts_comments`.`posts_id`
				) `last_comments` ON `last_comments`.`posts_id`=`posts`.`posts_id`
			LEFT JOIN (
					SELECT
						`users_posts_like`.`posts_id`,
						COUNT(`rel_users_posts_like_id`) as `likes_count`
					FROM
						`users_posts_like`
					GROUP BY
						`users_posts_like`.`posts_id`
				) `likes` ON `likes`.`posts_id`=`posts`.`posts_id`

		";
		$tablename = 'posts';
		$conditions = "

			`rel_users_following`.`users_follower_id`='$current_user_id'

		";

		$query_text = "

			SELECT
				$fields
			FROM
				`$tablename`
			$JOINs
			WHERE
				$conditions
			LIMIT
				0, $count
		";
		//echo $query_text;
		return $query_text;
	}

	public function make_select_attack2 () {
		$current_user_id = (int) \testing_config::$select ['current_user_id'];
		$count           = (int) \testing_config::$select ['count'];

		$fields = '
			`posts`.`posts_id`,
			`posts_created_time`,
			`posts_title`,
			`posts_html`,

			`users_username`,
			`users_password`,

			`cats_names_string`,
			`cats_id_string`,

			`last_comments_users_id`,
			`last_comments_username`,

			`likes_count`
		';

		$JOINs = "

			LEFT JOIN `users` ON `users`.`users_id`=`posts`.`users_id`
			LEFT JOIN (
					SELECT
						`rel_posts_categories`.`posts_id`,
						GROUP_CONCAT(`categories_name` ORDER BY `categories_name` ASC SEPARATOR '[;]') as `cats_names_string`,
						GROUP_CONCAT(`rel_posts_categories`.`categories_id`   ORDER BY `categories_name` ASC SEPARATOR '[;]') as `cats_id_string`
					FROM
						`categories`
					LEFT JOIN
						`rel_posts_categories` ON
						`categories`.`categories_id`=`rel_posts_categories`.`categories_id`
					GROUP BY
						`rel_posts_categories`.`posts_id`
				) `posts_cats_strings` ON `posts_cats_strings`.`posts_id`=`posts`.`posts_id`
			LEFT JOIN `rel_users_following` ON `rel_users_following`.`users_supplier_id`=`users`.`users_id`
			LEFT JOIN (
					SELECT
						`rel_users_posts_comments`.`posts_id`,
						`users`.`users_id` as `last_comments_users_id`,
						`users`.`users_username` as `last_comments_username`,
						MAX(`rel_users_posts_comments_id`),
						`comments_text`
					FROM
						`rel_users_posts_comments`
					LEFT JOIN
						`users` ON
						`users`.`users_id`=`rel_users_posts_comments`.`users_id`
					GROUP BY
						`rel_users_posts_comments`.`posts_id`
				) `last_comments` ON `last_comments`.`posts_id`=`posts`.`posts_id`
			LEFT JOIN (
					SELECT
						`users_posts_like`.`posts_id`,
						COUNT(`rel_users_posts_like_id`) as `likes_count`
					FROM
						`users_posts_like`
					GROUP BY
						`users_posts_like`.`posts_id`
				) `likes` ON `likes`.`posts_id`=`posts`.`posts_id`

		";
		$tablename = 'posts';
		$conditions = "

			`rel_users_following`.`users_follower_id`='$current_user_id'

		";

		$query_text = "

			SELECT
				$fields
			FROM
				`$tablename`
			$JOINs
			WHERE
				$conditions
			LIMIT
				0, $count
		";
		//echo $query_text;
		return $query_text;
	}

	public function make_mixed_attack () {}
	public function high_load_emulation () {}
	public function get_statistics () {}

	public function run_select ($query_text) {
		$times = (int) \testing_config::$select ['times'];
		try {
			$db = \hlkiller_core::db ();
			$start_time = microtime (2);
			$result = $db->query ($query_text);
			if ($db->errno) {
				throw new \Exceptions\MySQLQuery ('Mysqli died. '.$db->errno.' : '.$db->error);
			}
			$result_array = array ();
			while ($tmp_array = $result->fetch_assoc ()) {
				$result_array [] = $tmp_array;
			}
			$end_time = microtime (2);
			echo $end_time - $start_time;
		}
		catch (\Exceptions\MySQLQuery $e) {
			die ($e->getMessage());
		}
		\annex::showArray($result_array);
	}
}