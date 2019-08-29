<?php
class Backgroundjobs extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->input->is_cli_request()){exit();}
	}
	public function exec_u_by_u_notif($name='',$number='',$pw='',$user_id=0){
		if($user_id > 0){
			$this->load->model('mobilemodel');
			$username = $this->mobilemodel->get_user_name($user_id);
			$user = $username->row();
			if(!empty($user)){
				$sms = "Hello ".str_replace(' ', '+', $name).",
 ".$user->firstname." has created an account for you at www.societywizard.com
Please click the link to download the App -> http://goo.gl/tzbpfa

Your login details for App:
username : ".$number."
password : ".$pw;
				send_sms($number,$sms);
			}
		}
	}
	public function send_invoice_sms($id='0'){
		if($id > 0){
			$this->load->library('redisstore');
			if($this->redisstore->key_exist('inv:'.$id)){
				multiRequest(json_decode($this->redisstore->get('inv:'.$id)));
				$this->redisstore->remove_set('inv:'.$id);
			}
		}
	}
	public function remove_visitors($soc){
		if($this->redisstore->key_exist('rmv:'.$soc)){
			$data = json_decode($this->redisstore->get('rmv:'.$soc));
			if(isset($data[0],$data[1])){
				$this->load->model('watchmandata');
				$parent = array();
				foreach ($data[0] as $value) {
					$parent[] = $value->parent_id;
				}
				unset($value);
				if(!empty($parent)){
					$this->watchmandata->delete_logs($data[1]);
					$res = $this->watchmandata->check_parent_count($parent)->result();
					if(!empty($res)){
						foreach ($res as $key => $value) {
							if($value->total==='0'){
								if($value->image){
									if(file_exists('assets/visitors/'.$soc.'/'.$value->image)){
										unlink('assets/visitors/'.$soc.'/'.$value->image);
									}
								}
								$this->watchmandata->delete_single_record($value->id);
							}
						}
						unset($value);
					}
				}
			}
		}
	}
	public function send_alerts($soc){
		if($this->redisstore->key_exist('alert:'.$soc)){
			$data = json_decode($this->redisstore->get('alert:'.$soc));
			if(isset($data[0],$data[1])){
				if($data[1]==='1'){
					$this->load->model('user');
					$users = $this->user->association_members_simple($soc,'mobile_no')->result();
					$numbers = '';
					foreach ($users as $key => $value) {
						$numbers .= $value->mobile_no.',';
					}
					unset($value);
					$numbers = trim($numbers,',');
					send_sms($numbers,$data[0]);
				} else if($data[1]==='2'){
					$this->load->model('user');
					$users = $this->user->get_users_simple($this->society,'mobile_no')->result();
					$numbers = '';
					foreach ($users as $key => $value) {
						$numbers .= $value->mobile_no.',';
					}
					unset($value);
					$numbers = trim($numbers,',');
					send_sms($numbers,$data[0]);
				}
			}
		}
	}
}