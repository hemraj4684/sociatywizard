<?php
require 'important.php';
header('Content-Type: application/json');
if(isset($_POST['token'])){
$ajax = new FrontEndAjax();
echo json_encode($ajax->like($_POST['token']));
}