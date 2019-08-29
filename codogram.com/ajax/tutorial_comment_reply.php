<?php
require 'important.php';
if(isset($_POST['reply'],$_POST['token'],$_POST['id'])){
$ajax = new FrontEndAjax();
echo $ajax->insert_reply($_POST['reply'],$_POST['token'],$_POST['id']);
}