<?php
class Mobileappmyflat extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){$this->output->set_header('HTTP/1.0 403 Forbidden');}
	}
	public function get_flat_page_data(){
		$this->load->model('mobilemodel');
		$flat = array();
		if($this->input->server('HTTP_SOCIETY')){
			$flat = $this->mobilemodel->get_user_flats($this->input->server('HTTP_USER_ID'),$this->input->server('HTTP_SOCIETY'))->result();
		}
		if(!empty($flat)){
			$flat_id = array();
			foreach ($flat as $key => $value) {
				array_push($flat_id, $value->flat_id);
			}
			unset($value);
			$fdetail = $this->mobilemodel->fetch_flat_details($flat_id)->result();
			$count_u = $this->mobilemodel->count_users_in_flat($flat_id)->result();
			foreach ($fdetail as $key => $value) {
				echo '<div class="col s12"><div class="card-panel"><h5 class="mt0">Flat Details</h5><table class="bordered highlight"><tr><td>Flat No</td><td>';
				if(!empty($value->name)){
					echo h($value->name).' - ';
				}
				echo h($value->flat_no).'</td></tr><tr><td>Intercom</td><td>'.h($value->intercom).'</td></tr><tr><td>Flat Area</td><td>'.h($value->sq_foot).' Sq ft</td></tr><tr><td>Owner Name</td><td>'.h($value->owner_name).'</td></tr><tr><td>Owner Contact</td><td>'.h($value->owner_number).'</td></tr>';
		        if($value->status==='2'){
		        	echo '<tr><td>Flat Status</td><td>On Rent</td></tr>';
		        }
				echo '</table></div></div><div class="col s5"><a data-transition="slide" data-flat="'.$value->id.'" href="#flat_member_list" class="block indigo accent-3 white-text fm_list card-panel"><i class="fa fa-user"></i> ';
				foreach ($count_u as $val) {
					if($val->flat_id===$value->id){
						echo '<span class="d_tm">'.$val->total.'</span>';
						break;
					}
				}
				unset($val);
				echo ' Members</a></div><div class="col s7"><a data-position-to="window" data-transition="fade" href="#addmemberpopup" data-rel="popup" class="green white-text card-panel block"><i class="fa fa-plus"></i> Add Flat Members</a></div>';
				$vehi = $this->mobilemodel->flat_parking($value->id,$value->total_parking)->result();
				if(!empty($vehi)){
					foreach ($vehi as $val1) {
						echo '<div class="col s12"><div class="card-panel"><table>';
						echo '<tr><td>Parking Slot</td><td>'.h($val1->slot_label).'</td></tr><tr><td>Number Plate</td><td>'.h($val1->no_plate).'</td></tr><tr><td>Vehicle Model</td><td>'.h($val1->vehicle_model).'</td></tr><tr><td>Vehicle Type</td><td>';
						if($val1->vehicle_type==='2'){
							echo 'Four Wheeler';
						} else {
							echo 'Two Wheeler';
						}
						echo '</td></tr>';
						echo '</table></div></div>';
					}
					unset($val1);
				}
      			echo '<hr>';
			}
			unset($value);
		} else {
			echo '<div class="na-data">No Data Available</div>';
		}
	}
	public function members_from($id=0){
		if(is_valid_number($id)){
			$this->load->model('mobilemodel');
			$flat = $this->mobilemodel->get_flat_basics($id)->row();
			if(!empty($flat)){
				$data = $this->mobilemodel->member_in_flat($id,$this->input->server('HTTP_SOCIETY'))->result();
				echo '<h5 class="blue-grey lighten-5 font-20 bold-500 mem-list-h5 text-center">Members In Flat ';
				if(!empty($flat->name)){
					echo $flat->name.' - ';
				}
				echo h($flat->flat_no).'</h5>';
				foreach ($data as $key => $value) {
					echo '<div class="mt10 row"><div class="col s4">';
					if(empty($value->picture)){
						echo '<img class="materialboxed responsive-img" src="'.base_url('assets/images/user_image.png').'">';
					} else {
						echo '<img class="materialboxed responsive-img" src="'.base_url('assets/members_picture/'.h($value->picture)).'">';
					}
					echo '</div><div class="col s8"><p class="font-16"><strong>'.h($value->firstname).' '.h($value->lastname).'</strong></p><p>'.h($value->mobile_no).'</p>';
					if(!empty($value->email)){
						echo '<p>'.h($value->email).'</p>';
					}
					if($value->owner_tenant==='1'){
						echo '<p>Flat Relation : Owner</p>';
					} else {
						echo '<p>Flat Relation : 
						Tenant</p>';
					}
					echo '</div></div>';
				}
				unset($value);
			}
		}
	}
	public function addmembers(){
		header('Content-Type: application/json');
		if($this->input->server('HTTP_SOCIETY')){
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('mo', 'Mobile Number', 'trim|required|integer|exact_length[10]|is_unique[users.mobile_no]',array('integer'=>'Please enter correct mobile number','exact_length'=>'Please enter correct 10 digit mobile number','is_unique'=>'An account with this %s already exists'));
			$this->form_validation->set_rules('fn', 'First Name', 'trim|required|alpha_numeric|max_length[20]');
			$this->form_validation->set_rules('ln', 'Last Name', 'trim|required|alpha_numeric|max_length[20]');
			$this->form_validation->set_rules('owner_type', 'Owner / Tenant', 'required');
			if($this->form_validation->run()){
				$salt = md5('ZXdgf5275dfsdg'.time().'dsgjhsd545DSAd');
				$pw = substr(md5('sdglmdflhzdgf5275dfsdg'.mt_rand().'zdkngji446u7e8ryu 5e8y'), 15, 6);
				$hash = hash('sha256', $pw.$salt);
				$token = hash('sha256', mt_rand().$salt.time());
				$data = array(
					'firstname' => $this->input->post('fn'),
					'lastname' => $this->input->post('ln'),
					'mobile_no' => $this->input->post('mo'),
					'password' => $hash,
					'salt' => $salt,
					'token' => $token,
					'phone_verified' => 1
				);
				$this->load->model('user');
				$code = substr($salt,10,6);
				$owner_tenant = 2;
				if($this->input->post('owner_type')==='1'){
					$owner_tenant = 1;
				}
				$flat = $this->mobilemodel->get_user_flats($this->input->server('HTTP_USER_ID'),$this->input->server('HTTP_SOCIETY'))->result();
				if($this->user->user_adding_user($data,$code,array('added_by'=>$this->input->server('HTTP_USER_ID'),'society_id'=>$this->input->server('HTTP_SOCIETY')),$flat,$owner_tenant)){
					exec('php index.php backgroundjobs exec_u_by_u_notif '.$data["firstname"].' '.$data["mobile_no"].' '.$pw.' '.$this->input->server("HTTP_USER_ID"));
					echo json_encode(array('success'=>'1'));
				} else {
					echo json_encode(array('error' => 'Something Went Wrong! Please Try Again'));
				}
			} else {
				echo json_encode(array(
					'fn'=>form_error('fn'),
					'ln'=>form_error('ln'),
					'mo'=>form_error('mo'),
					'ot'=>form_error('owner_type')
				));
			}
		} else {
			echo json_encode(array('error' => 'You Dont Belong To Any Society!'));
		}
	}
}