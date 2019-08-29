<?php
class Alerts extends Controlusers {
	public function index(){
		$this->js = '<script src="'.base_url('assets/js/materialize.clockpicker.js').'"></script><script src="'.base_url('assets/js/alerts.js').'"></script>';
		$this->css = '<link href="'.base_url('assets/css/materialize.clockpicker.css').'" rel="stylesheet">';
		$this->header();
		$this->load->view('alerts/index');
		$this->footer();
	}
}