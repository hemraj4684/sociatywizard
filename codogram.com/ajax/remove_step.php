<?php
require 'important.php';
header('Content-Type: application/json');
if(isset($_POST['token'],$_POST['id'])){
$tutorial = new TutorialsController();
echo json_encode($tutorial->remove_block($_POST['id'],$_POST['token']));
}