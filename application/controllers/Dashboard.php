<?php
class Dashboard extends Controlusers {
	public function index(){
		$this->css = '<link rel="stylesheet" href="'.base_url('assets/css/dashboard.css').'">';
		$this->header();
		$this->load->model('dashboardmodel');
		$data_on = $this->dashboardmodel->dashboard_one($this->society);
		$this->load->view('dashboard',array('data_on'=>$data_on));
		$this->js = '<script src="'.base_url('assets/js/admin_dashboard_1.1.js').'"></script>';
		$this->footer();
	}
}