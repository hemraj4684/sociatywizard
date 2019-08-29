<?php
require 'important.php';
if(isset($_POST['ifid'],$_POST['token'])){
$class = new UsersAjax();
$class->load_following($_POST['ifid'],$_POST['token']);
}
if(isset($_POST['fmid'],$_POST['token'])){
$class = new UsersAjax();
$class->load_followers($_POST['fmid'],$_POST['token']);
}