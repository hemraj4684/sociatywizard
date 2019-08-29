<?php
class Watchmanapplogin extends WatchmanParent {
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		header('Content-Type: application/json');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('username', 'Username', 'required|max_length[20]',array('max_length'=>'Invalid Username / Password'));
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');
		if($this->form_validation->run()){
			$this->load->model('watchmandata');
			$query = $this->watchmandata->watchman_data_search_one($this->input->post('username'),'id,password,salt,token,society_id');
			if($query->num_rows()===1){
				$data = $query->row();
				$hash = hash('sha256', $this->input->post('password').$data->salt);
				if($hash===$data->password){
					$this->load->model('flatmodel');
					$flats = $this->flatmodel->get_id_name($data->society_id)->result();
					echo json_encode(array('success'=>'1','society'=>$data->society_id,'token'=>$data->token,'id'=>$data->id,'flats'=>$flats));
				} else {
					echo json_encode(array(
						'uerr' => 'Invalid Username / Password'
					));
				}
			} else {
				echo json_encode(array(
					'uerr' => 'Invalid Username / Password'
				));
			}
		} else {
			echo json_encode(array(
				'uerr' => form_error('username'),
				'perr' => form_error('password'),
			));
		}
	}
}