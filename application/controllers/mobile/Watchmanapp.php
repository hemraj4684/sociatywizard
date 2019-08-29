<?php
class Watchmanapp extends WatchmanParent {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){header('HTTP/1.0 403 Forbidden');exit();}
	}
	public function new_data(){
		header('Content-Type: application/json');
		if(isset($_POST) && !empty($_POST)){
			$name = '';
			$no = '';
			$po = '';
			if($this->input->post('vname')){
				$name = $this->input->post('vname');
			}
			if($this->input->post('vno')){
				$no = $this->input->post('vno');
			}
			if($this->input->post('vpo')){
				$po = $this->input->post('vpo');
			}
			$data = array(
				'visitor_name' => $name,
				'visitor_number' => $no,
				'visitor_purpose' => $po,
				'society_id' => $this->society,
				'user_id' => $this->user_id
			);
			$log = array(
				'date_of_entry' => date('Y-m-d H:i:s')
			);
			if($this->input->post('c_date')){
				$log['date_of_entry'] = date('Y-m-d H:i:s',strtotime('+5 hours +30 minutes',strtotime($this->input->post('c_date'))));
			}
			if($this->input->post('vflat')){
				$this->load->helper('admin_helper');
				if(is_valid_number($this->input->post('vflat'))){
					$data['visitor_flat'] = $this->input->post('vflat');
				}
			}
			if(isset($_FILES,$_FILES['file']['name'])){
				if(!is_dir('assets/visitors/'.$this->society)) {
				    mkdir('assets/visitors/'.$this->society, 0777, TRUE);
				}
				$config['upload_path'] = 'assets/visitors/'.$this->society;
				$config['max_size'] = 2048;
				$config['allowed_types'] = 'jpg|png|jpeg';
		    	$config['encrypt_name'] = TRUE;
		    	$this->load->library('upload', $config);
				if($this->upload->do_upload('file')){
					$data['visitor_image'] = $this->upload->data()['file_name'];
				}
			}
			if($this->watchmandata->add_one($data,$log)){
				echo json_encode(array('success'=>'1'));
				exit();
			}
			echo json_encode(array('db_err'=>'1'));
		} else {
			echo json_encode(array('empty'=>'1'));
		}
	}
}