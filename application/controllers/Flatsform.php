<?php
class Flatsform extends Requestcontrol {
	public function add(){
		$this->load->model('user');
		// $user = $this->user->check_number_exists($this->input->post('contact'),'id,firstname as fn,lastname as ln,picture as pic');
		// if($user->num_rows()==1){
		// 	$row = $user->row();
		// 	echo json_encode(array('exist'=>array('id'=>$row->id,'fn'=>h($row->fn),'ln'=>h($row->ln),'pic'=>h($row->pic))));
		// 	exit();
		// }
		// exit();
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('flat_no', 'Flat No', 'required');
		$this->form_validation->set_rules('flat_wing', 'Flat Wing', 'is_natural');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('contact', 'Mobile Number', 'required|exact_length[10]|is_natural');
		$this->form_validation->set_rules('owner_fn', 'Owner Firstname', 'trim|required|max_length[25]');
		$this->form_validation->set_rules('owner_ln', 'Owner Lastname', 'trim|required|max_length[24]');
		if($this->form_validation->run()){
			$data = array('flat_no'=>$this->input->post('flat_no'),'owner_name'=>$this->input->post('owner_fn').' '.$this->input->post('owner_ln'),'sq_foot'=>$this->input->post('sq_foot'),'intercom'=>$this->input->post('intercom'),'society_id'=>$this->society,'owner_number'=>$this->input->post('contact'),'status'=>1);
			if($this->input->post('status')==='2'){
				$data['status'] = 2;
			}
			if($this->input->post('flat_wing') > 0){
				$data['flat_wing'] = $this->input->post('flat_wing');
			}
			$this->load->model('flatmodel');
			if($this->flatmodel->flat_exists($this->input->post('flat_no'),$this->input->post('flat_wing'),$this->society)){
				echo json_encode(array('flat_no'=>'Flat No Already Exist!'));
				exit();
			}
			if(!$this->input->post('flat_wing') || $this->flatmodel->verify_block($this->input->post('flat_wing'),$this->society)){
				$this->flatmodel->add($data);
				echo json_encode(array('success'=>1));
			} else {
				echo json_encode(array('flat_wing'=>'You are not authenticated'));
			}
		} else {
			echo json_encode(array('flat_no'=>form_error('flat_no'),'flat_wing'=>form_error('flat_wing'),'no_err'=>form_error('contact'),'status_err'=>form_error('status'),'owner_fn_err'=>form_error('owner_fn'),'owner_ln_err'=>form_error('owner_ln')));
		}
	}
	public function edit(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('flat_no', 'Flat No', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('id', 'Flat', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('flat_wing', 'Flat Wing', 'is_natural');
		$this->form_validation->set_rules('contact', 'Mobile Number', 'required|exact_length[10]|is_natural');
		$this->form_validation->set_rules('owner', 'Owner Name', 'required|max_length[50]');
		if($this->form_validation->run()){
			$this->flat_validation($this->input->post('id'));
			$data = array('flat_no'=>$this->input->post('flat_no'),'owner_name'=>$this->input->post('owner'),'sq_foot'=>$this->input->post('sq_foot'),'intercom'=>$this->input->post('intercom'),'status'=>1);
			if($this->input->post('flat_wing') > 0){
				$data['flat_wing'] = $this->input->post('flat_wing');
			} else {
				$data['flat_wing'] = NULL;
			}
			if($this->input->post('status')==='2'){
				$data['status'] = 2;
			}
			if(empty($this->input->post('contact'))){
				$data['owner_number'] = NULL;
			} else {
				$data['owner_number'] = $this->input->post('contact');
			}
			$this->load->model('flatmodel');
			$before = $this->flatmodel->flats_table($this->input->post('id'),'flat_wing')->row();
			if(empty($before->flat_wing) && !empty($data['flat_wing'])){
				$do = 1;
			} else if(!empty($before->flat_wing) && empty($data['flat_wing'])){
				$do = 2;
			} else if(!empty($before->flat_wing) && !empty($data['flat_wing'])){
				$do = 3;
			} else {
				$do = 4;
				// do nothing
			}
			$this->flatmodel->edit($data,$this->input->post('id'),$this->session->soc,$do,$before->flat_wing);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('flat_no'=>form_error('flat_no'),'flat_wing'=>form_error('flat_wing'),'contact'=>form_error('contact'),'status_err'=>form_error('status'),'owner_err'=>form_error('owner')));
		}
	}
	public function members_summary(){
		$this->load->model('flatmodel');
		$wings = $this->flatmodel->get_wings($this->session->soc)->result();
		$data = [];
		foreach ($wings as $key => $value) {
			$ro = $this->flatmodel->all_user_and_flat($value->id);
			array_push($data, array('id'=>h($value->id),'wing'=>h($value->name),'total'=>$ro->num_rows()));
		}
		unset($value);
		echo json_encode(array('result'=>$data));
	}
	public function flats_summary($id=0){
		$this->load->model('flatmodel');
		if(!is_valid_number($id)){$id=0;}
		$data = $this->flatmodel->get_flat_status($id,$this->session->soc)->result();
		$res = [];
		$inc = [];
		foreach($data as $key => $value) {
			if(isset($res[$value->name])){
				++$inc[$value->name];
				$res[$value->name] = array('id'=>$value->id,'total'=>$inc[$value->name]);
			} else {
				$res[$value->name] = array();
				$inc[$value->name] = 0;
				if(!empty($value->status)){
					$inc[$value->name] = 1;
				}
				$res[$value->name] = array('id'=>$value->id,'total'=>$inc[$value->name]);
			}
		}
		unset($value);
		echo json_encode(array('result'=>$res));
	}
	public function blocks_summary($id=0){
		$id = trim($id,',');
		$ex_id = explode(',', $id);
		foreach ($ex_id as $key => $value) {
			if(!is_valid_number($value)){exit();}
		}
		$this->load->model('flatmodel');
		$data = $this->flatmodel->get_block_status($this->session->soc)->result();
		$wing_wise = [];
		foreach ($data as $key => $value) {
			if(isset($wing_wise[$value->wing])){
				array_push($wing_wise[$value->wing], $value);
			} else {
				if(!empty($value->wing)){
					$wing_wise[$value->wing] = [];
					array_push($wing_wise[$value->wing], $value);
				}
			}
		}
		unset($value);
		$inc = [];
		$res = [];
		foreach ($wing_wise as $key => $value) {
			foreach ($value as $val) {
				if(isset($res[$key][$val->name])){
					++$inc[$key][$val->name];
					$res[$key][$val->name] = array('id'=>$val->id,'total'=>$inc[$key][$val->name],'wing'=>$val->wing);
				} else {
					$inc[$key][$val->name] = 1;
					$res[$key][$val->name] = [];
					$res[$key][$val->name] = array('id'=>$val->id,'total'=>$inc[$key][$val->name],'wing'=>$val->wing);
				}
			}
			unset($val);
		}
		echo json_encode(array('data'=>$res));
	}
	public function pending_cheque_update(){
		$ids = $this->input->post('id');
		$type = $this->input->post('type');
		if(is_array($ids) && ($type==='1' || $type==='4')){
			if(is_valid_numbers($ids)){
				$this->load->model('invoicemodel');
				$this->invoicemodel->update_cheques($ids,$type,$this->session->soc,$this->session->user);
			}
		}
		echo json_encode(array('success'=>1));
	}
	public function search_flat_keyword($var=''){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('var', 'Keyword', 'trim|required|max_length[15]|min_length[1]');
		if($this->form_validation->run()){
			$this->load->model('flatmodel');
			$data = $this->flatmodel->search_flats_autocomplete($this->input->post('var'),$this->session->soc)->result();
			if(!empty($data)){
				echo '<ul class="collection z-depth-1">';
				foreach ($data as $key => $value) {
					$vars='';
					if(!empty($value->name)){
						$vars .= h($value->name).' - ';
					}
					$vars .= h($value->flat_no);
					echo '<li class="collection-item pointer-cursor drop-f-item" data-value="'.$vars.'" data-id="'.h($value->id).'">';
					echo $vars.'<span class="secondary-content black-text"><i class="fa fa-plus"></i></span></li>';
				}
				unset($value);
				echo '</ul>';
			}
		}
	}
}