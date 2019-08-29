<?php
require 'important.php';
if(isset($_POST['token'])){
$ajax = new FrontEndAjax();
$ajax->load_comments($_POST['token']);
}