<?php
require 'important.php';
header('Content-Type: application/json');
if(isset($_POST['title'],$_POST['description'],$_POST['conclusion'],$_POST['tags'],$_POST['token'])){
$tutorial = new TutorialsController();
$tutorial->update_intro();
}
if(isset($_POST['language'],$_POST['explain'],$_POST['heading'],$_POST['token'],$_POST['ID'],$_POST['code'])){
$tutorial = new TutorialsController();
$tutorial->update_block();
}
if(isset($_POST['token'],$_POST['positions'])){
$tutorial = new TutorialsController();
echo json_encode($tutorial->change_positions($_POST['positions'],$_POST['token']));
}
if(isset($_POST['rt_id'],$_POST['token'])){
$tutorial = new TutorialsController();
echo json_encode($tutorial->remove_tutorial($_POST['token']));
}
if(isset($_POST['permalink'],$_POST['token'])){
$tutorial = new TutorialsController();
echo json_encode($tutorial->change_permalink($_POST['permalink'],$_POST['token']));
}