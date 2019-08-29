<?php
require 'important.php';
header('Content-Type: application/json');
if(isset($_POST['email'],$_POST['token'])){
$class = new ForgotPasswordAjax();
echo json_encode($class->reset_password($_POST['email'],$_POST['token']));
}