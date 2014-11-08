<?php

define ('DIRSEP', DIRECTORY_SEPARATOR);
define ('EXT', '.php');
define ('PATH', dirname(__FILE__).DIRSEP);

function __autoload($classname) {
	$filename = PATH.'hlkiller'.DIRSEP.$classname.EXT;
	include_once($filename);
}

\Application::run ();

?>