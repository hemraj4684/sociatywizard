<?php
class Vendersform extends Requestcontrol {
	public function validate_data(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('name', 'Contact Person Name', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('no1', 'Contact Number', 'max_length[16]|is_natural');
		$this->form_validation->set_rules('no2', 'Alternate Contact Number', 'max_length[16]|is_natural');
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[255]');
		$this->form_validation->set_rules('notes', 'Notes', 'trim|max_length[1000]');
		$this->form_validation->set_rules('category', 'Category', 'trim|max_length[50]');
	}
	public function this_data(){
		$data = array(
			'contact_name'=>$this->input->post('name'),
			'address'=>$this->input->post('address'),
			'notes'=>$this->input->post('notes'),
			'category'=>$this->input->post('category'),
			'society_id'=>$this->session->soc,
			'added_by'=>$this->session->user
		);
		if($this->input->post('no2')){
			$data['contact_number_2'] = $this->input->post('no2');
		} else {
			$data['contact_number_2'] = NULL;
		}
		if($this->input->post('no1')){
			$data['contact_number_1'] = $this->input->post('no1');
		} else {
			$data['contact_number_1'] = NULL;
		}
		return $data;
	}
	public function add_new(){
		$this->validate_data();
		if($this->form_validation->run()){
			$this->load->model('vendorsmodel');
			$this->vendorsmodel->add_vendor($this->this_data());
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array(
					'name'=>form_error('name'),
					'no1'=>form_error('no1'),
					'no2'=>form_error('no2'),
					'address'=>form_error('address'),
					'notes'=>form_error('notes'),
					'category'=>form_error('category')
			));
		}
	}
	public function editing($id=0){
		$this->validate_data();
		$this->form_validation->set_rules('id', 'Vendor', 'required|is_natural_no_zero');
		if($this->form_validation->run()){
			$this->load->model('vendorsmodel');
			$this->vendorsmodel->update_vendor($this->this_data(),$this->input->post('id'),$this->session->soc);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array(
					'name'=>form_error('name'),
					'no1'=>form_error('no1'),
					'no2'=>form_error('no2'),
					'address'=>form_error('address'),
					'notes'=>form_error('notes'),
					'category'=>form_error('category')
			));
		}
	}
	public function remove_vendor($id=0){
		if(is_valid_number($id)){
			$this->load->model('vendorsmodel');
			$this->vendorsmodel->remove_vendor($id,$this->session->soc);
		}
	}
}