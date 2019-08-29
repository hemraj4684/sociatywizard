<?php
class Documents extends Controlusers {
	public function index(){
		$this->js = '<script src="'.base_url('assets/js/jquery.contextMenu.js').'"></script><script src="'.base_url('assets/js/doc_main.js').'"></script><script src="'.base_url('assets/js/jquery.ui.position.min.js').'"></script>';
		$this->css = '<link href="'.base_url('assets/css/jquery.contextMenu.min.css').'" rel="stylesheet">';
		$this->load->view('header');
		$this->load->model('documentsmodel');
		$folder = $this->documentsmodel->get_all_folders($this->session->soc);
		$this->load->view('documents/index',array('folder'=>$folder));
		$this->load->view('footer');
	}
	public function folder($id=0){
		if(is_valid_number($id)){
			$this->load->model('documentsmodel');
			$folder = $this->documentsmodel->get_a_folder($id,$this->session->soc)->row();
			if(empty($folder)){
				show_404();
			} else {
				$this->js = '<script src="'.base_url('assets/js/jquery.contextMenu.js').'"></script><script src="'.base_url('assets/js/jquery.ui.position.min.js').'"></script><script src="'.base_url('assets/js/docs.js').'"></script>';
				$this->css = '<link href="'.base_url('assets/css/jquery.contextMenu.min.css').'" rel="stylesheet">';
				$files = $this->documentsmodel->folder_files($id);
				$this->load->view('header');
				$this->load->view('documents/folder',array('folder'=>$folder,'files'=>$files,'id'=>$id));
				$this->load->view('footer');
			}
		}
	}
}