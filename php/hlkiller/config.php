<?php
class config {
	const VIEW_PATH = 'hlkiller/view';

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