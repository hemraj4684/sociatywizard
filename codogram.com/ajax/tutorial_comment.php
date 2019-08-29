<?php
require 'important.php';
if(isset($_POST['comment'],$_POST['token'])){
$ajax = new FrontEndAjax();
echo $ajax->tutorial_comment($_POST['comment'],$_POST['token']);
}