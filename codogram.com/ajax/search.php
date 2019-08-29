<?php
require 'important.php';
if(isset($_POST['search'],$_POST['token'])){
$class = new SearchController();
$searching='0';
if(isset($_POST['searching'])){$searching=$_POST['searching'];}
$class->search_result($_POST['search'],$searching,$_POST['token']);
}