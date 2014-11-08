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
		}
	} else
		\hlkiller_core::get_view();
\hlkiller_core::disconnect();