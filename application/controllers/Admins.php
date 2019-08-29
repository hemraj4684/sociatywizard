<?php
class Admins extends Controlusers {
	public function index(){
		$this->js = dt_options().'<script type="text/javascript" src="'.base_url('assets/js/admins_list.js').'"></script>';
		$this->css = dt_css();
		$this->load->model('user');
		$data = $this->user->get_admins_list($this->session->soc)->result();
		$this->load->view('header');
		$this->load->view('admins/list',array('data'=>$data));
		$this->load->view('layout/sms_modal');
		$this->load->view('footer');
	}
}