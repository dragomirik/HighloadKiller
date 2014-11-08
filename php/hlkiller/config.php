<?php
	class config {
		const VIEW_PATH = 'hlkiller/view';

<<<<<<< HEAD
		public static $db_server     = '127.0.0.1';
		public static $db_username   = 'root';
		public static $db_userpass   = '';
		public static $db_name       = 'killer_test';

		/**
		 * | int[, primary = false]
		 * | time
		 * | string, length
		 * | text
		 *
		 * @var array
		 */
		public static $tables  = array(
			'users' => array(
				'users_id' => array(
					'type'      =>  'int',
					'primary'   =>  true
				),
				'user_name' => array(
					'length'    =>  100,
					'type'      =>  'string'
				),
				'users_password' => array(
					'length'    =>  32,
					'type'      =>  'string'
				)
			),
			'categories' => array(
				'categories_id' => array(
					'type'      =>  'int',
					'primary'   =>  true
				),
				'categories_name' => array(
					'length'    =>  100,
					'type'      =>  'string'
				)
			),
			'posts' => array (
				'posts_id' => array (
					'type'      =>  'int',
					'primary'   =>  true
				),
				'users_id' => array (
					'type'      =>  'int'
				),
				'posts_created_time' => array (
					'type'      =>  'time'
				),
				'posts_title' => array (
					'length'    =>  256,
					'type'      =>  'string'
				),
				'posts_html' => array (
					'type'      =>  'text'
				)
			),
			'users_posts_like' => array (
				'rel_users_posts_like_id' => array (
					'type'      =>  'int',
					'primary'   =>  true
				),
				'users_id' => array (
					'type'      =>  'int'
				),
				'posts_id' => array (
					'type'      =>  'int'
				),
				'rel_users_posts_like_time' => array (
					'type'      =>  'time'
				)
			),
			'rel_users_posts_comments' => array (
				'rel_users_posts_comments_id' => array (
					'type'      =>  'int',
					'primary'   =>  true
				),
				'users_id' => array (
					'type'      =>  'int'
				),
				'posts_id' => array (
					'type'      =>  'int'
				),
				'comments_text' => array (
					'type'      =>  'text'
				),
				'rel_users_posts_comments_time' => array (
					'type'      =>  'time'
				)
			),
			'rel_users_following' => array (
				'following_id' => array (
					'type'      =>  'int',
					'primary'   =>  true
				),
				'users_follower_id' => array (
					'type'      =>  'int'
				),
				'users_supplier_id' => array (
					'type'      =>  'int'
				),
				'following_time' => array (
					'type'      =>  'time'
				)
			),
			'rel_posts_categories' => array (
				'posts_categories_id' => array (
					'type'      =>  'int',
					'primary'   =>  true
				),
				'posts_id' => array (
					'type'      =>  'int'
				),
				'categories_id' => array (
					'type'      =>  'int'
				)
			)
		);
	}
=======
	public static $db_server     = '127.0.0.1';
	public static $db_username   = 'root';
	public static $db_userpass   = '';
	public static $db_name       = 'killer_test';
        public static $tables  = array(
            'users'=>array(
                'users_id'=> array(
                    'type'=>'int',
                    'primary'=>true
                    ),
                'user_name'=>array(
                    'length'=>100,
                    'type'=>'string'
                ),
                'users_password'=>array(
                    'length'=>32,
                    'type'=>'string' 
                )
            ),
            'categories'=>array(
                'categories_id'=>array(
                    'type'=>'int',
                    'primary'=>true
                    
                    ),
                'categories_name'=>array(
                    'length'=>100,
                    'type'=>'string'
                )
            )
            
        );
}
>>>>>>> origin/master
