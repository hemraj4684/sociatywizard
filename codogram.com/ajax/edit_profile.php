<?php
require 'important.php';
header('Content-Type: application/json');
if(isset($_POST['fn'],$_POST['token'],$_POST['ln'])){
$ep = new EditingProfile();
echo json_encode($ep->form1($_POST['fn'],$_POST['ln'],$_POST['token']));
}
if(isset($_POST['about'],$_POST['token'])){
$ep = new EditingProfile();
echo json_encode($ep->form2($_POST['about'],$_POST['token']));
}
if(isset($_POST['fb'],$_POST['wb'],$_POST['tw'],$_POST['token'])){
$ep = new EditingProfile();
echo json_encode($ep->form3($_POST['wb'],$_POST['fb'],$_POST['tw'],$_POST['token']));
}
if(isset($_POST['token'],$_FILES['photo'])){
$ep = new EditingProfile();
echo json_encode($ep->form4($_FILES['photo'],$_POST['token']));
}
if(isset($_POST['username'],$_POST['token'])){
$ep = new EditingProfile();
echo json_encode($ep->form5($_POST['username'],$_POST['token']));
}
if(isset($_POST['token'],$_FILES['banner'])){
$ep = new EditingProfile();
echo json_encode($ep->form6($_FILES['banner'],$_POST['token']));
}
if(isset($_POST['token'],$_POST['long'],$_POST['lat'])){
$ep = new EditingProfile();
echo json_encode($ep->form7($_POST['token'],$_POST['lat'],$_POST['long']));
}
if(isset($_POST['password'],$_POST['password_n'],$_POST['password_r'],$_POST['token'])){
$ep = new EditingProfile();
echo json_encode($ep->form8($_POST['password'],$_POST['password_n'],$_POST['password_r'],$_POST['token']));
}
if(isset($_POST['skill-token'])){
$ep = new EditingProfile();
$skill=[];
if(isset($_POST['skills'])){$skill = $_POST['skills'];}
echo json_encode($ep->form9($_POST['skill-token'],$skill));
}