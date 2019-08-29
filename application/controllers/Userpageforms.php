<?php
class Userpageforms extends Shareaccesscontrol {
	public function change_pic(){
		$cur_f = date('m_Y');
		$config['upload_path'] = 'assets/members_picture/'.$cur_f;
        $config['allowed_types'] = 'jpeg|jpg|png';
	    $config['max_size'] = 2048;
	    $config['encrypt_name'] = TRUE;
	    if (!is_dir('assets/members_picture/'.$cur_f)) {
		    mkdir('assets/members_picture/'.$cur_f, 0777, TRUE);
		}
		$this->load->model('user');
		$user = $this->user->user_by_id($this->session->user,'picture')->row();
		if(!empty($user->picture)){
			$exp_pic = explode('/',$user->picture);
			if(count($exp_pic)===2){
				$cur_f = $exp_pic[0];
				$config['upload_path'] = 'assets/members_picture/'.$cur_f;
			}
		}
		$this->load->library('upload', $config);
		if($this->upload->do_upload()){
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
			$this->user->update_users_main($this->session->user,array('picture'=>$cur_f.'/'.$picn));
			if(!empty($user->picture)){
				if(file_exists($this->input->server('DOCUMENT_ROOT').'/assets/members_picture/'.$user->picture)){
					unlink($this->input->server('DOCUMENT_ROOT').'/assets/members_picture/'.$user->picture);
				}
			}
			echo json_encode(array('success'=>1,'new_file'=>$cur_f.'/'.$picn));
		} else {
			echo json_encode(array('error' => $this->upload->display_errors('','')));
		}
	}
	public function change_basic(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('fn', 'Firstname', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('ln', 'Lastname', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('gender', 'Gender', 'required');
		if($this->form_validation->run()){
			$data = array('firstname'=>$this->input->post('fn'),'lastname'=>$this->input->post('ln'),'date_of_birth'=>NULL,'gender'=>1);
			if(!empty($this->input->post('dob'))){
				if(!is_valid_date($this->input->post('dob'),$dob)){
					echo json_encode(array('dob'=>'Please enter correct date'));
					exit();
				}
				$data['date_of_birth'] = $dob;
			}
			if($this->input->post('gender')==='2'){
				$data['gender'] = 2;
			}
			$data['phone_privacy'] = 1;
			if($this->input->post('mo_privacy')){
				$data['phone_privacy'] = 2;
			}
			$this->load->model('user');
			$this->user->update_users_main($this->session->user,$data);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('fn'=>form_error('fn'),'ln'=>form_error('ln'),'gn'=>form_error('gender')));
		}
	}
	public function user_changing_password(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('old_pw', 'Old Password', 'required|min_length[3]',array('min_length'=>'Incorrect Old Password'));
		$this->form_validation->set_rules('new_pw', 'New Password', 'required|min_length[3]');
		$this->form_validation->set_rules('c_new_pw', 'Confirm Password', 'required|matches[new_pw]',array('matches'=>'Confirm password doesn\'t match'));
		if($this->form_validation->run()){
			$this->load->model('user');
			$query = $this->user->user_by_id($this->session->user,'password,salt')->row();
			if(!empty($query)){
				$old_hash = hash('sha256', $this->input->post('old_pw').$query->salt);
				if($query->password===$old_hash){
					$salt = random_salt();
					$hash = hash('sha256', $this->input->post('new_pw').$salt);
					$data = array('password'=>$hash,'salt'=>$salt);
					$this->user->update_users_main($this->session->user,$data);
					echo json_encode(array('success'=>1));
				} else {
					echo json_encode(array('old_pw'=>'Incorrect Old Password'));
				}
			} else {
				echo json_encode(array('old_pw'=>'Incorrect Old Password'));
			}
		} else {
			echo json_encode(array('old_pw'=>form_error('old_pw'),'new_pw'=>form_error('new_pw'),'c_new_pw'=>form_error('c_new_pw')));
		}
	}
	public function new_helpdesk(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|max_length[255]|min_length[3]');
		$this->form_validation->set_rules('h-message', 'Message', 'trim|required|min_length[10]');
		if($this->form_validation->run()){
			$type="2";
			if($this->input->post('type')==='1'){
				$type="1";
			}
			$data = array('message'=>$this->input->post('h-message'),'message_subject'=>$this->input->post('subject'),'message_type'=>$type,'sender_id'=>$this->session->user,'society_id'=>$this->society);
			$this->db->insert('members_messages',$data);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('subject'=>form_error('subject'),'msg'=>form_error('h-message')));
		}
	}
}