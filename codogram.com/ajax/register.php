<?php
require 'important.php';
header('Content-Type: application/json');
if(isset($_POST['firstname'],$_POST['lastname'],$_POST['email'],$_POST['password'],$_POST['token'],$_POST['country'])){
    $class = new Registration();
    echo json_encode($class->make_register($_POST['firstname'],$_POST['lastname'],$_POST['email'],$_POST['password'],$_POST['country'],$_POST['token']));
}