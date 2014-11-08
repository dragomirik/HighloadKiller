<?php

define ('DIRSEP', DIRECTORY_SEPARATOR);
define ('EXT', '.php');
define ('PATH', dirname (__FILE__).DIRSEP);

// class autoload
function __autoload ($classname) {
	$filename = PATH.'hlkiller'.DIRSEP.$classname.EXT;
	include_once ($filename);
}
// function for routing ajax queries
function ajax_route ($request) {
	switch ($request) {
		case 'generate_fish' : {
			$db_generator = new \db_generator ();
			$db_generator->generate_fish();
		}
			break;
		case 'clear_db' : {
			$db_generator = new \db_generator ();
			$db_generator->clear_db();
		}
			break;
		case 'add_structure_dump' : {
			$db_generator = new \db_generator ();
			$db_generator->add_structure_dump();
		}
			break;
		case 'make_select_attack' : {
			$machine_gun = new \machine_gun ();
			$machine_gun ->make_select_attack();
		}
			break;
		case 'make_mixed_attack' : {
			$machine_gun = new \machine_gun ();
			$machine_gun ->make_mixed_attack();
		}
			break;
		case 'hight_load_emulation' : {
			$machine_gun = new \machine_gun ();
			$machine_gun ->high_load_emulation();
		}
			break;
		case 'get_statistics' : {
			$machine_gun = new \machine_gun ();
			$machine_gun ->get_statistics();
		}
			break;
	}
}

// db connect (mysqli)
\hlkiller_core::connect();

	// route ajax queries
	if (isset ($_GET ['do'])) {
		ajax_route ($_GET ['do']);
	} else
		// print index view
		echo \ui::get_index_view();

//db disconnect
\hlkiller_core::disconnect();