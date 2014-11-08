<?php

define ('DIRSEP', DIRECTORY_SEPARATOR);
define ('EXT', '.php');
define ('PATH', dirname (__FILE__).DIRSEP);

function __autoload ($classname) {
	$filename = PATH.'hlkiller'.DIRSEP.$classname.EXT;
	include_once ($filename);
}

\hlkiller_core::connect();
	if (isset ($_GET ['do'])) {
		switch ($_GET ['do']) {
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
				$machine_gun ->hight_load_emulation();
			}
				break;
			case 'get_statistics' : {
				$machine_gun = new \machine_gun ();
				$machine_gun ->get_statistics();
			}
				break;
		}
	} else
		\hlkiller_core::get_view();
\hlkiller_core::disconnect();