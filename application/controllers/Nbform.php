<?php
class Nbform extends Requestcontrol {
	public function adding(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('notice', 'Notice', 'required',array('required'=>'Notice Cannot Be Empty!'));
		if($this->form_validation->run()){
			$this->load->model('noticeboard_model');
			$data = array('notice_text'=>$this->input->post('notice'),'society_id'=>$this->society);
			$users = $this->get_users_pushToken();
			$this->noticeboard_model->add($data);
			sendNotification('Your Society Admin Has Added A New Notice On Notice Board',$users,array('ref'=>'nb'));
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('error'=>form_error('notice')));
		}
	}
	public function edit_notice(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('notice', 'Notice', 'required',array('required'=>'Notice Cannot Be Empty!'));
		$this->form_validation->set_rules('id', 'Notice', 'required|is_natural_no_zero',array('required'=>'Notice Cannot Be Empty!','is_natural_no_zero'=>'Notice Cannot Be Empty!'));
		if($this->form_validation->run()){
			$this->load->model('noticeboard_model');
			$data = array('notice_text'=>$this->input->post('notice'));
			$this->noticeboard_model->update($data,$this->input->post('id'),$this->society);
			echo json_encode(array('success'=>1));
		} else {
			if(form_error('notice')!=''){
				echo json_encode(array('error'=>form_error('notice')));
			} else {
				echo json_encode(array('error'=>form_error('id')));
			}
		}
	}
	public function delete($id){
		if(is_valid_number($id)){
			$this->load->model('noticeboard_model');
			$this->noticeboard_model->remove_notice($id,$this->society);
		}
	}
}