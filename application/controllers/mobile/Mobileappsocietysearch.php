<?php
class Mobileappsocietysearch extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){$this->output->set_header('HTTP/1.0 403 Forbidden');}
		$this->load->model('mobilemodel');
	}
	public function index() {
		if(!$this->mobilemodel->request_verify($this->input->server('HTTP_USER_ID'))){
			$this->load->model('user');
			$society = $this->user->get_users_society($this->input->server('HTTP_USER_ID'))->result();
			if(!empty($society)){
				$this->output->set_header('HTTP/1.0 403 Forbidden');
			} else {
				$data = $this->mobilemodel->all_societies()->result();
				echo '<div class="row data-list"><div class="grey lighten-3 col s12 data-list-item"><h6><b>Send A Request To Your Society</b></h6><p><b>Note :</b> Avoid sending spam request.<br>Otherwise your account will be blocked.</p></div>';
				foreach ($data as $key => $value) {
					echo '<a href="#" data-id="'.$value->id.'" class="col s12 society-s-item data-list-item"><p class="mb5"><b>'.h($value->society_name).'</b></p><p class="mt0">'.h($value->society_address).'</p></a>';
				}
				unset($value);
				echo '</div>';
			}
		} else {
			$data = $this->mobilemodel->requested_society($this->input->server('HTTP_USER_ID'))->row();
			if(!empty($data)){
				echo '<div class="row data-list"><div class="col data-list-item s12">';
				echo '<p class="mb5 black-text"><b>You have sent request to the society below</b></p>';
				echo '<p class="mb5">'.h($data->society_name).'</p><p class="mt0 mb5">'.h($data->society_address).'</p>';
				echo '<p class="mb5">Date Sent : '.date('h:ia, dS M Y',strtotime($data->date_requested)).'</p>';
				if($data->status==='1'){
					echo '<p>Status : <span class="btn btn-small orange">Pending</span></p>';
				} else {
					echo '<p>Status : <span class="btn btn-small red">Decline</span></p>';
				}
				echo '</div></div>';
			}
		}
	}
	public function send_request($id='') {
		$this->output->set_header('Content-Type: application/json');
		if(is_valid_number($id)){
			if($this->mobilemodel->society_exist($id)){
				if(!$this->mobilemodel->request_verify($this->input->server('HTTP_USER_ID'))){
					$this->mobilemodel->society_request_entry(array('society_id'=>$id,'user_id'=>$this->input->server('HTTP_USER_ID')));
					echo json_encode(array('success'=>'1'));
					exit();
				} else {
					echo json_encode(array('err'=>'You have already sent a request!'));
					exit();
				}
			} else {
				echo json_encode(array('err'=>'Invalid Request'));
				exit();
			}
		} else {
			echo json_encode(array('err'=>'Invalid Request'));
			exit();
		}
	}
}