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
			case 'plug' : echo 1;
			case 'generate_fish' : $db_generator->generate_fish();
			case 'clear_db' : $db_generator->clear_db();
			case 'make_select_attack' : $machine_gun ->make_select_attack();
			case 'make_mixed_attack' : $machine_gun ->make_mixed_attack();
			case 'hight_load_emulation' : $machine_gun ->hight_load_emulation();
			case 'get_statistics' : $machine_gun ->get_statistics();
		}
	} else
		\hlkiller_core::get_view();
\hlkiller_core::disconnect();