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

		try {
			$result = \hlkiller_core::db ()->multi_query($buf);
			if ($result === false) {
				throw new \Exceptions\MySQLQuery ('Mysqli died.');
			}
		}
		catch (\Exceptions\MySQLQuery $e) {
			die ($e->getMessage());
		}

		exit ('Tables loaded');
	}


	public function generate_fish ($config) {
                $tables=$config['tables'];
                
                $startPostsEach = $config['startPostsEach'];
                $endPostsEach = $config['endPostsEach'];
                $startCategoriesEach = $config['startCategoriesEach'];
                $endCategoriesEach = $config['endCategoriesEach'];
                $startCommentsEach = $config['startCommentsEach'];
                $endCommentsEach = $config['endCommentsEach'];
                $startLikesEach = $config['startLikesEach'];
                $endLikesEach = $config['endLikesEach'];
                
                        
                
                $user_insert_values_arr = array();
                $following_insert_values_arr = array();
                $posts_insert_values_arr = array();
                $rel_categories_posts_insert_arr = array();
                $add_comments_rel_count = array();
                $rel_comments_posts_insert_arr = array();
                $rel_like_posts_insert_arr = array();
                
                for ($user_part_first_id=1; $user_part_first_id<=$config['final_users_count']; $user_part_first_id+=$config['part_count']) {
	
                    $user_part_last = $user_part_first_id+$config['part_count'];
                    // Add Users
                    for($user_id=$user_part_first_id; $user_id<$user_part_last; $user_id++) {
                        $user_insert_values_arr[] = \annex::set_fields($tables['users'],$user_id);
                    }
                    //Add following
                    for($user_id=$user_part_first_id; $user_id<$user_part_last; $user_id++) {
                            $following_insert_values_arr[] = \annex::set_fields($tables['rel_users_following'],$user_id,array_rand($user_insert_values_arr));
                    }

                    // Add user posts
                    for($user_id=$user_part_first_id; $user_id<$user_part_last; $user_id++) {

                        $add_post_count = rand($startPostsEach, $endPostsEach);

                        for($i=$startPostsEach;$i<=$add_post_count;$i++) {
                            // Add Post
                            $posts_insert_values_arr[] = \annex::set_fields($tables['posts'],$user_id,array_rand($user_insert_values_arr));
                                    //Array(‘user_id’ => user_id, `post_id`=>post_id... ) 
                            $post_id++;

                            // Add Categories Relations
                            $add_categories_rel_count = rand($startCategoriesEach, $endCategoriesEach);
                            for($rel_count=$startCategoriesEach; $rel_count<=$endCategoriesEach; $rel_count++) {
                                $rel_categories_posts_insert_arr[] = \annex::set_fields($tables['rel_posts_categories'],$post_id,array_rand($user_insert_values_arr));
                                        //Array(post_id, rand_from_array(category_id))
                            }
                            
                            // Add Post Comments
                            $add_comments_rel_count = rand($startCommentsEach, $endCommentsEach);
                            for($rel_count=$startCategoriesEach; $rel_count<=$endCategoriesEach; $rel_count++) {
                                $rel_comments_posts_insert_arr[] = \annex::set_fields($tables['rel_users_posts_comments'],$post_id,array_rand($user_insert_values_arr));
                                        //Array(post_id, user_insert_values_arr[rand_this()], comment_text)
                            }
                            
                            // Add Post Likes
                            $add_like_rel_count = rand($startLikesEach, $endLikesEach);
                             for($rel_count=$startCategoriesEach; $rel_count<=$endCategoriesEach; $rel_count++) {
                                 $rel_like_posts_insert_arr[] = \annex::set_fields($tables['rel_users_posts_comments'],$post_id,array_rand($user_insert_values_arr));
                                 //Array(post_id, user_insert_values_arr[rand_this()], like_time)
                             }
                        }
                    }
                }
                
            /*
            
		for ($i = 0; $i < $php_multiplier; $i ++) {
			try {
				
				#--test
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
             * 
             */

	}
	public function clear_db () {}
}