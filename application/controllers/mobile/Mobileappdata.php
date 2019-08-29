<?php
class Mobileappdata extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){$this->output->set_header('HTTP/1.0 403 Forbidden');}
	}
	public function get_user_pic(){
		$this->load->model('mobilemodel');
		$query = $this->mobilemodel->get_userpic($this->input->server('HTTP_USER_ID'))->row();
		if(!empty($query)){
			if(!empty($query->picture)){
				echo base_url().'assets/members_picture/'.h($query->picture);
			} else {
				echo 'assets/img/user_image.png';
			}
		} else {
			echo 'assets/img/user_image.png';
		}
	}
}