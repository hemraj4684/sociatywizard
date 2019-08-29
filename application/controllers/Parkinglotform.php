<?php
class Parkinglotform extends Requestcontrol {
	public function form_common(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('pplate', 'Vehicle Number Plate', 'required|max_length[30]');
		$this->form_validation->set_rules('ptype', 'Vehicle Type', 'required');
		$this->form_validation->set_rules('plabel', 'Parking Slot Label', 'max_length[30]');
		$this->form_validation->set_rules('pmodel', 'Vehicle Model', 'max_length[25]');
		$this->form_validation->set_rules('flat_id', 'Flat', 'is_natural_no_zero',array('is_natural_no_zero'=>'Please Select A Valid Flat'));
	}
	public function add(){
		$this->form_common();
		if($this->form_validation->run()){
			$data = array('slot_label'=>$this->input->post('plabel'),'vehicle_model'=>$this->input->post('pmodel'),'no_plate'=>$this->input->post('pplate'),'society_id'=>$this->society,'vehicle_type'=>1);
			if($this->input->post('ptype')==='2'){
				$data['vehicle_type'] = 2;
			}
			if($this->input->post('flat_id')){
				$this->flat_validation($this->input->post('flat_id'));
				$data['flat_id'] = $this->input->post('flat_id');
			}
			$this->load->model('parkinglotmodel');
			$this->parkinglotmodel->add($data);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('pplate'=>form_error('pplate'),'ptype'=>form_error('ptype'),'plabel'=>form_error('plabel'),'pmodel'=>form_error('pmodel'),'flat_id'=>form_error('flat_id')));
		}
	}
	public function delete($id=''){
		if(is_valid_number($id)){
			$this->load->model('parkinglotmodel');
			$this->parkinglotmodel->delete($id,$this->society);
			echo json_encode(array('success'=>'1'));
		} else {
			echo json_encode(array('err'=>'Please Select A Vehicle'));
		}
	}
	public function edit(){
		$this->form_common();
		if($this->form_validation->run()){
			$data = array('slot_label'=>$this->input->post('plabel'),'vehicle_model'=>$this->input->post('pmodel'),'no_plate'=>$this->input->post('pplate'),'society_id'=>$this->society,'vehicle_type'=>1);
			if($this->input->post('ptype')==='2'){
				$data['vehicle_type'] = 2;
			}
			if($this->input->post('flat_id')){
				$this->flat_validation($this->input->post('flat_id'));
				$data['flat_id'] = $this->input->post('flat_id');
			} else {
				$data['flat_id'] = NULL;
			}
			$this->load->model('parkinglotmodel');
			$this->parkinglotmodel->edit($this->input->post('id'),$this->society,$data);
			echo json_encode(array('success'=>'1'));
		} else {
			echo json_encode(array('pplate'=>form_error('pplate'),'ptype'=>form_error('ptype'),'plabel'=>form_error('plabel'),'pmodel'=>form_error('pmodel'),'flat_id'=>form_error('flat_id')));
		}
	}
}