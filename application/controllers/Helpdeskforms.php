<?php
class Helpdeskforms extends Requestcontrol {
	public function insert_reply(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('reply', 'Reply', 'required');
		$this->form_validation->set_rules('id', 'Message', 'required|is_natural_no_zero');
		if($this->form_validation->run()){
			$this->load->model(array('user','helpdesk_model'));
			$check = $this->helpdesk_model->verify_helpdesk($this->input->post('id'),$this->society,',conv_type,sender_id',$help_var);
			if($check){
				if($help_var->conv_type!=='2'){
					$user = $this->user->user_by_id($this->session->user,'firstname,lastname,picture')->row();
					if(!empty($user)){
						$data = array('reply_text'=>$this->input->post('reply'),'admin_id'=>$this->session->user,'parent_id'=>$this->input->post('id'),'reply_from'=>2);
						$this->helpdesk_model->insert_comment($data);
						$this->load->helper(array('helpdesk_helper'));
						reply_view($user->firstname,$user->lastname,$user->picture,date('Y-m-d H:i:s'),2,$this->input->post('reply'),'right');
						$this->send_one_notification($help_var->sender_id,'Your Society Admin Has Sent You A Reply On Helpdesk',array('ref'=>'hdesk','hid'=>$this->input->post('id')));
					}
				}
			}
		}
	}
	public function change_conv_status(){
		$this->form_validation->set_rules('conv_status', 'Status', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('id', 'Message', 'required|is_natural_no_zero');
		if($this->form_validation->run()){
			$this->load->model('helpdesk_model');
			$check = $this->helpdesk_model->verify_helpdesk($this->input->post('id'),$this->session->soc,',conv_type',$help_var);
			if($check){
				if($help_var->conv_type==='1'){
					$this->helpdesk_model->conv_type_change($this->input->post('id'),2);
				} else {
					$this->helpdesk_model->conv_type_change($this->input->post('id'),1);
				}
			}
		}
	}
}