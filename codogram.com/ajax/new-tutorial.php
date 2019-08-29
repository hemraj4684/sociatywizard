<?php
require 'important.php';
header('Content-Type: application/json');
if(isset($_POST['title'],$_POST['conclusion'],$_POST['tags'],$_POST['token'],$_POST['link'],$_POST['code'],$_POST['description'],$_POST['explain'],$_POST['heading'])){
$tutorial = new TutorialsController();
$tutorial->add();
}