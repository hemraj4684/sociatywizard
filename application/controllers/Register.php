<?php
class Register extends CI_Controller {
	public function index(){
		$this->general_validation();
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]');
		$this->form_validation->set_rules('gender', 'Gender', 'required');
		if($this->form_validation->run()){
			$this->load->model('user');
			$salt = random_salt();
			$hash = hash('sha256', $this->input->post('password').$salt);
			$token = hash('sha256', mt_rand().$salt.time());
			$code = substr($salt,10,6);
			$data = array(
				'firstname' => $this->input->post('first_name'),
				'lastname' => $this->input->post('last_name'),
				'email' => $this->input->post('email'),
				'mobile_no' => $this->input->post('mobile'),
				'password' => $hash,
				'salt' => $salt,
				'token' => $token
			);
			if($this->input->post('gender')==='2'){
				$data['gender'] = 2;
			}
			$last = $this->user->register($data,$code);
			$this->session->set_flashdata('reg_name',$this->input->post('first_name')." ".$this->input->post('last_name'));
			$this->session->no_verifier = $last;
			$e_token = hash('sha256', $last.random_salt());
			$this->redisstore->set_timer('verify:'.$last,$e_token,7200);
			$msg = "Hello $data[firstname] $data[lastname],%nThank You for registering at SocietyWizard.%nYour Verification Code : $code";
			send_sms($this->input->post('mobile'),$msg);
			$full = $this->input->post('first_name')." ".$this->input->post('last_name');
			mail_this($this->input->post('email'),'Welcome to SocietyWizard.com',"<div style='font-family:verdana,sans-serif'><p>Hi $full,</p><p>Thank you for signing up with SocietyWizard, please click the link below to complete your registration.</p><p>http://www.societywizard.com/home/verify_email/$e_token/$last</p><b>Thanks,<br>SocietyWizard<br>www.societywizard.com</b></p></div>");
			echo json_encode(array('success'=>'1'));
		} else {
			echo json_encode(array(
				'fn' => form_error('first_name'),
				'ln' => form_error('last_name'),
				'em' => form_error('email'),
				'mo' => form_error('mobile'),
				'pw' => form_error('password'),
				'gn' => form_error('gender')
			));
		}
	}
	public function general_validation($chk_mob = '|is_unique[users.mobile_no]',$chk_email = '|is_unique[users.email]') {
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[25]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[25]');
		$this->form_validation->set_rules('email', 'Email ID', 'required|valid_email'.$chk_email,array('is_unique'=>'%s already exists'));
		$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|integer|exact_length[10]'.$chk_mob,array('integer'=>'%s must only contain numbers','is_unique'=>'%s already exists'));
	}
	public function verify_code(){
		if($this->session->no_verifier){
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('code', 'Verification Code', 'trim|required|exact_length[6]|alpha_numeric');
			if($this->form_validation->run()){
				$this->load->model('user');
				$data = $this->user->verify_phone_code($this->session->no_verifier)->row();
				if(!empty($data)){
					if($data->phone_code===$this->input->post('code')){
						$this->user->update_phone_code($this->session->no_verifier,'');
						$this->session->no_verifier = NULL;
						$this->session->set_flashdata('login_flash','<div class="card-panel center success-area green">Thank You!<br>You have successfully verified your mobile number.<br>Please Login Now</div>');
						echo json_encode(array('success'=>'1'));
					} else {
						echo json_encode(array('err'=>'Incorrect Verification Code'));
					}
				} else {
					echo json_encode(array('err'=>'Incorrect Verification Code'));
				}
			} else {
				echo json_encode(array('err'=>form_error('code')));
			}
		} else {
			echo json_encode(array('err'=>'Unable to identify you!'));
		}
	}
}