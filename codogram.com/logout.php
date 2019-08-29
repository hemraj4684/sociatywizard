<?php
require 'c/init.php';
session_destroy();
if(isset($_COOKIE['user'])){
setcookie('user','',time()-3600);
}
session_regenerate_id(true);
header('Location:/login');
exit();