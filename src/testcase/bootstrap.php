<?php
/*
 * #cd unittest
 * phpunit --bootstrap testcase/bootstrap.php testcase
 * single file run
 * phpunit --bootstrap testcase/bootstrap.php testcase/filename.php 
 */
define ( "SKIPAUTOLOADERROR", "TRUE" );
require dirname ( __FILE__ ) . "./../configure.php";
$GLOBALS['db']->Debug = false;
$GLOBALS['AppConfig']['DeveloperMode'] = true;