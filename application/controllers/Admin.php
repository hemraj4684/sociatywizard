<?php
class Admin extends CI_Controller {
	public $css,$js;
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		redirect('dashboard');
	}
	// public function sms(){
	// 	$query = $this->db->query("SELECT id, firstname, mobile_no FROM `users` WHERE `date_register` LIKE '%2016-08-20%'");
	// 	$res = $query->result();
	// 	foreach ($res as $key => $value) {
	// 		$salt = random_salt();
	// 		var_dump($value);
	// 		$password = substr(random_salt(),10,6);
	// 		$hash = hash('sha256', $password.$salt);
	// 		var_dump($password);
	// 		$this->db->limit(1)->where(array('id'=>$value->id))->update('users',array('password'=>$hash,'salt'=>$salt));
	// 		sleep(0.5);
	// 		send_sms($value->mobile_no,"Hello ".$value->firstname.",%nYour society admin has created an account for you at www.societywizard.com%nYour login details are :%nusername : ".$value->mobile_no."%npassword : ".$password);

	// 	}
	// 	// var_dump("Hello ".$value->firstname.",%nYour society admin has created an account for you at www.societywizard.com%nYour login details are :%nusername : ".$value->mobile_no."%npassword : ".$password);
	// }
}