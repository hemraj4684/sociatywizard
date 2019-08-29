<?php
class Controlmobile extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('admin_helper');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: X-Requested-With,MY-TOKEN,AUTH-TOKEN,USER-ID,SOCIETY');
		if(!$this->valid_request()){exit();}
	}
	function valid_request(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'],$_SERVER['HTTP_MY_TOKEN']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest' && $_SERVER['HTTP_MY_TOKEN']==='MTI3MmY0MDAzODU2MzdlYmZlZDU1ZTM2ZmQ4MWIwYTNiNWI3ZGY4MjgwMDgwYjdkY2E0Y2Q1MTk5OTQ0ZDQzYw=='){
			return true;
		}
		return false;
	}
	public function authenticated_user(){
		if($this->input->server('HTTP_AUTH_TOKEN') && $this->input->server('HTTP_USER_ID')){
			$this->load->model('mobilemodel');
			$data = $this->mobilemodel->valid_user_check($this->input->server('HTTP_USER_ID'))->row();
			$soc_id = 0;
			if($this->input->server('HTTP_SOCIETY')){
				$soc = $this->mobilemodel->valid_user_society($this->input->server('HTTP_USER_ID'),$this->input->server('HTTP_SOCIETY'))->row();
				if(!empty($soc)){
					$soc_id = $soc->society;
				} else {
					return false;
				}
			}
			if(empty($data)){
				return false;
			} else {
				if($data->token===$this->input->server('HTTP_AUTH_TOKEN') && ($soc_id===0 || $this->input->server('HTTP_SOCIETY')===$soc_id)){
					return true;
				} else {
					return false;
				}
			}
		}
		return false;
	}
}
class WatchmanParent extends CI_Controller {
	public $society,$user_id;
	public function __construct(){
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: X-Requested-With,MY-TOKEN,AUTH-TOKEN,USER-ID,SOCIETY');
		if(!$this->valid_request()){exit();}
	}
	function valid_request(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'],$_SERVER['HTTP_MY_TOKEN']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest' && $_SERVER['HTTP_MY_TOKEN']==='MWYyYTNiODJiYjQ1ZGIyYWU4N2MzNmM4M2Q3OTI1Zjg2OWJiMmI5NTRkYzUzYjRhOWE4YTMzMmI0MjdiYWI2Ng=='){
			return true;
		}
		return false;
	}
	public function authenticated_user(){
		if($this->input->server('HTTP_AUTH_TOKEN') && $this->input->server('HTTP_USER_ID') && $this->input->server('HTTP_SOCIETY')){
			$this->load->model('watchmandata');
			$this->society = $this->input->server('HTTP_SOCIETY');
			$this->user_id = $this->input->server('HTTP_USER_ID');
			if($this->watchmandata->watchman_verify($this->input->server('HTTP_USER_ID'),$this->input->server('HTTP_AUTH_TOKEN'),$this->society)){
				return true;
			}
		}
		return false;
	}
}
class Controlusers extends CI_Controller {
	public $css,$js,$fn,$ln,$pic,$society,$s_name,$s_address,$metatags;
	public function __construct(){
		parent::__construct();
		auth_admin();
		$this->society = $this->session->soc;
		$this->usertype = $this->session->type;
		$this->load->model('user');
		$user = $this->user->user_with_society($this->session->user,$this->society,'firstname,lastname,picture,society_name,society_address')->row();
		$this->fn = h($user->firstname);
		$this->ln = h($user->lastname);
		$this->s_name = h($user->society_name);
		$this->s_address = h($user->society_address);
		if(!empty($user->picture)){
			$this->pic = base_url('assets/members_picture/'.h($user->picture));
		} else {
			$this->pic = base_url('assets/images/user_image.png');
		}
	}
	public function header(){
		$this->load->view('header');
	}
	public function footer(){
		$this->load->view('footer');
	}
}
class Userrole extends CI_Controller {
	public $css,$js,$fn,$ln,$pic,$society,$usertype,$s_name,$s_address,$metatags;
	public function __construct(){
		parent::__construct();
		$this->society = $this->session->soc;
		$this->usertype = $this->session->type;
		$this->load->model('user');
		$user = $this->user->user_with_society_init($this->session->user,$this->society,'firstname,lastname,picture,society_name,society_address')->row();
		$this->fn = h($user->firstname);
		$this->ln = h($user->lastname);
		if(!empty($user->picture)){
			$this->pic = base_url('assets/members_picture/'.h($user->picture));
		} else {
			$this->pic = base_url('assets/images/user_image.png');
		}
		$this->s_name = h($user->society_name);
		$this->s_address = h($user->society_address);
	}
	public function header(){
		$this->load->view('header');
	}
	public function footer(){
		$this->load->view('footer');
	}
	public function is_my_flat($user,$flat_id){
		$this->load->model('flatmodel');
		$row = $this->flatmodel->user_flat_verify($user,$flat_id);
		if($row->num_rows()>0){
			return true;
		}
		return false;
	}
}
class Shareaccess extends CI_Controller {
	public $css,$js,$fn,$ln,$pic,$society,$usertype,$s_name,$s_address,$metatags;
	public function __construct(){
		parent::__construct();
		auth_user();
		$this->society = $this->session->soc;
		$this->usertype = $this->session->type;
		$this->load->model('user');
		if($this->usertype==='1'){
			$user = $this->user->user_with_society($this->session->user,$this->society,'firstname,lastname,picture,society_name,society_address')->row();
		} else {
			$user = $this->user->user_with_society_init($this->session->user,$this->society,'firstname,lastname,picture,society_name,society_address')->row();
		}
		$this->s_name = h($user->society_name);
		$this->s_address = h($user->society_address);
		$this->fn = h($user->firstname);
		$this->ln = h($user->lastname);
		if(!empty($user->picture)){
			$this->pic = base_url('assets/members_picture/'.h($user->picture));
		} else {
			$this->pic = base_url('assets/images/user_image.png');
		}
	}
	public function header(){
		$this->load->view('header');
	}
	public function footer(){
		$this->load->view('footer');
	}
}
class Requestcontrol extends CI_Controller {
	public $society;
	public function __construct(){
		parent::__construct();
		$this->society = $this->session->soc;
		if(!verify_admin()){
			$this->output->set_status_header('401');
			exit();
		}
	}
	public function flat_validation($id,$select='',&$return=''){
		if(!is_valid_flat($this->society,$id,$select,$return)){
			$this->output->set_status_header('400');
			exit();
		}
	}
	public function get_society_main_data(){
		$this->load->model('societysettingmodel');
		return $this->societysettingmodel->get_my($this->society)->row();
	}
	public function get_users_pushToken(){
		$this->load->model('user');
		$data = $this->user->get_pushTokens($this->society);
		$users = array();
		foreach ($data as $value) {
			array_push($users, $value->push_token);
		}
		unset($value);
		return $users;
	}
	public function send_one_notification($id,$msg,$par=array()){
		$this->load->model('user');
		$data = $this->user->get_pushToken($id);
		if(!empty($data)){
			sendNotification($msg,array($data->push_token),$par);
		}
	}
}
class Shareaccesscontrol extends CI_Controller {
	public $society;
	public function __construct(){
		parent::__construct();
		$this->society = $this->session->soc;
		if(!verify_user()){
			$this->output->set_status_header('401');
			exit();
		}
	}
}