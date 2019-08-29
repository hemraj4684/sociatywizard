<?php
class Subscriptionhistory extends Controlusers {
	public function index(){
		$this->header();
		$this->load->model('societysettingmodel');
		$data = $this->societysettingmodel->get_my($this->society)->row();
		$this->load->view('subscription/index',array('data'=>$data));
		$this->js = '<script src="'.base_url('assets/js/subscription.js').'"></script>';
		$this->footer();
	}
}