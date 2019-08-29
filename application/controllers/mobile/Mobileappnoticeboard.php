<?php
class Mobileappnoticeboard extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){$this->output->set_header('HTTP/1.0 403 Forbidden');}
	}
	public function notice_view(){
		$this->load->model('mobilemodel');
		$data = array();
		if($this->input->server('HTTP_SOCIETY')){
			$data = $this->mobilemodel->fetch_notices($this->input->server('HTTP_SOCIETY'))->result();
		}
		if(empty($data)){
			echo '<div class="na-data">No Notice Avaliable!</div>';
		} else {
			echo '<div class="col s12">';
			foreach ($data as $key => $value) {
				echo '<div class="card black-text"><div class="card-content"><div class="card-title center">Notice</div><p class="mb15 grey-text font-12 right-align">Dated : '.date('dS F Y',strtotime($value->date_submited)).'</p>';
				echo strip_tags($value->notice_text,'<hr><u><b><p><a><strong><i><em><span><div><h1><h2><h3><h4><h5><h6><address><pre><ol><li>');
				echo '</div></div>';
			}
			unset($value);
			echo '</div>';
		}
	}
}