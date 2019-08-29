<?php
class Mobileregister extends Controlmobile {
	public function __construct(){
		parent::__construct();
	}
	public function login(){
		header('Content-Type: application/json');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|integer|exact_length[10]',array('integer'=>'Please enter correct mobile number','exact_length'=>'Please enter correct 10 digit mobile number'));
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]',array('min_length'=>'Mobile Number / Password is Incorrect'));
		if($this->form_validation->run()){
			$this->load->model('mobilemodel');
			$data = $this->mobilemodel->login($this->input->post('mobile'))->row();
			if(empty($data)){
				echo json_encode(array('err' => 'Mobile Number / Password is Incorrect'));
			} else {
				if($data->phone_verified==='1'){
					$hash = hash('sha256', $this->input->post('password').$data->salt);
					if($hash===$data->password){
						$this->load->model('user');
						$society = $this->user->get_users_society($data->id)->result();
						$leg_soc = array();
						$my_soc = '0';
						foreach ($society as $key => $value) {
							array_push($leg_soc, $value->society);
							$my_soc = $value->society;
						}
						unset($value);
						echo json_encode(array('success'=>'1','id'=>$data->id,'token'=>$data->token,'society'=>$my_soc));
					} else {
						echo json_encode(array('err' => 'Mobile Number / Password is Incorrect'));
					}
				} else {
					echo json_encode(array('err' => 'Mobile Number / Password is Incorrect'));
				}
			}
		} else {
			$merr = form_error('mobile');
			$pwerr = form_error('password');
			if(!empty($merr)){
				echo json_encode(array('err' => $merr));
			} else if(!empty($pwerr)) {
				echo json_encode(array('err' => $pwerr));
			}
		}
	}
	public function register(){
		header('Content-Type: application/json');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email ID', 'trim|valid_email|is_unique[users.email]',array('is_unique'=>'%s already exists'));
		$this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|integer|exact_length[10]|is_unique[users.mobile_no]',array('integer'=>'%s must only contain numbers','is_unique'=>'%s already exists'));
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]');
		if($this->form_validation->run()){
			$salt = md5('zdgf5275dfsdg'.time().'dsgjhsd545DSAd');
			$hash = hash('sha256', $this->input->post('password').$salt);
			$token = hash('sha256', mt_rand().$salt.time());
			$data = array(
				'firstname' => $this->input->post('first_name'),
				'lastname' => $this->input->post('last_name'),
				'email' => $this->input->post('email'),
				'mobile_no' => $this->input->post('mobile'),
				'password' => $hash,
				'salt' => $salt,
				'token' => $token
			);
			$this->load->model('user');
			$code = substr($salt,10,6);
			$id = $this->user->mobile_register($data,$code);
			$msg = "Hello $data[firstname] $data[lastname],%nThank You for registering at SocietyWizard.%nYour Verification Code : $code";
			send_sms($this->input->post('mobile'),$msg);
			echo json_encode(array('success'=>'1','id'=>$id));
		} else {
			$ferr = form_error('first_name');
			$lerr = form_error('last_name');
			$eerr = form_error('email');
			$merr = form_error('mobile');
			$pwerr = form_error('password');
			if(!empty($ferr)){
				echo json_encode(array('err' => $ferr));
			} else if(!empty($lerr)) {
				echo json_encode(array('err' => $lerr));
			} else if(!empty($eerr)) {
				echo json_encode(array('err' => $eerr));
			} else if(!empty($merr)) {
				echo json_encode(array('err' => $merr));
			} else {
				echo json_encode(array('err' => $pwerr));
			}
		}
	}
	public function verify_phone(){
		header('Content-Type: application/json');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('code', 'Verification Code', 'trim|required|exact_length[6]|alpha_numeric');
		$this->form_validation->set_rules('id', 'User', 'trim|required|integer');
		if($this->form_validation->run()){
			$this->load->model('user');
			$data = $this->user->verify_phone_code($this->input->post('id'))->row();
			if(!empty($data)){
				if($data->phone_code===$this->input->post('code')){
					$this->user->update_phone_code($this->input->post('id'),'');
					$this->session->pw_reset = $this->input->post('id');
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
	}
	public function get_new_code(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('number', 'Mobile Number', 'trim|required|exact_length[10]|integer',array('integer'=>'Please Enter Your Correct 10 Digit Mobile Number'));
		if($this->form_validation->run()){
			$this->load->model('user');
			$verify = $this->user->check_number_exists($this->input->post('number'))->row();
			if(!empty($verify)){
				$salt = md5(mt_rand());
				$code = substr($salt,10,6);
				$this->user->update_phone_code($verify->id,$code);
				send_sms($this->input->post('number'),'Your SocietyWizard Verification Code : '.$code);
				echo json_encode(array('success'=>'1','id'=>$verify->id));
			} else {
				echo json_encode(array('err'=>'The Mobile Number You Have Entered Does Not Exists'));
			}
		} else {
			echo json_encode(array('err'=>form_error('number')));
		}
	}
	public function reset_new_password(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]');
		$this->form_validation->set_rules('re-password', 'Confirm Password', 'required|matches[password]',array('required'=>'Please confirm your new password','matches'=>'Password does not match the Confirm Password'));
		if($this->form_validation->run() && $this->session->pw_reset){
			$this->load->model('user');
			$salt = md5(mt_rand());
			$pw = $this->input->post('password');
			$hash = hash('sha256', $pw.$salt);
			$data = array('password'=>$hash,'salt'=>$salt);
			$this->user->update_users_main($this->session->pw_reset,$data);
			$this->session->unset_userdata('pw_reset');
			echo json_encode(array('success'=>1));
		} else {
			$pw_err = form_error('password');
			$pw_err2 = form_error('re-password');
			if($pw_err!==''){
				echo json_encode(array('err'=>$pw_err));
			} else if($pw_err2!==''){
				echo json_encode(array('err'=>$pw_err2));
			} else {
				echo json_encode(array('err'=>'Unable to verify your account!'));
			}
		}
	}
}