<?php
require 'important.php';
if(isset($_POST['user'],$_POST['tut'],$_POST['token'])){
$class = new FrontEndAjax();
echo $class->load_home1($_POST['user'],$_POST['tut'],$_POST['token']);
}