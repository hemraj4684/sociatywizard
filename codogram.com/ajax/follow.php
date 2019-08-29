<?php
require 'important.php';
header('Content-Type: application/json');
if(isset($_POST['token'],$_POST['id'])){
$class = new EditingProfile();
echo json_encode($class->follow_unfollow($_POST['token'],$_POST['id']));
}