<?php
class Userpage extends Shareaccess {
	public function settings(){
		$this->js = '<script src="'.base_url('assets/js/user_setting.js').'"></script>';
		$data = $this->user->user_by_id($this->session->user,'gender,date_of_birth,phone_privacy,mobile_no,email')->row();
		$this->header();
		$this->load->view('userpage/settings',array('data'=>$data));
		$this->footer();
	}
}