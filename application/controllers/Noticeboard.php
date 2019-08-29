<?php
class Noticeboard extends Controlusers {
	public function index(){
		$this->load->model('noticeboard_model');
		$notices = $this->noticeboard_model->fetch_notices($this->session->soc)->result();
		$this->js = '<script src="'.base_url('assets/js/editor/ckeditor.js').'"></script><script src="'.base_url('assets/js/notice_board.js').'"></script>';
		$this->load->view('header');
		$this->load->view('noticeboard/index',array('notices'=>$notices));
		$this->load->view('footer');
	}
}