<?php
class Invoice extends Requestcontrol {
	public function update(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('bill_id', 'Bill', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('due_date', 'Due Date', 'trim|required');
		$this->form_validation->set_rules('fine', 'Fine', 'is_natural');
		if($this->form_validation->run()){
			$date = NULL;
			if(!is_valid_date($this->input->post('due_date'),$date)){
				echo json_encode(array('due_date'=>'Invalid Due Date'));
				exit();
			}
			$first = [];
			$first['due_date'] = $date;
			$first['fine'] = 0;
			$old = 0;
			if($this->input->post('fine') > 0){
				$first['fine'] = $this->input->post('fine');
			}
			if($this->input->post('fine_old') > 0){
				$old = $this->input->post('fine_old');
			}
			$this->load->model('invoicemodel');
			$this->invoicemodel->update_invoice($this->input->post('bill_id'),$first,$old,$first['fine'],$this->session->soc);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array(
				'due_date' => form_error('due_date'),
				'bill' => form_error('bill_id'),
				'fine' => form_error('fine')
				));
		}
	}
	public function create(){
		$this->form_validation->set_error_delimiters('*', '*');
		$this->form_validation->set_rules('month', 'Month', 'trim|required|is_natural_no_zero|less_than[13]',array('is_natural_no_zero'=>'Please Select Bill Month'));
		$this->form_validation->set_rules('year', 'Year', 'trim|required|is_natural_no_zero|exact_length[4]',array('is_natural_no_zero'=>'Please Select A Year'));
		$this->form_validation->set_rules('due_date', 'Due Date', 'required');
		$this->form_validation->set_rules('to_month', 'Bill Month Upto', 'is_natural|less_than[13]');
		$this->form_validation->set_rules('fine', 'Fine', 'is_natural');
		$this->form_validation->set_rules('group', 'Bill Group', 'required|is_natural_no_zero',array('is_natural_no_zero'=>'Please Select A Valid Bill Group'));
		if($this->form_validation->run()){
			if(!is_valid_date($this->input->post('due_date'),$date)){
				echo json_encode(array('due_date'=>'Invalid Due Date'));
				exit();
			}
			$inv_month =  $this->input->post('year').'-'.$this->input->post('month').'-01';
			$inv_mF=date('F',strtotime($inv_month));
			$first = [];
			$diff = 1;
			$second = array();
			$final_t = 0;
			if($this->input->post('to_month')>0){
				$arr = $this->input->post('to_year').'-'.$this->input->post('to_month').'-01';
				$str_to = strtotime($inv_month);
				$str_from = strtotime($arr);
				if($str_to>=$str_from){
					echo json_encode(array('to_month'=>'Bill Month Cannot Be Less OR Equal To Bill Month Upto'));
					exit();
				} else {
					$year1 = date('Y', $str_to);
					$year2 = date('Y', $str_from);
					$month1 = date('m', $str_to);
					$month2 = date('m', $str_from);
					$diff = (($year2 - $year1) * 12) + ($month2 - $month1) + 1;
				}
			} else {
				$arr = NULL;
			}
			$principal = 0;
			$this->load->model(array('invoicemodel','flatmodel'));
			$group_id = $this->input->post('group');
			$p_amounts = $this->invoicemodel->get_groupwise_particulars($group_id)->result();
			foreach ($p_amounts as $key => $value) {
				$second[$key]['particulars'] = $value->name;
				$second[$key]['amount'] = $value->amount;
				$final_t = $final_t + $value->amount;
				$principal = $principal+$value->amount;
			}
			unset($value);
			$final_t = $final_t*$diff;
			$arrear_cr = array();
			$arrear_db = array();
			$data = array();
			$flats = $this->flatmodel->flat_id_of_bill($group_id)->result();
			if(empty($flats)){
				echo json_encode(array('error'=>'*No Flat Belong To This Group*'));
				exit();
			}
			$send_to = array();
			foreach ($flats as $flat) {
				array_push($send_to, $flat->flat_id);
			}
			unset($flat);

			$numbers = $this->flatmodel->search_flats_table($send_to,'id,owner_number,owner_name')->result();
			$data = $this->invoicemodel->flatwise_collection($send_to,0,0,$this->society)->result();
			$arr_flats = array();
			foreach ($data as $key => $value) {
				if($value->total_received!==$value->total_paid || $value->d_arrear>0 || $value->c_arrear>0){
					if($value->total_received>$value->total_paid){
						$total = ((($value->total_received-$value->total_paid)-$value->d_arrear)-$value->c_arrear);
						$arrear_cr[$value->flat_id] = $total;
						array_push($arr_flats,$value->flat_id);
					} else {
						$total = $value->c_arrear+($value->total_paid-$value->total_received-$value->d_arrear);
						$arrear_db[$value->flat_id] = $total;
						array_push($arr_flats,$value->flat_id);
					}
				}
			}
			unset($value);
			$arr_flats = array_unique($arr_flats);
			$data1 = array();
			if(!empty($arr_flats)){
				$data1 = $this->invoicemodel->flatwise_collection($arr_flats,0,1,$this->session->soc)->result();
			}
			foreach ($data1 as $key => $value) {
				if(isset($arrear_cr[$value->flat_id])){
					$arrear_cr[$value->flat_id] = abs($arrear_cr[$value->flat_id])-$value->c_arrear;
				}
				if(isset($arrear_db[$value->flat_id])){
					$arrear_db[$value->flat_id] = $arrear_db[$value->flat_id]-$value->d_arrear;
				}
			}
			unset($value);
			$fine = 0;
			if($this->input->post('fine')>0){
				$fine = $this->input->post('fine');
			}
			$society_id = $this->session->soc;
			$sms_arr = array();
			foreach($send_to as $key => $value) {
				$add_total = $final_t;
				$arr_db = '0.00';
				$arr_cr = '0.00';
				if(isset($arrear_cr[$value])){
					$arr_cr = $arrear_cr[$value];
					$add_total = $add_total+$arr_cr;
				}
				if(isset($arrear_db[$value])){
					if($arrear_db[$value]<$final_t){
						$arr_db = $arrear_db[$value];
						$add_total = $add_total-$arr_db;
					} else if($arrear_db[$value]==$final_t) {
						$arr_db = '0.00';
						$add_total = 0;
					} else {
						$arr_db = $arrear_db[$value];
						$add_total = 0;
					}
				}
				$add_total = $add_total + $fine;
				foreach ($numbers as $nk => $val) {
					if($val->id===$value){
						$sms = rawurlencode("Dear $val->owner_name,%nYour bill for the month of $inv_mF has been dispatched.%nAmount : Rs. $add_total/-%nDue Date : $date%nCheck SocietyWizard app for more details.");
						array_push($sms_arr, sms_url($val->owner_number,$sms));
						unset($numbers[$nk]);
					}
				}
				unset($val);
				array_push($first,array('invoice_month'=>$inv_month,'flat_id'=>$value,'principal_amount'=>$principal,'total_amount'=>$add_total,'due_date'=>$date,'advance_month'=>$arr,'credit_arrear'=>abs($arr_cr),'debit_arrear'=>abs($arr_db),'fine'=>$fine,'society_id'=>$society_id));
			}
			unset($value);
			$this->invoicemodel->insert_invoice($first,$second);
			$this->load->library('redisstore');
			$this->redisstore->set_timer('inv:'.$this->society,json_encode($sms_arr),300);
			exec('php index.php backgroundjobs send_invoice_sms '.$this->society);
			$this->session->set_flashdata('invoice_created','<div style="display:block" class="card-panel white-text green success-area"><i class="fa fa-thumbs-o-up"></i> Bill Has Been Successfully Sent</div>');
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array(
				'due_date' => form_error('due_date'),
				'to_month' => form_error('to_month'),
				'month' => form_error('month'),
				'arr_db' => form_error('arr_db'),
				'arr_cr' => form_error('arr_cr'),
				'fine' => form_error('fine'),
				'group' => form_error('group')
				));
		}
	}
	public function get_group_ajax($id = 0){
		if(is_valid_number($id)){
			$this->load->model('Invoicemodel');
			$data = $this->Invoicemodel->get_groupwise_particulars($id)->result();
			foreach ($data as $key => $value) {
				echo '<tr><td>'.++$key.') '.h($value->name).'</td><td><input type="hidden" class="invoice-p-amount input-text" value="'.h($value->amount).'"><i class="fa fa-rupee"></i> '.h($value->amount).'/-</td></tr>';
			}
		}
	}
	public function update_bill_details(){
		$paid = $this->input->post('paid');
		if($paid!=='1' && $paid!=='2'){
			echo json_encode(array('paid'=>'Please Select Payment Status'));
			exit();
		}
		if($paid==='1'){
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('paid', 'Payment Status', 'required|is_natural_no_zero',array('is_natural_no_zero'=>'Please Select Valid %s'));
			$this->form_validation->set_rules('amt_paid', 'Total Amount Paid', 'numeric');
			$this->form_validation->set_rules('dop', 'Date Of Payment', 'required');
		}
		$this->form_validation->set_rules('id', 'Bill', 'required|is_natural_no_zero', array('required'=>'The bill is not valid','is_natural_no_zero'=>'The bill is not valid'));
		$p_method = $this->input->post('p_method');
		$date_v = false;
		$v_date = NULL;
		if($this->input->post('cq_date') && !empty($this->input->post('cq_date'))){
			$date_v = is_valid_date($this->input->post('cq_date'),$v_date);
			if(!$date_v){
				echo json_encode(array('date'=>'The Date You Entered Is Not Valid'));
				exit();
			}
		}
		if($paid==='1'){
			$this->form_validation->set_rules('p_method', 'Payment Method', 'trim|required|is_natural_no_zero',array('is_natural_no_zero'=>'Please Select Valid %s'));
			if($p_method!=='1' && $p_method!=='2' && $p_method!=='3'){
				echo json_encode(array('p_method'=>'Please Select Payment Method'));
				exit();
			}
		}
		if($this->form_validation->run()){
			$dop_v = is_valid_date($this->input->post('dop'),$v_dop);
			if($paid==='1'){
				if(!$dop_v){
					echo json_encode(array('dop_err'=>'The Date Of Payment You Have Entered Is Not Valid'));
					exit();
				}
			}
			if($paid==='1'){
				$data = array('note'=>$this->input->post('note'),'amount_paid'=>'0.00','date_of_payment'=>$v_dop,'cheque_amount'=>$this->input->post('amt_paid'),'payment_method'=>$p_method);
				if($p_method==='2'){
					$data['cheque_no'] = $this->input->post('cq_no');
					$data['cheque_status'] = 2;
					$data['is_paid'] = 3;
					if($this->input->post('cheque_success')){
						$data['cheque_status'] = 1;
						$data['is_paid'] = 1;
					}
					if($date_v){$data['cheque_date'] = $v_date;}
				} else {
					$data['is_paid'] = 1;
					$data['cheque_no'] = '';
					$data['cheque_status'] = 3;
					$data['cheque_date'] = NULL;
				}
				if($p_method !=='2' && $this->input->post('amt_paid') && !empty($this->input->post('amt_paid'))){
					$data['amount_paid'] = $this->input->post('amt_paid');
				}
			} else {
				$data = array('is_paid'=>2,'cheque_status'=>3,'note'=>$this->input->post('note'),'amount_paid'=>'0.00','cheque_amount'=>'0.00','payment_method'=>4,'cheque_no'=>'','cheque_date'=>NULL,'amount_paid'=>'0.00','date_of_payment'=>NULL);
			}
			$this->load->model('invoicemodel');
			$ie_entry = array();
			if($data['is_paid']==1){
				$ie_entry = array('amount'=>$this->input->post('amt_paid'),'date_of_payment'=>$v_dop,'payment_method'=>$p_method,'cheque_no'=>$this->input->post('cq_no'),'added_by'=>$this->session->user,'society_id'=>$this->society,'trans_type'=>1,'bill_id'=>$this->input->post('id'));
			}
			$this->invoicemodel->update_details($data,$this->input->post('id'),$this->input->post('amt_paid'),$this->session->soc,$ie_entry);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('id'=>form_error('id'),'paid'=>form_error('paid'),'amt_paid'=>form_error('amt_paid'),'p_method'=>form_error('p_method'),'dop_err'=>form_error('dop')));
		}
	}
	public function delete_bill(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id[]', 'Bill', 'required');
		if($this->form_validation->run()){
			$this->load->model('invoicemodel');
			if($this->invoicemodel->verify_bill_belongs_to_society($this->input->post('id'),$this->society)->num_rows() === count($this->input->post('id'))){
				$this->invoicemodel->delete_bills($this->input->post('id'));
				echo json_encode(array('success'=>1));
			} else {
				echo json_encode(array('err'=>'Something Went Wrong. Please Refresh The Page And Try Again.'));
			}
		} else {
			echo json_encode(array('err'=>'Please Select A Bill To Delete'));
		}
	}
	public function multiple_payments(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id[]', 'Bill', 'required');
		if($this->form_validation->run()){
			$this->load->model(array('invoicemodel','flatmodel'));
			$verify = $this->invoicemodel->get_flat_id_from_invoice($this->input->post('id'),$this->society);
			if($verify->num_rows()===count($this->input->post('id'))){
				$flats = $verify->result();
				$flat_id = array();
				$ie_arr = array();
				$arr = array();
				$dop = date('Y-m-d');
				foreach ($flats as $flat) {
					array_push($flat_id,$flat->flat_id);
				}
				unset($flat);
				$f_detail = $this->flatmodel->multipayment_pre_data($this->input->post('id'))->result();
				if(!empty($f_detail)){
					foreach ($f_detail as $value) {
						if(!empty($value->wing_name)){
							$arr = array('giver_taker'=>'Flat No : '.$value->wing_name.'-'.$value->flat_no);
						} else {
							$arr = array('giver_taker'=>'Flat No : '.$value->flat_no);
						}
						$arr['bill_id'] = $value->id;
						$arr['amount'] = $value->total_amount;
						$arr['payment_method'] = 3;
						$arr['trans_type'] = 1;
						$arr['society_id'] = $this->society;
						$arr['added_by'] = $this->session->user;
						$arr['date_of_payment'] = $dop;
						array_push($ie_arr,$arr);
					}
					unset($value);
					$pay = array();
					$pay['is_paid'] = 1;
					$pay['payment_method'] = 3;
					$pay['date_of_payment'] = $dop;
					$pay['society_id'] = $this->society;
					$pay['cheque_status'] = 3;
					$this->invoicemodel->update_details_multiple($pay,$ie_arr,$this->input->post('id'));
				}
				echo json_encode(array('success'=>1));
			} else {
				echo json_encode(array('err'=>'Something went wrong. Please refresh the page and try again.'));
			}
		} else {
			echo json_encode(array('err'=>'Please select atleast one bill'));
		}
	}
	public function remove_late_interest($id=0){
		if(is_valid_number($id)){
			$this->load->model('invoicemodel');
			if($this->invoicemodel->verify_bill_belongs_to_society($id,$this->society)->num_rows() === 1){
				$this->invoicemodel->remove_late_interest($id,$this->society);
			}
		}
	}
}