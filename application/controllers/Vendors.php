<?php
class Vendors extends Controlusers {
	public function index(){
		$this->js = dt_options().'<script src="'.base_url('assets/js/vendors.js').'"></script>';
		$this->css = dt_css();
		$this->load->model('vendorsmodel');
		$data = $this->vendorsmodel->get_vendors($this->session->soc)->result();
		$this->load->view('header');
		$this->load->view('vendors/index',array('data'=>$data));
		$this->load->view('footer');
	}
}