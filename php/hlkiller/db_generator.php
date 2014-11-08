<?php
class db_generator {

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
		$sql = fread($handle, filesize($file_path));
		fclose ($handle);

		$db = &\hlkiller_core::db ();
		try {
			if ($db->multi_query($sql)) {
				$i = 0;
				do {
					$i++;
				} while ($db->next_result());
			}
			if ($db->errno) {
				throw new \Exceptions\MySQLQuery ('Mysqli died.');
			}
		}
		catch (\Exceptions\MySQLQuery $e) {
			die ($e->getMessage());
		}

		exit ('Tables loaded');
	}


	public function generate_fish ($config) {
                $this->clear_db();
                //return;
                $tables=\config::getModel();
                
                $startPostsEach = $config['startPostsEach'];
                $endPostsEach = $config['endPostsEach'];
                $startCategoriesEach = $config['startCategoriesEach'];
                $endCategoriesEach = $config['endCategoriesEach'];
                $startCommentsEach = $config['startCommentsEach'];
                $endCommentsEach = $config['endCommentsEach'];
                $startLikesEach = $config['startLikesEach'];
                $endLikesEach = $config['endLikesEach'];
                
                
                $partCount = $config['partCount'];
                $finalUsersCount = $config['finalUsersCount'];
                $categoriesCount = $config['categoriesCount'];
                
                $user_insert_values_arr = array();
                $following_insert_values_arr = array();
                $posts_insert_values_arr = array();
                $rel_categories_posts_insert_arr = array();
                $add_comments_rel_count = array();
                $rel_comments_posts_insert_arr = array();
                $rel_like_posts_insert_arr = array();
                
                $categories_insert_arr = array();
                $categories_id_arr = array();
                
                $post_id = 1;
                
                for ($i=1; $i<=$categoriesCount; $i++) {
                        $categories_insert_arr[] = \annex::set_fields($tables['categories'],$i);
                        $categories_id_arr[] = $i;
                }
                
                \hlkiller_core::sql_gen('insert',array(
                    'table'=>'categories',
                    'values'=>$categories_insert_arr
                ));
                
                for ($user_part_first_id=1; $user_part_first_id<=$finalUsersCount; $user_part_first_id+=$partCount) {
	
                    $user_insert_values_arr = array();
                    $following_insert_values_arr = array();
                    
                    
                    
                    $user_part_last = $user_part_first_id+$partCount;
                    // Add Users
                    for($user_id=$user_part_first_id; $user_id<$user_part_last; $user_id++) {
                        $user_insert_values_arr[] = \annex::set_fields($tables['users'],$user_id);
                    }
                    
                    
                    \hlkiller_core::sql_gen('insert',array(
                        'table'=>'users',
                        'values'=>$user_insert_values_arr
                    ));
                    
                    //Add following
                    for($user_id=$user_part_first_id; $user_id<$user_part_last; $user_id++) {
                            $following_insert_values_arr[] = \annex::set_fields($tables['rel_users_following'],$user_id,get_primary_value('users_id',$user_insert_values_arr));
                    }
                    /*echo '<pre>';
                                print_r($user_insert_values_arr);
                                echo '</pre>';*/
                    
                    \hlkiller_core::sql_gen('insert',array(
                        'table'=>'rel_users_following',
                        'values'=>$following_insert_values_arr
                    ));
                    

                    // Add user posts
                    for($user_id=$user_part_first_id; $user_id<$user_part_last; $user_id++) {
                        
                        $posts_insert_values_arr = array();
                        $rel_categories_posts_insert_arr = array();
                        $add_comments_rel_count = array();
                        $rel_comments_posts_insert_arr = array();
                        $rel_like_posts_insert_arr = array();
                        
                        
                        $add_post_count = rand($startPostsEach, $endPostsEach);

                        for($i=$startPostsEach;$i<=$add_post_count;$i++) {
                            // Add Post
                            $posts_insert_values_arr[] = \annex::set_fields($tables['posts'],$post_id,get_primary_value('users_id',$user_insert_values_arr));
                            $post_id++;

                            // Add Categories Relations
                            $add_categories_rel_count = rand($startCategoriesEach, $endCategoriesEach);
                            for($rel_count=$startCategoriesEach; $rel_count<=$endCategoriesEach; $rel_count++) {
                                $rel_categories_posts_insert_arr[] = \annex::set_fields($tables['rel_posts_categories'],$post_id,get_primary_value('categories_id',$categories_insert_arr));
                                        //Array(post_id, rand_from_array(category_id))
                            }
                            
                            // Add Post Comments
                            $add_comments_rel_count = rand($startCommentsEach, $endCommentsEach);
                            for($rel_count=$startCategoriesEach; $rel_count<=$endCategoriesEach; $rel_count++) {
                                $rel_comments_posts_insert_arr[] = \annex::set_fields($tables['rel_users_posts_comments'],get_primary_value('users_id',$user_insert_values_arr),$post_id);
                                        //Array(post_id, user_insert_values_arr[rand_this()], comment_text)
                            }
                            
                            // Add Post Likes
                            $add_like_rel_count = rand($startLikesEach, $endLikesEach);
                             for($rel_count=$startCategoriesEach; $rel_count<=$endCategoriesEach; $rel_count++) {
                                 $rel_like_posts_insert_arr[] = \annex::set_fields($tables['users_posts_like'],get_primary_value('users_id',$user_insert_values_arr),$post_id);
                                 //Array(post_id, user_insert_values_arr[rand_this()], like_time)
                             }
                        }
                        
                        \hlkiller_core::sql_gen('insert',array(
                            'table'=>'posts',
                            'delayed'=>TRUE,// DELAYED
                            'values'=>$posts_insert_values_arr
                        ),true);
                        
                        \hlkiller_core::sql_gen('insert',array(
                            'table'=>'rel_posts_categories',
                            'values'=>$rel_categories_posts_insert_arr
                        ),true);
                        
                        \hlkiller_core::sql_gen('insert',array(
                            'table'=>'rel_users_posts_comments',
                            'values'=>$rel_comments_posts_insert_arr
                        ),true);
                        \hlkiller_core::sql_gen('insert',array(
                            'table'=>'users_posts_like',
                            'values'=>$rel_like_posts_insert_arr
                        ),true);
                    }
                }
                
                /*echo '<pre>';
                print_r($user_insert_values_arr);
                print_r($following_insert_values_arr);
                print_r($posts_insert_values_arr);
                print_r($rel_categories_posts_insert_arr);
                print_r($add_comments_rel_count);
                print_r($rel_comments_posts_insert_arr);
                print_r($rel_like_posts_insert_arr);
                print_r($categories_insert_arr);
                print_r($categories_id_arr);
                echo '</pre>';
                */
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
        
        
	public function clear_db () {
		$db = &\hlkiller_core::db ();
		foreach(\config::getTables() as $table) {
			try {
<<<<<<< HEAD
				$sql = "TRUNCATE TABLE `".$table['TABLE_NAME']."`";
				$result = $db->query($sql);
=======
				//$sql = "TRUNCATE TABLE `".$table['TABLE_NAME']."`";
				$sql = "DELETE FROM `".$table['TABLE_NAME']."`";
                                    
                                $result = \hlkiller_core::db ()->query($sql);
>>>>>>> origin/master
				if ($result === false) {
					throw new \Exceptions\MySQLQuery ('Mysqli died.');
				}
				//--test
			}
			catch (\Exceptions\MySQLQuery $e) {
				die ($e->getMessage());
			}
		}
	}
}