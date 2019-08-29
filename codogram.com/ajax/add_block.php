<?php
require 'important.php';
header('Content-Type: application/json');
if(isset($_POST['explain'],$_POST['heading'],$_POST['ajaxToken'],$_POST['code'])){
$tutorial = new TutorialsController();
echo json_encode($tutorial->add_new_block());
}