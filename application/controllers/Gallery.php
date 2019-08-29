<?php
class Gallery extends Controlusers {
	public function index(){
		$this->load->model('gallerymodel');
		$query = $this->gallerymodel->get_all_folders($this->session->soc);
		$this->css = '<link href="'.base_url('assets/css/jquery.contextMenu.min.css').'" rel="stylesheet">';
		$this->load->view('header');
		$this->load->view('gallery/index',array('query'=>$query));
		$this->js = '<script src="'.base_url('assets/js/jquery.contextMenu.js').'"></script><script src="'.base_url('assets/js/jquery.ui.position.min.js').'"></script><script src="'.base_url('assets/js/gallery_index.js').'"></script>';
		$this->load->view('footer');
	}
	public function folder($id=0){
		if(is_valid_number($id)){
			$this->load->model('gallerymodel');
			$folder = $this->gallerymodel->get_one_folder($id,$this->session->soc)->row();
			if(empty($folder)){
				show_404();
			} else {
				$files = $this->gallerymodel->folder_files($id)->result();
				$this->js = '<script src="'.base_url('assets/js/jquery.contextMenu.js').'"></script><script src="'.base_url('assets/js/jquery.ui.position.min.js').'"></script><script src="'.base_url('assets/js/lightbox.js').'"></script><script src="'.base_url('assets/js/gallery.js').'"></script>';
				$this->css = '<link href="'.base_url('assets/css/lightbox.css').'" rel="stylesheet"><link href="'.base_url('assets/css/jquery.contextMenu.min.css').'" rel="stylesheet">';
				$this->load->view('header');
				$this->load->view('gallery/folder',array('folder'=>$folder,'files'=>$files,'id'=>$id));
				$this->load->view('footer');
			}
		} else {
			show_404();
		}
	}
}