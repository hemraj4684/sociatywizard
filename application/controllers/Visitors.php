<?php
class Visitors extends Controlusers {
	public function index(){
		if($this->input->get('reset')){
			$this->session->unset_userdata('vfrom');
			$this->session->unset_userdata('vupto');
			redirect('visitors');
			exit();
		}
		$data = array();
		$this->css = dt_css().'<link href="'.base_url('assets/css/materialize.clockpicker.css').'" rel="stylesheet">';
		$this->js = dt_options().'<script src="'.base_url('assets/js/visitors.js').'"></script><script src="'.base_url('assets/js/materialize.clockpicker.js').'"></script>';
		$this->load->model('watchmandata');
		$wname = $this->watchmandata->watchman_username($this->society);
		if($this->session->vfrom){
			$data = $this->watchmandata->search_by_date($this->society,$this->session->vfrom,$this->session->vupto);
		} else {
			$data = $this->watchmandata->visitors($this->society);
		}
		$this->load->view('header');
		$this->load->view('visitors/index',array('data'=>$data->result(),'wname'=>$wname));
		$this->load->view('footer');
	}
}