<?php
class Mobileapp extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){$this->output->set_header('HTTP/1.0 403 Forbidden');}
	}
	public function helpdesk_history_item(){
		$this->load->model('mobilemodel');
		$data = $this->mobilemodel->get_user_name($this->input->server('HTTP_USER_ID'))->row();
		$this->load->view('app/helpdesk/history_item',array('data'=>$data));
	}
	public function get_userpic(){
		$this->load->model('mobilemodel');
		$pic = $this->mobilemodel->get_userpic($this->input->server('HTTP_USER_ID'))->row();
		if(!empty($pic)){
			if(!empty($pic->picture)){
				return base_url('assets/members_picture/'.$pic->picture);
			}
		}
		return 'assets/img/user_image.png';
	}
	public function notif_data(){
		if($this->input->post('id') && $this->input->server('HTTP_USER_ID')){
			$this->load->model('mobilemodel');
			$this->mobilemodel->single_data_add(array('push_token'=>$this->input->post('id')),$this->input->server('HTTP_USER_ID'));
		}
	}
}