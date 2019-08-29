<?php
class Mobileappnotification extends Controlmobile {
	public function index(){
		if($this->authenticated_user()){
			if($this->input->post('id')){
				$this->load->model('mobilemodel');
				$this->mobilemodel->single_data_add(array('push_token'=>$this->input->post('id')),$this->input->server('HTTP_USER_ID'));
			}
		}
	}
}