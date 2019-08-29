<?php
require 'important.php';
header('Content-Type: application/json');
if(isset($_POST['id'],$_POST['token'])){
$ajax = new FrontEndAjax();
echo json_encode($ajax->remove_tutorial_comment($_POST['id'],$_POST['token']));
}