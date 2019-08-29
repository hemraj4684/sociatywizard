<?php
class Home extends CI_Controller {
	public function track_login($id=0,$data=''){
		if(PHP_SAPI=='cli'){
			$this->load->model('user');
			$this->user->login_track($id,$data);
		}
	}
	public function index(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('username', 'Email address or phone number', 'required|min_length[3]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]', array('min_length'=>'Username / Password Incorrect'));
		$err = '';
		if($this->form_validation->run()){
			$this->load->model('loginmodel');
			$result = $this->loginmodel->login($this->input->post('username'))->row();
			if(empty($result)){
				if(is_valid_number($this->input->post('username'))){
					$err = 'Phone number / Password Incorrect';
				} else {
					$err = 'Email address / Password Incorrect';
				}
			} else {
				if($result->phone_verified==='1'){
					$hash = hash('sha256',$this->input->post('password').$result->salt);
					// if($hash===$result->password) {
						$this->load->model('user');
						$society = $this->user->get_users_society($result->id)->result();
						$this->session->type = '2';
						if(!empty($society)){
							$leg_soc = array();
							foreach ($society as $key => $value){
								array_push($leg_soc, $value->society);
								$this->session->soc = $value->society;
								$this->session->type = $value->is_admin;
							}
							unset($value);
							if(count($leg_soc) > 1){
								$this->session->soc_all = $society;
							}
						}
						setcookie('user',$result->id,time() + (172800),'/',null,null,TRUE);
						$this->session->user = $result->id;
						session_regenerate_id(true);
						$eData = array();
						if(isset($_SERVER['HTTP_USER_AGENT'])){
							$eData['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
						}
						if(isset($_SERVER['SERVER_ADDR'])){
							$eData['SERVER_ADDR'] = $_SERVER['SERVER_ADDR'];
						}
						exec('php index.php home track_login '.$this->session->user.' '.base64_encode(json_encode($eData)));
						if($this->session->type==='1'){
							redirect(base_url('dashboard'));
						} else {
							redirect('me');
						}
					// } else {
					// 	if(is_valid_number($this->input->post('username'))){
					// 		$err = 'Phone number / Password Incorrect';
					// 	} else {
					// 		$err = 'Email address / Password Incorrect';
					// 	}
					// }
				} else {
					$err = 'Your Phone Number Is Not Verified.<br>Please Verify To Get Login Access.<br><button type="button" onclick="get_phone_verification(\''.$result->mobile_no.'\',$(this))" class="btn btn-small verify-email-btn indigo accent-3">Verify Now?</button>';
				}
			}
		} else {
			$err = form_error('username');
			if(empty($err)){
				$err = form_error('password');
			}
		}
		$this->load->view('login/index',array('err'=>$err));
	}
	public function register_success(){
		if($this->session->no_verifier){
			$this->session->keep_flashdata('reg_name');
			$this->load->view('login/register_success',array());
		} else {
			redirect('');
		}
	}
	public function verify_email($token='',$id=''){
		if(is_valid_number($id)){
			$exist = $this->redisstore->key_exist('verify:'.$id);
			if($exist){
				$get = $this->redisstore->get('verify:'.$id);
				if($get===$token){
					$this->load->model('user');
					$this->user->update_users_main($id,array('email_verified'=>1));
					$this->redisstore->remove_set('verify:'.$id);
					$this->load->view('login/verify_success');
				} else {
					redirect();
				}
			} else {
				redirect();
			}
		} else {
			redirect();
		}
	}
	public function forgot_password(){
		$this->load->view('login/forgot_password');
	}
	public function start_verify_email_fp(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('email', 'Email ID', 'required|valid_email');
		if($this->form_validation->run()){
			$this->load->model('user');
			$check = $this->user->search_in_user_main(array('email'=>$this->input->post('email')),'id')->row();
			if(!empty($check)){
				$token = hash('sha256', $check->id.uniqid().mt_rand().time());
				$this->redisstore->set_timer('fp:'.$check->id,$token,7200);
				$msg = "<div style='font-family:verdana,sans-serif'><p>You have just requested to reset your password for your SocietyWizard.</p><p>Please click the following link: http://www.societywizard.com/home/get_new_password/$token/$check->id to reset your password.<p><p>Please note that this link is valid only for 2 hours.</p><p>If you have not requested to reset your password, do not worry, it has not been changed. However if you are concerned that someone has done this without your knowing, then please do contact SocietyWizard Support.</p><p><b>Regards,<br>SocietyWizard</b></p></div>";
				mail_this($this->input->post('email'),'Reset your new SocietyWizard password',$msg);
				echo json_encode(array('success'=>1));
			} else {
				echo json_encode(array('err'=>'We did not find any account with that Email ID'));
			}
		} else {
			echo json_encode(array('err'=>form_error('email')));
		}
	}
	public function logout(){
		$this->session->sess_destroy();
		if(isset($_COOKIE['user'])){
			setcookie('user','',time()-3600);
		}
		session_regenerate_id(true);
		redirect();
	}
	public function get_phone_verification(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('phone', 'Mobile Number', 'required|integer|exact_length[10]');
		if($this->form_validation->run()){
			$this->load->model('user');
			$check = $this->user->search_in_user_main(array('mobile_no'=>$this->input->post('phone')),'id')->row();
			if(!empty($check)){
				$token = substr(random_salt(),14,6);
				$this->user->update_new_phone_code($check->id,$token);
				send_sms($this->input->post('phone'),'Your SocietyWizard Verification Code : '.$token);
				$this->session->no_verifier = $check->id;
			}
		}
	}
	public function get_phone_verification_fp(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|integer|exact_length[10]',array('integer'=>'Please Enter Correct 10 Digit Mobile Number'));
		if($this->form_validation->run()){
			$this->load->model('user');
			$check = $this->user->search_in_user_main(array('mobile_no'=>$this->input->post('mobile')),'id')->row();
			if(!empty($check)){
				$token = substr(random_salt(),14,6);
				$this->user->update_new_phone_code($check->id,$token);
				send_sms($this->input->post('mobile'),'Your SocietyWizard Verification Code : '.$token);
				$this->session->fp_verifier = $check->id;
				echo json_encode(array('success'=>1));
			} else {
				echo json_encode(array('err'=>'The mobile number does not exist!'));
			}
		} else {
			echo json_encode(array('err'=>form_error('mobile')));
		}
	}
	public function verify_number(){
		$this->load->view('login/verify_number');
	}
	public function verify_code_fp(){
		if($this->session->fp_verifier){
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('code', 'Verification Code', 'trim|required|exact_length[6]|alpha_numeric');
			if($this->form_validation->run()){
				$this->load->model('user');
				$data = $this->user->verify_phone_code($this->session->fp_verifier)->row();
				if(!empty($data)){
					if($data->phone_code===$this->input->post('code')){
						$this->user->update_phone_code($this->session->fp_verifier,'');
						$this->session->fp_verifier_valid = 1;
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
	public function password_changing($id){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]');
		$this->form_validation->set_rules('r-password', 'Repeat Password', 'required|matches[password]');
		if($this->form_validation->run()){
			$this->load->model('user');
			$salt = random_salt();
			$hash = hash('sha256', $this->input->post('password').$salt);
			$this->user->update_users_main($id,array('password'=>$hash,'salt'=>$salt));
			$this->session->set_flashdata('login_flash','<p class="card-panel center success-area white-text green">You have successfully changed your password.</p>');
			redirect();
		}
	}
	public function get_new_password_mobile(){
		if($this->session->fp_verifier_valid || $this->session->fp_verifier){
			$this->password_changing($this->session->fp_verifier);
			$this->load->view('login/change_password');
		} else {
			redirect();
		}
	}
	public function get_new_password($token='',$id=''){
		if(is_valid_number($id)){
			$exist = $this->redisstore->key_exist('fp:'.$id);
			if($exist){
				$get = $this->redisstore->get('fp:'.$id);
				if($get===$token){
					$this->password_changing();
					$this->load->view('login/change_password');
				} else {
					redirect();
				}
			} else {
				redirect();
			}
		} else {
			redirect();
		}
	}
	public function new_society_enquiry(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('s_name', 'Society Name', 'required|max_length[50]');
		$this->form_validation->set_rules('cp_name', 'Contact Person Name', 'required|max_length[50]');
		$this->form_validation->set_rules('cp_no', 'Contact Person Mobile Number', 'required|exact_length[10]|integer');
		if($this->form_validation->run()){
			$this->load->model('loginmodel');
			$data = array();
			$data['society_name'] = $this->input->post('s_name');
			$data['contact_person_name'] = $this->input->post('cp_name');
			$data['contact_person_number'] = $this->input->post('cp_no');
			$this->loginmodel->insert_enquiry($data);
			echo json_encode(array('success'=>1));
		} else {
			$sn = form_error('s_name');
			$cpn = form_error('cp_name');
			$cpmn = form_error('cp_no');
			if(!empty($sn)){
				echo json_encode(array('err'=>$sn));
			} else if(!empty($cpn)) {
				echo json_encode(array('err'=>$cpn));
			} else {
				echo json_encode(array('err'=>$cpmn));
			}
		}
	}
}