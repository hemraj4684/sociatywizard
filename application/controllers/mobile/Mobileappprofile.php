<?php
class Mobileappprofile extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){$this->output->set_header('HTTP/1.0 403 Forbidden');}
	}
	public function update_page(){
		$this->load->model('mobilemodel');
		$data = $this->mobilemodel->for_user_settings($this->input->server('HTTP_USER_ID'))->row();
		if(!empty($data)){
			echo '<div class="blue-grey lighten-5 pt15 pb15 row"><div class="col s6 pic-holder-u">';
			if(empty($data->picture)){
				echo '<img class="responsive-img" src="assets/img/user_image.png">';
			} else {
				echo '<img class="responsive-img" src="'.base_url('assets/members_picture/'.$data->picture).'">';
			}
			echo '</div><div class="col s6">';
			echo '<label><span class="btn font-12 white-space" id="change_ep_img">Change Image</span></label>';
      		echo '</div></div>';
			echo form_open('','id="user-profile-update"');
			echo '<div class="col s12 input-field"><input maxlength="35" type="text" value="'.h($data->firstname).'" name="fn" placeholder="Firstname"></div>';
			echo '<div class="col s12 input-field"><input maxlength="35" type="text" value="'.h($data->lastname).'" name="ln" placeholder="Lastname"></div>';
			echo '<div class="input-field"><div class="col s6"><input class="with-gap" name="gender" value="1" type="radio" id="male"';
			if($data->gender==='1'){
				echo ' checked';
			}
			echo '><label for="male">Male</label></div><div class="col s6"><input class="with-gap" name="gender" value="2" type="radio" id="female"';
			if($data->gender==='2'){
				echo ' checked';
			}
			echo '><label for="female">Female</label></div></div>';
			echo '<div class="input-field col s12"><input type="text" name="date" value="';
			if(!empty($data->date_of_birth)){
				echo date('d-m-Y',strtotime($data->date_of_birth));
			}
			echo '" placeholder="Date Of Birth" class="datepicker"><p class="mt0 font-12 grey-text">Your Date Of Birth Is Private.</p></div>';
			echo '<div style="height:135px" class="input-field col s12"><hr class="mt5 mb10"><span class="grey-text text-darken-1">Your Mobile Number Privacy</span><div class="switch">
<label style="margin-top:40px;">Private<input type="checkbox" name="mobile_privacy" value="1"';
			if($data->phone_privacy==2){
				echo ' checked';
			}
			echo '><span class="lever"></span>Public</label>
</div><p class="grey-text font-12" style="margin-top:50px;">Private : Nobody will see your mobile number.<br>Public : Your mobile number will be visible to your society members.</p><hr style="margin-top:5px"></div><div class="col s12"></div>';
  			echo '<div class="input-field col s12 grey-text clearfix">To change other information you can contact your society admin through <i class="mdi-hardware-headset-mic"></i> Helpdesk.</div><div class="input-field col s12"><button class="col s12 mt15 indigo accent-3 setting-update-btn btn" type="submit">Update</button></div>';
			echo form_close();
		}
	}
	public function change_image(){
		$this->output->set_header('Content-Type: application/json');
		$cur_f = date('m_Y');
		$config['upload_path'] = 'assets/members_picture/'.$cur_f;
        $config['allowed_types'] = 'jpeg|jpg|png';
	    $config['max_size'] = 2048;
	    $config['encrypt_name'] = TRUE;
	    if (!is_dir('assets/members_picture/'.$cur_f)) {
		    mkdir('assets/members_picture/'.$cur_f, 0777, TRUE);
		}
		$this->load->model('user');
		$user = $this->user->user_by_id($this->input->server('HTTP_USER_ID'),'picture')->row();
		if(!empty($user->picture)){
			$exp_pic = explode('/',$user->picture);
			if(count($exp_pic)===2){
				$cur_f = $exp_pic[0];
				$config['upload_path'] = 'assets/members_picture/'.$cur_f;
			}
		}
		$this->load->library('upload', $config);
		if($this->upload->do_upload('file')){
			$config['image_library'] = 'gd2';
			$config['width'] = 600;
			$config['height'] = 600;
			$config['source_image'] = $this->upload->upload_path.$this->upload->file_name;
            $config['create_thumb'] = false;
            $config['maintain_ratio'] = TRUE;
			$config['new_image'] = $this->upload->file_name;
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
			$picn = $this->upload->data()['file_name'];
			$this->user->update_users_main($this->input->server('HTTP_USER_ID'),array('picture'=>$cur_f.'/'.$picn));
			if(!empty($user->picture)){
				if(file_exists($this->input->server('DOCUMENT_ROOT').'/assets/members_picture/'.$user->picture)){
					unlink($this->input->server('DOCUMENT_ROOT').'/assets/members_picture/'.$user->picture);
				}
			}
			echo json_encode(array('new_pic'=>base_url('assets/members_picture/'.$cur_f.'/'.$picn),'success'=>1));
		} else {
			echo json_encode(array('error'=>$this->upload->display_errors('','')));
		}
	}
	public function update_profile_submit(){
		$this->output->set_header('Content-Type: application/json');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('fn', 'Firstname', 'required|max_length[35]',array('max_length'=>'Your Firstname is too long.'));
		$this->form_validation->set_rules('ln', 'Lastname', 'required|max_length[35]',array('max_length'=>'Your Lastname is too long.'));
		$this->form_validation->set_rules('gender', 'Gender', 'required',array('required'=>'Please Select Your Gender'));
		if($this->form_validation->run()){
			$date = NULL;
			if($this->input->post('date')!==''){
				$exdate = explode('-', $this->input->post('date'));
				if(count($exdate)===3){
					if(!empty($exdate[0]) && !empty($exdate[1]) && !empty($exdate[2])){
						if(!checkdate($exdate[1],$exdate[0],$exdate[2])){
							echo json_encode(array('err'=>'Your Date Of Birth is Invalid'));
							exit();
						} else {
							$date = $exdate[2].'-'.$exdate[1].'-'.$exdate[0];
						}
					} else {
						echo json_encode(array('err'=>'Your Date Of Birth is Invalid'));
						exit();
					}
				} else {
					echo json_encode(array('err'=>'Your Date Of Birth is Invalid'));
					exit();
				}
			}
			$gender = 1;
			if($this->input->post('gender')!=='1'){
				$gender = 2;
			}
			$this->load->model('mobilemodel');
			$data = array('firstname'=>$this->input->post('fn'),'lastname'=>$this->input->post('ln'),'gender'=>$gender,'date_of_birth'=>$date);
			$data['phone_privacy'] = 1;
			if($this->input->post('mobile_privacy')){
				$data['phone_privacy'] = 2;
			}
			$this->mobilemodel->update_user($this->input->server('HTTP_USER_ID'),$data);
			echo json_encode(array('success'=>1));
		} else {
			if(form_error('fn')!==''){
				echo json_encode(array('err'=>form_error('fn')));
			} else if(form_error('ln')!==''){
				echo json_encode(array('err'=>form_error('ln')));
			} else if(form_error('gender')!==''){
				echo json_encode(array('err'=>form_error('gender')));
			}
		}
	}
}