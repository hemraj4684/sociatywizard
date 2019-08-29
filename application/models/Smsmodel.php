<?php
class Smsmodel extends MY_Model {
	public function store_sms($data){
		$this->db->insert('sms_storage',$data);
	}
}