<?php
class Smsgateway extends Requestcontrol {
	public function send_group(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('message', 'Message', 'required|min_length[10]',array('required'=>'Please write your message','min_length'=>'The Message is too short. Write atleast 10 characters.'));
		$numbers = $this->input->post('numbers[]');
		if($numbers && !empty($numbers)){
			foreach ($numbers as $key => $value) {
				if(empty($value)){
					unset($numbers[$key]);
				}
			}
			unset($value);
			if(empty($numbers)){
				echo json_encode(array('user'=>'No numbers selected'));
				exit();
			}
		} else {
			echo json_encode(array('user'=>'Please select atleast one user to send SMS'));
			exit();
		}
		if($this->form_validation->run()){
			$referrer = $this->input->post('url');
			$note = '';
			if(!empty($referrer)){
				$referrer = strtolower($referrer);
				if(substr($referrer,0,5)==='flats'){
					$note = 'Sent From Flats Management Section';
				} else if('registeredmembers'===substr($referrer,0,17)) {
					$c_ref = substr($referrer,18,6);
					if($c_ref==='associ'){
						$note = 'Sent From Registered Members >> Association Members Section';
					} else {
						$note = 'Sent From Registered Members Section';
					}
				} else if('flatbill'===substr($referrer,0,8)){
					$note = 'Sent From Pending Bills Section';
				} else if('admins'===substr($referrer,0,6)){
					$note = 'Sent From Admins Section';
				}
			}
			$nums = array_unique($numbers);
			$num = implode(',', $nums);
	 		promotional_sms($num,$this->input->post('message'),$this->session->user,$this->society,count($nums),$note,true);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('msg'=>form_error('message'),'user'=>form_error('numbers[]')));
		}
	}
	public function send_mb_reminder(){
		if($this->input->post('data')){
			$society = $this->get_society_main_data();
			$sname='';
			if(!empty($society)){
				$sname=$society->society_name;
			}
			$sms_arr = array();
			$db_store = array();
			$total = 0;
			$nums = '';
			foreach ($_POST['data'] as $key => $value) {
				if(isset($value['number'],$value['name'],$value['month'],$value['duedate'],$value['amount'])){
					if(is_valid_mobile_number($value['number'])){
						$msg = "Dear ".substr($value['name'],0,50).",
Payment Reminder : Please pay your Maintenance Bill for the month of ".substr($value['month'],0,20).".
Amount : Rs. ".substr($value['amount'],0,10)."/-
Due Date : ".substr($value['duedate'],0,20)."

Ignore if already paid.
Thank You.
$sname";
						array_push($sms_arr,sms_url($value['number'],rawurlencode($msg)));
						$nums .= $value['number'].',';
						if(strlen($msg)>160){
							if(strlen($msg)>320){
								$total=$total+3;
							} else {
								$total=$total+2;
							}
						} else {
							++$total;
						}
						
					} else {
						echo json_encode(array('err'=>'Something went wrong. Please refresh the page and try again.'));
						exit();
					}
				} else {
					echo json_encode(array('err'=>'Something went wrong. Please refresh the page and try again.'));
					exit();
				}
			}
			unset($value);
			$db_store = array(
				'sender_id'=>$this->session->user,
				'society_id'=>$this->society,
				'notes'=>'Pending maintenence bill reminder',
				'total_message'=>$total,
				'numbers'=>trim($nums,',')
			);
			$this->load->model('smsmodel');
			$this->smsmodel->store_sms($db_store);
			multiRequest($sms_arr);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('err'=>'Please select atleast one person to send reminder'));
		}
	}
}