<?php
class Registeredmembersform extends Requestcontrol {
	public function add_user(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('first_name', 'Firstname', 'trim|required|max_length[25]');
		$this->form_validation->set_rules('last_name', 'Lastname', 'trim|required|max_length[25]');
		$this->form_validation->set_rules('email', 'Email ID', 'valid_email|is_unique[users.email]',array('is_unique'=>'%s already exists'));
		$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|integer|exact_length[10]|is_unique[users.mobile_no]',array('integer'=>'%s must only contain numbers','is_unique'=>'%s already exists'));
		$this->form_validation->set_rules('gender', 'Gender', 'required');
		$this->form_validation->set_rules('designation', 'Designation', 'max_length[25]');
		$flat = $this->input->post('flat_id');
		if(!empty($flat)){
			$this->form_validation->set_rules('ot', 'Owner / Tenant', 'required');
		}
		if($this->form_validation->run()){
			$flat = $this->input->post('flat_id');
			$nof = 0;
			if(!empty($flat)){
				$this->flat_validation($flat);
				$nof = 1;
			}
			$salt = random_salt();
			$password = substr(random_salt(),10,6);
			$hash = hash('sha256', $password.$salt);
			$token = hash('sha256', $salt.$hash);
			$data = array('firstname'=>$this->input->post('first_name'),'lastname'=>$this->input->post('last_name'),'mobile_no'=>$this->input->post('mobile'),'gender'=>1,'salt'=>$salt,'password'=>$hash,'token'=>$token,'phone_verified'=>1,'email_verified'=>1);
			if(!empty($this->input->post('email'))){
				$data['email'] = $this->input->post('email');
			}
			if($this->input->post('gender')==='2'){
				$data['gender'] = 2;
			}
			$this->load->model('user');
			$ot=1;
			if($this->input->post('ot')==='2'){
				$ot=2;
			}
			$is_admin=2;
			if($this->input->post('is_admin')){
				$is_admin=1;
			}
			$is_assoc=2;
			if($this->input->post('is_assoc')){
				$is_assoc=1;
			}
			$this->user->admin_user_add($data,$this->society,$is_assoc,$flat,$ot,$nof,$is_admin,$this->input->post('designation'));
			$sms = "Hello ".$this->input->post('first_name').",
Your society admin has created an account for you at www.societywizard.com
Please click the link to download the App -> http://goo.gl/tzbpfa

Your login details for App:
username : ".$this->input->post('mobile')."
password : ".$password;
			send_sms($this->input->post('mobile'),$sms);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('fn'=>form_error('first_name'),'ln'=>form_error('last_name'),'em'=>form_error('email'),'mo'=>form_error('mobile'),'gn'=>form_error('gender'),'ot'=>form_error('ot'),'de'=>form_error('designation')));
		}
	}
	public function edit(){
		$chk_mob = '|is_unique[users.mobile_no]';
		$chk_email = '|is_unique[users.email]';
		if($this->input->post('mobile_old')===$this->input->post('mobile')){
			$chk_mob = '';
		}
		if($this->input->post('email_old')===$this->input->post('email')){
			$chk_email = '';
		}
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[25]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[25]');
		$this->form_validation->set_rules('email', 'Email ID', 'valid_email'.$chk_email,array('is_unique'=>'%s already exists'));
		$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|integer|exact_length[10]'.$chk_mob,array('integer'=>'%s must only contain numbers','is_unique'=>'%s already exists'));
		$this->form_validation->set_rules('id', 'User', 'required|is_natural_no_zero',array('is_natural_no_zero'=>'Please Select A Valid User'));
		$this->form_validation->set_rules('designation', 'Designation', 'max_length[25]');
		if($this->form_validation->run()){
			$data = array(
				'firstname' => $this->input->post('first_name'),
				'lastname' => $this->input->post('last_name'),
				'mobile_no' => $this->input->post('mobile')
			);
			if(!empty($this->input->post('email'))){
				$data['email'] = $this->input->post('email');
			} else {
				$data['email'] = NULL;
			}
			$is_admin=2;
			if($this->input->post('is_admin')){
				$is_admin=1;
			}
			$is_assoc = 2;
			if($this->input->post('is_assoc')){
				$is_assoc = 1;
			}
			$data_s = array('assoc_member'=>$is_assoc,'is_admin'=>$is_admin,'designation'=>$this->input->post('designation'));
			$this->load->model('user');
			$this->user->update($data,$data_s,$this->input->post('id'),$this->society);
			$this->session->set_flashdata('edit_user','<div class="card success-area green"><div class="card-content"><i class="fa fa-thumbs-o-up"></i> Changes Saved Successfully!</div></div>');
			echo json_encode(array('success'=>'1'));
		} else {
			echo json_encode(array(
				'fn' => form_error('first_name'),
				'ln' => form_error('last_name'),
				'em' => form_error('email'),
				'mo' => form_error('mobile'),
				'status'=> form_error('status'),
				'mem_type' => form_error('member_type'),
				'user_id' => form_error('id'),
				'de'=>form_error('designation')
			));
		}
	}
	public function flats_info_update(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('flat_id[]', 'Flat', 'is_natural_no_zero',array('is_natural_no_zero'=>'Please Select A Valid Flat'));
		$this->form_validation->set_rules('ot[]', 'Owner / Tenant', 'is_natural_no_zero');
		$this->form_validation->set_rules('id', 'Member', 'required|is_natural_no_zero');
		if($this->form_validation->run()){
			$flat = $this->input->post('flat_id[]');
			$ot = $this->input->post('ot[]');
			if(count($flat)===count($ot)){
				$data = array();
				$soc = $this->session->soc;
				$t_house = 0;
				if(!empty($flat)){
					sort($ot);
					$flat = array_unique($flat);
					$this->flat_validation($flat);
					foreach ($flat as $key => $value) {
						++$t_house;
						$ot_i = 2;
						if($ot[$key]==='1'){
							$ot_i = 1;
						}
						array_push($data, array('user'=>$this->input->post('id'),'flat_id'=>$value,'society_id'=>$soc,'owner_tenant'=>$ot_i));
					}
					unset($value);
				}
				$this->load->model('flatmodel');
				$this->flatmodel->update_user_flat($this->input->post('id'),$soc,$data,$t_house);
				echo json_encode(array('success'=>'1'));
			} else {
				echo json_encode(array('error'=>'Please Select All The Available Options'));
			}
		} else {
			echo json_encode(array('error'=>form_error('flat_id[]')));
		}
	}
	public function reset_password($id=0){
		if(is_valid_number($id)){
			$this->load->model('user');
			if($this->user->verify_user($id,$this->session->soc)){
				$salt = md5(time().mt_rand());
				$pw = substr(md5($salt),10,6);
				$hash = hash('sha256', $pw.$salt);
				$this->user->update_users_main($id,array('password'=>$hash,'salt'=>$salt));
				$row = $this->user->user_by_id($id,'mobile_no')->row();
				if(!empty($row)){
					send_sms($row->mobile_no,"Your society admin has sent you a new password for SocietyWizard : ".$pw."%nDo not forget to change it after you login.");
				}
			}
		}
	}
	public function remove_users(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id[]', 'Members', 'required|is_natural_no_zero');
		if($this->form_validation->run()){
			$this->load->model('user');
			$ids = array_unique($this->input->post('id[]'));
			$suser = $this->session->user;
			$meok = 0;
			if(in_array($suser,$ids)){
				foreach ($ids as $key => $value) {
					if($value==$suser){
						unset($ids[$key]);
						$meok = 1;
					}
				}
			}
			if($this->user->verify_users($ids,$this->society)){
				$this->user->remove_from_society($ids,$this->society);
				echo json_encode(array('success'=>'1'));
			} else {
				if($meok==1){
					echo json_encode(array('err'=>'You cannot remove yourself!'));
				} else {
					echo json_encode(array('err'=>'Please Select A Member To Remove'));
				}
			}
		} else {
			echo json_encode(array('err'=>'Please Select A Member To Remove'));
		}
	}
	public function request_admin_init($id='',$what=''){
		if(is_valid_number($id) && ($what==1 || $what==2)){
			$this->load->model('user');
			if($this->user->admin_request_verify($id,$this->society)){
				if(!$this->user->verify_user($id,$this->society)){
					if($what==1){
						$this->user->accept_admin_request($id,$this->society);
					} else {
						$this->user->reject_admin_request($id,$this->society);
					}
					echo json_encode(array('success'=>'1'));
				} else {
					$this->user->remove_from_request($id,$this->society);
					echo json_encode(array('err'=>'This person is already a member of your society'));
				}
			} else {
				echo json_encode(array('err'=>'Invalid Request'));
			}
		} else {
			echo json_encode(array('err'=>'Invalid Request'));
		}
	}
}