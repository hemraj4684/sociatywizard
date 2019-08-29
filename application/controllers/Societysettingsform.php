<?php
class Societysettingsform extends Requestcontrol {
	public function update_form1(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('name', 'Society Name', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('address', 'Society Address', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('reg_no', 'Society Registration Number', 'trim|max_length[100]');
		$this->form_validation->set_rules('notes', 'Invoice Notes', 'trim|max_length[1000]');
		$this->form_validation->set_rules('interest_number', 'Late Payment Interest Percentage', 'trim|is_natural');
		if($this->form_validation->run()){
			if($this->input->post('interest_number') > 100){
				echo json_encode(array('intr'=>'Please Enter The Value Less Than 100'));
				exit();
			}
			$data = array(
				'society_name'=>$this->input->post('name'),
				'society_address'=>$this->input->post('address'),
				'registration_number'=>$this->input->post('reg_no'),
				'invoice_note'=>$this->input->post('notes'),
				'late_payment_interest'=>$this->input->post('interest_number')
			);
			$this->load->model('societysettingmodel');
			$this->societysettingmodel->update_data($data,$this->society);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('name'=>form_error('name'),'address'=>form_error('address'),'reg_no'=>form_error('reg_no'),'notes'=>form_error('notes'),'intr'=>form_error('interest_number')));
		}
	}
	public function update_form2(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('name', 'Bill Group Name', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('particular[]', 'Particular', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('amount[]', 'Amount', 'trim|required|max_length[10]|numeric|greater_than[0]');
		if($this->form_validation->run()){
			if(count($this->input->post('particular[]')) === count($this->input->post('amount[]'))){
				$this->load->model('societysettingmodel');
				$data = array();
				foreach ($this->input->post('particular[]') as $key => $value) {
					array_push($data, array('name'=>$value,'amount'=>$this->input->post('amount[]')[$key]));
				}
				unset($value);
				$this->societysettingmodel->insert_bill_group(array('name'=>$this->input->post('name'),'society_id'=>$this->society),$data);
				echo json_encode(array('success'=>1));
			} else {
				echo json_encode(array('particular'=>'Something is wrong with amount and particulars'));
			}
		} else {
			echo json_encode(array('name'=>form_error('name'),'particular'=>form_error('particular[]'),'amount'=>form_error('amount[]')));
		}
	}
	public function get_bill_groups(){
		$this->load->model('societysettingmodel');
		$groups = $this->societysettingmodel->my_bill_groups($this->society)->result();
		$ids = [];
		foreach ($groups as $key => $group) {
			array_push($ids,$group->id);
		}
		unset($value);
		$partis = array();
		if(!empty($ids)){
			$partis = $this->societysettingmodel->bill_particulars($ids)->result();
		}
		$data = array();
		foreach ($partis as $key => $value) {
			if(isset($data[$value->group_id])){
				array_push($data[$value->group_id], $value);
			} else {
				$data[$value->group_id] = array();
				array_push($data[$value->group_id], $value);
			}
		}
		unset($value);
		if(!empty($groups)){
		echo '<table class="table bordered highlight data_list"><thead><tr><th>#</th><th>Bill Group Name</th><th>Particulars</th><th class="right-align">Action</th></tr></thead><tbody>';
		foreach ($groups as $key => $value) {
			echo '<tr><th class="valign-top">'.++$key.')</th><td class="valign-top">'.h($value->name).'</td><td>';
			if(isset($data[$value->id])){
				echo '<table class="table bordered">';
				$total = 0.00;
				foreach ($data[$value->id] as $val) {
					$total = $total+$val->amount;
					echo '<tr><td width="50%">'.h($val->name).'</td><td class="right-align"><i class="fa fa-rupee"></i> '.h($val->amount).'/-</td></tr>';
				}
				unset($val);
				echo '<tr><th>Total Amount</th><th class="right-align"><i class="fa fa-rupee"></i> '.$total.'/-</th></tr>';
				echo '</table>';
			}
			echo '</td><td class="valign-top"><button type="button" class="btn right red accent-3 btn-small remove_bill_group" data-id="'.$value->id.'" title="Remove this bill group"><i class="fa fa-close m0"></i></button><span class="right">&nbsp;</span><a href="#" onclick="javascript:void window.open(';
			echo "'";
			echo base_url('societysettings/edit_bill_group/'.$value->id);
			echo "','','width=650,height=550,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;";
			echo '" class="btn right blue btn-small"><i class="fa fa-edit m0"></i></a></td></tr>';
		}
		unset($value);
		echo '</tbody></table>';
		} else {
			echo '<h5 class="center">No Data Available</h5>';
		}
	}
	public function remove_bill($id){
		if(is_valid_number($id)){
			$this->load->model('societysettingmodel');
			$this->societysettingmodel->remove_bill($id,$this->society);
		}
	}
	public function edit_bill_group(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('name', 'Bill Group Name', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('particular[]', 'Particular', 'trim|max_length[100]');
		$this->form_validation->set_rules('id', 'Bill Group', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('amount[]', 'Amount', 'trim|max_length[10]|numeric|greater_than[0]');
		if($this->input->post('new_particular[]')){
			$this->form_validation->set_rules('new_particular[]', 'Particular', 'trim|required|max_length[100]');
		}
		if($this->input->post('new_amount[]')){
			$this->form_validation->set_rules('new_amount[]', 'Amount', 'trim|required|max_length[10]|numeric|greater_than[0]');
		}
		if($this->form_validation->run()){
			$new = array();
			if($this->input->post('new_particular[]') && $this->input->post('new_amount[]')){
				if(count($this->input->post('new_particular[]')) === count($this->input->post('new_amount[]'))){
					foreach ($this->input->post('new_particular[]') as $key => $value) {
						array_push($new, array('name'=>$value,'amount'=>$this->input->post('new_amount[]')[$key]));
					}
					unset($value);
				} else {
					echo json_encode(array('particular'=>'Something is wrong with amount and particularss'));
				}
			}
			if(empty($this->input->post('particular[]')) && empty($this->input->post('new_particular[]'))){
				echo json_encode(array('particular'=>'Particulars cannot be empty'));
				exit();
			}
			if(count($this->input->post('particular[]')) === count($this->input->post('amount[]'))){
				$ids = array();
				if(!empty($this->input->post('particular[]'))){
					foreach ($this->input->post('particular[]') as $key => $value) {
						array_push($ids,$key);
						if(!isset($this->input->post('amount[]')[$key])){
							echo json_encode(array('particular'=>'Something is wrong with amount and particulars'));
							exit();
						}
					}
					unset($value);
				}
				$merged = $ids;
				$exp = array();
				if(!empty($this->input->post('rem_id'))){
					$exp = array_map('intval', explode(',', $this->input->post('rem_id')));
					if(!is_valid_ints($exp)){
						echo json_encode(array('particular'=>'Something is wrong with amount and particularss'));
						exit();
					}
					$merged = array_merge($ids,$exp);
				}
				$this->load->model('societysettingmodel');
				$check = $this->societysettingmodel->verify_bill_particulars($merged,$this->input->post('id'),$this->society);
				if(count($merged) === $check){
					$update = array();
					$parts = $this->input->post('particular[]');
					$amts = $this->input->post('amount[]');
					foreach ($ids as $key => $value) {
						$update[$value] = array('name'=>$parts[$value],'amount'=>$amts[$value]);
					}
					unset($value);
					$this->societysettingmodel->update_group_bill(array('name'=>$this->input->post('name')),$update,$this->input->post('id'),$exp,$new);
					echo json_encode(array('success'=>1));
				} else {
					echo json_encode(array('particular'=>'Something is wrong with amount and particulars'));
				}
			} else {
				echo json_encode(array('particular'=>'Something is wrong with amount and particulars'));
			}
		} else {
			echo json_encode(array('name'=>form_error('name'),'particular'=>form_error('particular[]'),'amount'=>form_error('amount[]'),'new_particular'=>form_error('new_particular[]'),'new_amount'=>form_error('new_amount[]')));
		}
	}
	public function forwarding_to_payment_gateway(){
		$this->load->view('payment_gateway/Crypto.php');
		$this->load->view('payment_gateway/ccavRequestHandler.php');
	}
	public function payment_success(){
		$this->payment_error();
	}
	public function payment_error(){
		if(!verify_admin()){exit();}
		if(isset($_POST["encResp"])){
			$this->load->view('payment_gateway/Crypto.php');
			$workingKey='927C0235026505239CD6FB48E1AD37C1';
			$encResponse=$_POST["encResp"];
			$rcvdString=decrypt($encResponse,$workingKey);
			$order_status="";
			$decryptValues=explode('&', $rcvdString);
			$dataSize=sizeof($decryptValues);
			$months = 0;
			$amount = 0;
			echo "<center>";
			for($i = 0; $i < $dataSize; $i++) 
			{
				$information=explode('=',$decryptValues[$i]);
				if($i==3)	$order_status=$information[1];
				if('merchant_param1'==$information[0]){
		    		$months = $information[1];
		    	}
		    	if('amount'==$information[0]){
		    		$amount=$information[1];
		    	}
			}
			if($order_status==="Success")
			{
				echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful.";
				$this->load->model('societysettingmodel');
				$get = $this->societysettingmodel->get_current_subscription_date($this->society)->row();
				if(!empty($get)){
					if(strtotime($get->subscribed_until) < strtotime(date('Y-m-d 00:00:00'))){
						$upto = date('Y-m-d 00:00:00', strtotime("+".$months." months", strtotime(date('Y-m-d'))));
					} else {
						$upto = date('Y-m-d 00:00:00', strtotime("+".$months." months", strtotime($get->subscribed_until)));
					}
					$this->societysettingmodel->update_subscription(array('subscribed_until' => $upto), $this->society, array('society_id'=>$this->society,'no_of_months'=>$months,'amount'=>$amount));
				}
			}
			else if($order_status==="Aborted")
			{
				echo "<br><h1>Your transaction has been cancelled</h1><h4>Wait until you redirect to other page.</h4>";
			}
			else if($order_status==="Failure")
			{
				echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
			}
			else
			{
				echo "<br>Security Error. Illegal access detected";
			}
			echo "<br><br>";
			echo "</center>";
			header('Refresh: 3;url='.base_url('subscriptionhistory'));
			exit();
		}
	}
	public function flat_and_bill(){
		if(!$this->input->is_ajax_request()){
			exit();
		}
		$this->load->model(array('flatmodel','societysettingmodel'));
		$data = $this->flatmodel->flat_and_bill_full($this->society)->result();
		$bills = $this->societysettingmodel->my_bill_groups($this->society)->result();
		$this->load->view('societysettings/flat_and_bill',array('flats'=>$data,'bills'=>$bills));
	}
	public function update_flat_bill(){
		header('Content-Type: application/json');
		if($this->input->is_ajax_request() && $this->input->post('group') && is_array($this->input->post('group'))){
			$data = $this->input->post('group');
			$flats = array();
			$bills = array();
			$flat_bill = array();
			foreach ($data as $key => $value) {
				array_push($flats, $key);
				array_push($bills, $value);
				if(isset($flat_bill[$value])){
					array_push($flat_bill[$value], $key);
				} else {
					$flat_bill[$value] = array();
					array_push($flat_bill[$value], $key);
				}
			}
			unset($value);
			if(!empty($flats) && count($bills)===count($flats)){
				$this->flat_validation($flats);
				unset($flats);
				$bills = array_unique($bills);
				$this->load->model(array('flatmodel','invoicemodel'));
				$groups = $this->invoicemodel->get_bill_group($this->society)->result();
				if(!empty($groups)){
					$gids = array();
					foreach ($groups as $group) {
						array_push($gids, $group->id);
					}
					unset($group);
					foreach ($bills as $bill) {
						if(!in_array($bill, $gids)){
							echo json_encode(array('error'=>'*Please Refresh The Page And Try Again*'));
							exit();
						}
					}
					unset($bill);
					$response = $this->flatmodel->assign_flat_bill($flat_bill);
					if($response){
						echo json_encode(array('success'=>'1'));
					} else {
						echo json_encode(array('error'=>'*Please Refresh The Page And Try Again*'));
					}
				}
			}
		}
	}
}