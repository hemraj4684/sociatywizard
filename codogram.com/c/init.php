<?php
session_start();
define('SALT','d156f2c66d4007f000feec6442a78f01');
define('SITE','http://www.codogram.com/');
define('URI',rtrim(strtolower($_SERVER['REQUEST_URI']),'/'));
date_default_timezone_set('UTC');
define('APP_PATH', 'c/');
//cookie secure https
//header('Access-Control-Allow-Origin: http://localhost');
// access control exposse headerd
//no cache for json types