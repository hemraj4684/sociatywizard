<?php
class Mobilesocietymembers extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){$this->output->set_header('HTTP/1.0 403 Forbidden');}
	}
	public function members(){
		if($this->input->server('HTTP_SOCIETY')){
			$this->load->model('mobilemodel');
			$data = $this->mobilemodel->members_list_by_society($this->input->server('HTTP_SOCIETY'))->result();
			echo '<ul class="collection">';
			foreach ($data as $key => $value) {
				echo '<li class="collection-item avatar">';
				if($value->picture){
					echo '<img style="background-image:url('.base_url('assets/members_picture/'.$value->picture).')" class="circle members_list_pic">';
				} else {
					echo '<img style="background-image:url('.base_url('assets/images/user_image.png').')" class="circle z-depth-1 members_list_pic">';
				}
				echo '<span class="title">'.h($value->firstname.' '.$value->lastname).'</span>';
				echo '<p>'.$value->designation.'</p>';
				echo '';
				if($value->phone_privacy == 1){
					echo '<p><i class="fa fa-phone"></i> &nbsp;<i class="fa fa-lock"></i> private number</p>';
				} else {
					echo '<p><i class="fa fa-phone"></i> &nbsp;<a class="sm_telno" href="tel:'.$value->mobile_no.'">'.$value->mobile_no.'</a></p>';
				}
				echo '</li>';
			}
			unset($value);
			echo '</ul>';
		}
	}
}