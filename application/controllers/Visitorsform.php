<?php
class Visitorsform extends Requestcontrol {
	public function search_by_date(){
		header('Content-Type: application/json');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('date_from', 'Date Of Visit', 'required');
		if($this->form_validation->run()){
			if(is_valid_date($this->input->post('date_from'),$date_from)){
				$date_upto = '';
				if($this->input->post('date_upto') && is_valid_date($this->input->post('date_upto'),$date_upto)){
					$start = strtotime($date_from);
					$end = strtotime($date_upto);
					if($start>=$end){
						echo json_encode(array('to_err'=>'Date Upto must be greater than Date Of Visit'));
						exit();
					}
				}
				$this->load->model('watchmandata');
				$data = $this->watchmandata->search_by_date($this->society,$date_from,$date_upto)->result();
				$this->session->vfrom = $date_from;
				$this->session->vupto = $date_upto;
				echo json_encode(array('success'=>1,'data'=>$this->load->view('visitors/data_table',array('data'=>$data),true)));
			}
		} else {
			echo json_encode(array('to_err'=>'Please Enter Date Of Visit'));
		}
	}
	public function update_credentials(){
		header('Content-Type: application/json');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('password', 'Password', 'min_length[4]');
		if($this->form_validation->run()){
			$this->load->model('watchmandata');
			$data = array('username'=>$this->input->post('username'));
			if($this->input->post('password')){
				$salt = random_salt();
				$hash = hash('sha256', $this->input->post('password').$salt);
				$data['salt'] = $salt;
				$data['password'] = $hash;
			}
			if($this->input->post('logout')){
				$data['token'] = hash('sha256', mt_rand().random_salt());
			}
			$this->watchmandata->update_credentials($this->society,$data);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('uerr'=>form_error('username'),'perr'=>form_error('password')));
		}
	}
	public function remove_visitors(){
		header('Content-Type: application/json');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id[]', 'Visitors', 'required|is_natural_no_zero');
		if($this->form_validation->run()){
			$this->load->model('watchmandata');
			$data = $this->watchmandata->data_to_verify_before_delete($this->input->post('id'),$this->society);
			if(count($data)===count($this->input->post('id'))){
				$this->redisstore->set_timer('rmv:'.$this->society,json_encode(array($data,$this->input->post('id'))),300);
				exec('php index.php backgroundjobs remove_visitors '.$this->society);
				echo json_encode(array('success'=>'1'));
			} else {
				echo json_encode(array('iderr'=>'Please refresh the page & try again!'));
			}
		}else{
			echo json_encode(array('iderr'=>'Please Select A Valid Visitor!'));
		}
	}
	public function new_visitor(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('v_name', 'Visitor Name', 'required|max_length[20]');
		$this->form_validation->set_rules('v_contact', 'Visitor Contact', 'max_length[15]');
		$this->form_validation->set_rules('v_purpose', 'Visitor Purpose', 'max_length[30]');
		$this->form_validation->set_rules('v_dov', 'Date Of Visiting', 'required');
		if($this->form_validation->run()){
			$data = array(
				'visitor_name' => $this->input->post('v_name'),
				'visitor_number' => $this->input->post('v_contact'),
				'visitor_purpose' => $this->input->post('v_purpose'),
				'visitor_image' => '',
				'society_id' => $this->society,
				'admin_id' => $this->session->user
			);
			if($this->input->post('flat_id')){
				if(is_valid_number($this->input->post('flat_id'))){
					$data['visitor_flat'] = $this->input->post('flat_id');
				}
			}
			if(isset($_FILES,$_FILES['userfile']['name'])){
				if(!is_dir('assets/visitors/'.$this->society)) {
				    mkdir('assets/visitors/'.$this->society, 0777, TRUE);
				}
				$config['upload_path'] = 'assets/visitors/'.$this->society;
				$config['max_size'] = 2048;
				$config['allowed_types'] = 'jpg|png|jpeg';
		    	$config['encrypt_name'] = TRUE;
		    	$this->load->library('upload', $config);
				if($this->upload->do_upload()){
					$data['visitor_image'] = $this->upload->data()['file_name'];
				}
			}
			$date_entry = '';
			if(is_valid_date($this->input->post('v_dov'),$date_entry)){
				$date_entry = $date_entry.' '.date('H:i:s',strtotime($this->input->post('v_time')));
			}
			$log = array(
				'date_of_entry' => $date_entry
			);
			$this->load->model('watchmandata');
			$this->watchmandata->add_one($data,$log,$last_id);
			echo json_encode(array('success'=>'1', 'data' => array(
				$data['visitor_name'],
				$data['visitor_number'],
				$data['visitor_purpose'],
				$data['visitor_image'],
				date('dS M Y, h:ia',strtotime($date_entry)),
				$last_id,
				$this->input->post('v_flat'),
				$this->society
			)));
		} else {
			echo json_encode(array('nerr'=>form_error('v_name'),'derr'=>form_error('v_dov')));
		}
	}
}