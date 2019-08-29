<?php
class UsersAjax extends AppController {
	public function load_following($id,$token){
		if($this->check_token('E1',$token)){
            if($this->forms_validation_start() && ctype_digit($id) && $id!=='0'){
            	$this->follows_view($this->model->users_following($id));
            }
        }
	}
    public function load_followers($id,$token){
        if($this->check_token('E1',$token)){
            if($this->forms_validation_start() && ctype_digit($id) && $id!=='0'){
                $this->follows_view($this->model->users_followers($id));
            }
        }
    }
    public function follows_view(array $data){
        foreach($data as $val){
            (empty($val['pc'])) ? $pc='/assets/img/default-profile.jpg' : $pc='/dp/user-profile-pictures/'.$this->sanitize($val['pc']);
            echo '<div class="user-as-item col s6 m4 l3"><a href="/'.$this->sanitize($val['un']).'"><img class="center-block" src="'.$pc.'"></a><h3 class="center"><a class="blue-text text-accent-4" href="/'.$this->sanitize($val['un']).'">'.$this->sanitize($val['fn']).' '.$this->sanitize($val['ln']).'</a></h3></div>';
        }
        unset($val);
    }
}