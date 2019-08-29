<?php
require 'important.php';
header('Content-Type: application/json');
if(isset($_POST['report'],$_POST['token'])){
$class = new ReportController();
echo json_encode($class->web_report($_POST['report'],$_POST['token']));
}