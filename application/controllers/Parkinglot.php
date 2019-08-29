<?php
class Parkinglot extends Controlusers {
	public function index() {
		$this->js = '<script src="'.base_url('assets/js/parkinglot.js').'"></script>';
		$this->header();
		$this->load->model('parkinglotmodel');
		$data = $this->parkinglotmodel->get_parking_list($this->society)->result();
		$this->load->view('parkinglot/index',array('data'=>$data));
		$this->footer();
	}
}