<?php
class Loginmodel extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	public function login($user){
		return $this->db->limit(1)->select('id,password,salt,token,phone_verified,mobile_no')->where(array('email'=>$user))->or_where('mobile_no',$user)->get('users');
	}
	public function insert_enquiry($data){
		$this->db->insert('society_enquiry',$data);
	}
}