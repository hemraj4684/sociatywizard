<?php
class Ie_form extends Requestcontrol {
	public function common_validation(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|greater_than[0]');
		$this->form_validation->set_rules('pm', 'Payment Method', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('date', 'Date', 'required');
		$this->form_validation->set_rules('payee', 'Payee', 'max_length[50]');
		$this->form_validation->set_rules('cheque_no', 'Cheque Number', 'max_length[50]');
	}
	public function add_expense(){
		$this->common_validation();
		$this->form_validation->set_rules('trans', 'Trans', 'required');
		$this->form_validation->set_rules('authorised', 'Expense Authorised By', 'integer|greater_than[0]');
		if($this->form_validation->run()){
			if(is_valid_date($this->input->post('date'),$date)){
				$trans = $this->input->post('trans');
				$e_trans = 2;
				if($trans==='1'){
					$e_trans = 1;
				}
				$this->load->model(array('user','iemodel'));
				$data = array('amount'=>$this->input->post('amount'),'date_of_payment'=>$date,'giver_taker'=>$this->input->post('payee'),'cheque_no'=>$this->input->post('cheque_no'),'added_by'=>$this->session->user,'society_id'=>$this->society,'trans_type'=>$e_trans);
				if(is_valid_number($this->input->post('note'))){
					$data['category_id'] = $this->input->post('note');
				}
				if($this->input->post('pm')==='1'){
					$data['payment_method'] = 1;
				} else if($this->input->post('pm')==='2'){
					$data['payment_method'] = 2;
				} else {
					$data['payment_method'] = 3;
				}
				if($this->input->post('authorised') && $this->user->valid_assoc_member($this->input->post('authorised'),$this->society)){
					$data['authorised_by'] = $this->input->post('authorised');
				}
				$this->iemodel->add_transaction($data);
				echo json_encode(array('success'=>1));
			} else {
				echo json_encode(array('date'=>'Incorrect Date Format'));
			}
		} else {
			echo json_encode(array('amt'=>form_error('amount'),'pm'=>form_error('pm'),'date'=>form_error('date'),'payee'=>form_error('payee'),'cheque'=>form_error('cheque_no')));
		}
	}
	public function edit_trans(){
		$this->common_validation();
		$this->form_validation->set_rules('id', 'Transaction', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('authorised', 'Expense Authorised By', 'integer|greater_than[0]');
		if($this->form_validation->run()){
			if(is_valid_date($this->input->post('date'),$date)){
				$trans = $this->input->post('trans');
				$e_trans = 2;
				if($trans==='1'){
					$e_trans = 1;
				}
				$this->load->model(array('user','iemodel'));
				if($this->iemodel->is_editable_trans($this->input->post('id'),$this->society)){
					$data = array('amount'=>$this->input->post('amount'),'date_of_payment'=>$date,'giver_taker'=>$this->input->post('payee'),'cheque_no'=>$this->input->post('cheque_no'),'category_id'=>NULL);
					if($this->input->post('pm')==='1'){
						$data['payment_method'] = 1;
					} else if($this->input->post('pm')==='2'){
						$data['payment_method'] = 2;
					} else {
						$data['payment_method'] = 3;
					}
					if(is_valid_number($this->input->post('note'))){
						$data['category_id'] = $this->input->post('note');
					}
					if($this->input->post('authorised') && $this->user->valid_assoc_member($this->input->post('authorised'),$this->society)){
						$data['authorised_by'] = $this->input->post('authorised');
					}
					$this->iemodel->update_trans($data,$this->input->post('id'),$this->society);
					echo json_encode(array('success'=>1));
				} else {
					echo json_encode(array('amt'=>'This transaction is not editable'));
				}
			} else {
				echo json_encode(array('date'=>'Incorrect Date Format'));
			}
		} else {
			echo json_encode(array('amt'=>form_error('amount'),'pm'=>form_error('pm'),'date'=>form_error('date'),'payee'=>form_error('payee'),'note'=>form_error('note'),'cheque'=>form_error('cheque_no')));
		}
	}
	public function remove_expense(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id[]', 'Item', 'required|is_natural_no_zero');
		if($this->form_validation->run()){
			$this->load->model('iemodel');
			$verify = $this->iemodel->verify_ie_by_society($this->input->post('id'),$this->society);
			if($verify->num_rows()===count($this->input->post('id'))){
				$this->iemodel->delete_ie($this->input->post('id'),$this->society);
				echo json_encode(array('success'=>1));
			} else {
				echo json_encode(array('err'=>'Something Went Wrong! Please Refresh The Page And Try Again.'));
			}
		} else {
			echo json_encode(array('err'=>'Please Select An Item To Delete'));
		}
	}
	public function expenses_list(){
		$this->load->model('iemodel');
		$data = $this->iemodel->last_30_days_trans($this->society,2)->result();
		$this->load->view('income_expense/expenses_ajax',array('data'=>$data,'type'=>2));
	}
	public function incomes_list(){
		$this->load->model('iemodel');
		$data = $this->iemodel->last_30_days_trans($this->society,1)->result();
		$this->load->view('income_expense/expenses_ajax',array('data'=>$data,'type'=>1));
	}
	public function search_expense_datewise(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('date_from', 'Date From', 'required');
		$this->form_validation->set_rules('date_to', 'Date To', 'required');
		$this->form_validation->set_rules('trans', 'Type', 'required');
		if($this->form_validation->run()){
			if(is_valid_date($this->input->post('date_from'),$from_date)){
				if(is_valid_date($this->input->post('date_to'),$to_date)){
					$start = strtotime($from_date);
					$end = strtotime($to_date);
					if($start<=$end){
						$trans = $this->input->post('trans');
						$e_trans = 2;
						if($trans==='1'){
							$e_trans = 1;
						}
						$this->load->model('iemodel');
						$data = $this->iemodel->date_wise($from_date,$to_date,$e_trans,$this->society)->result();
						echo json_encode(array('data'=>$this->load->view('income_expense/expenses_ajax',array('type'=>$e_trans,'data'=>$data),true)));
					} else {
						echo json_encode(array('to_err'=>'Date To must be greater than Date From'));
					}
				} else {
					echo json_encode(array('to_err'=>'Please Select A Valid Date'));
				}
			} else {
				echo json_encode(array('from_err'=>'Please Select A Valid Date'));
			}
		} else {
			echo json_encode(array('to_err'=>form_error('date_to'),'from_err'=>form_error('date_from')));
		}
	}
	public function search_datewise_report(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('date_from', 'Date From', 'required');
		$this->form_validation->set_rules('date_to', 'Date To', 'required');
		$this->form_validation->set_rules('trans', 'Type', 'required');
		if($this->form_validation->run()){
			if(is_valid_date($this->input->post('date_from'),$from_date)){
				if(is_valid_date($this->input->post('date_to'),$to_date)){
					$start = strtotime($from_date);
					$end = strtotime($to_date);
					if($start<=$end){
						$trans = $this->input->post('trans');
						$e_trans = 2;
						if($trans==='1'){
							$e_trans = 1;
						}
						$this->load->model('reportsmodel');
						$data = $this->reportsmodel->date_wise($from_date,$to_date,$e_trans,$this->society)->result();
						echo json_encode(array('data'=>$this->load->view('reports/expense_table',array('type'=>$e_trans,'data'=>$data),true)));
					} else {
						echo json_encode(array('to_err'=>'Date To must be greater than Date From'));
					}
				} else {
					echo json_encode(array('to_err'=>'Please Select A Valid Date'));
				}
			} else {
				echo json_encode(array('from_err'=>'Please Select A Valid Date'));
			}
		} else {
			echo json_encode(array('to_err'=>form_error('date_to'),'from_err'=>form_error('date_from')));
		}
	}
	public function search_datewise_report_category(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('date_from', 'Date From', 'required');
		$this->form_validation->set_rules('date_to', 'Date To', 'required');
		$this->form_validation->set_rules('trans', 'Type', 'required');
		$this->form_validation->set_rules('category', 'Category', 'required|is_natural_no_zero');
		if($this->form_validation->run()){
			if(is_valid_date($this->input->post('date_from'),$from_date)){
				if(is_valid_date($this->input->post('date_to'),$to_date)){
					$start = strtotime($from_date);
					$end = strtotime($to_date);
					if($start<=$end){
						$this->load->model(array('reportsmodel','iemodel'));
						$valid = $this->iemodel->valid_ie_category($this->input->post('category'),$this->society,$return);
						if($valid){
							$data = $this->reportsmodel->date_wise_category($from_date,$to_date,$this->input->post('category'),$this->society)->result();
							echo json_encode(array('data'=>$this->load->view('reports/expense_table',array('data'=>$data),true)));
						} else {
							echo json_encode(array('cat'=>'Invalid Category'));
						}
					} else {
						echo json_encode(array('to_err'=>'Date To must be greater than Date From'));
					}
				} else {
					echo json_encode(array('from_err'=>'Please Select A Valid Date'));
				}
			} else {
				echo json_encode(array('to_err'=>'Please Select A Valid Date'));
			}
		} else {
			echo json_encode(array('to_err'=>form_error('date_to'),'from_err'=>form_error('date_from'),'cat'=>form_error('category')));
		}
	}
	public function ie_graph(){
		$this->load->model('iemodel');
		$data = $this->iemodel->yearly_data(date('Y'),$this->society);
		if(count($data)===2){
			$array = array('1','2','3','4','5','6','7','8','9','10','11','12');
			$income = array();
			$expense = array();
			foreach ($data[0] as $key => $value) {
				$income[$value->month_val] = $value;
			}
			unset($value);
			foreach ($data[0] as $key => $value) {
				$income[$value->month_val] = $value->amount;
			}
			unset($value);
			foreach ($data[1] as $key => $value) {
				$expense[$value->month_val] = $value->amount;
			}
			unset($value);
			$inc = array();
			$exp = array();
			foreach ($array as $key => $month) {
				if(isset($income[$month])){
					array_push($inc, $income[$month]);
				} else {
					array_push($inc, 0);
				}
				if(isset($expense[$month])){
					array_push($exp, $expense[$month]);
				} else {
					array_push($exp, 0);
				}
			}
			unset($month);
			array_values($income);
			ksort($expense);
			echo json_encode(array($inc,$exp));
		}
	}
	public function new_ie_category(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('name', 'Category Name', 'required|max_length[25]');
		$this->form_validation->set_rules('type', 'Type', 'required');
		if($this->form_validation->run()){
			$this->load->model('iemodel');
			$data = array('name'=>$this->input->post('name'),'society_id'=>$this->society);
			if($this->input->post('type')==='2'){
				$data['c_type'] = 2;
			}
			$id = $this->iemodel->add_category($data);
			echo json_encode(array('success'=>1,'id'=>$id,'name'=>h($this->input->post('name'))));
		} else {
			echo json_encode(array('err'=>form_error('name')));
		}
	}
	public function remove_ie_cat($id=''){
		if(is_valid_number($id)){
			$this->load->model('iemodel');
			$this->iemodel->delete_category($id,$this->society);
		}
	}
	public function edit_ie_category(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('name', 'Category Name', 'required|max_length[25]');
		$this->form_validation->set_rules('id', 'Category', 'required|is_natural_no_zero');
		if($this->form_validation->run()){
			$this->load->model('iemodel');
			$data = array('name'=>$this->input->post('name'));
			$this->iemodel->edit_category($data,$this->society,$this->input->post('id'));
			echo json_encode(array('success'=>1,'id'=>$this->input->post('id'),'name'=>h($this->input->post('name'))));
		} else {
			echo json_encode(array('err'=>form_error('name')));
		}
	}
}