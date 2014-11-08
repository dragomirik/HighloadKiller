<?php
/**
 * Created by PhpStorm.
 * User: Денис
 * Date: 08.11.14
 * Time: 23:47
 */

abstract class testing_config {
	public static $gen_fish = array(
		'startPostsEach'        =>  5,
		'endPostsEach'          =>  15,
		'startCategoriesEach'   =>  1,
		'endCategoriesEach'     =>  5,
		'startCommentsEach'     =>  1,
		'endCommentsEach'       =>  4,
		'startLikesEach'        =>  10,
		'endLikesEach'          =>  20,

		'partCount'             =>  5,
		'finalUsersCount'       =>  10,
		'categoriesCount'       =>  20
	);

	public static $select = array(
	//max select attacks
		'current_user_id'       =>  1,
				// user id
		'count'                 =>  20,
				// query limits count
		'times'                 =>  100
				// request times
	);
} 