<?php
class Alertsform extends Requestcontrol {
	public function send_alerts(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('alert_type', 'Alert Type', 'required|in_list[meeting,event]');
		$this->form_validation->set_rules('alert_purpose', 'Alert Purpose', 'required|max_length[40]');
		$this->form_validation->set_rules('alert_date', 'Date', 'required|max_length[15]');
		$this->form_validation->set_rules('alert_time', 'Time', 'required|max_length[7]');
		$this->form_validation->set_rules('alert_venue', 'Venue', 'required|max_length[23]');
		$this->form_validation->set_rules('send_to', 'Send To', 'required|in_list[1,2]');
		if($this->form_validation->run()){
			$this->load->helper('sms_template_helper');
			$type = $this->input->post('alert_type');
			$send_to = $this->input->post('send_to');
			if($type=='meeting') {
				$this->redisstore->set_timer('alert:'.$this->society,json_encode(array(meeting($this->input->post('alert_purpose'),$this->input->post('alert_date'),$this->input->post('alert_time'),$this->input->post('alert_venue')),$send_to)),300);
			} else if($type=='event') {
				$this->redisstore->set_timer('alert:'.$this->society,json_encode(array(event($this->input->post('alert_purpose'),$this->input->post('alert_date'),$this->input->post('alert_time'),$this->input->post('alert_venue')),$send_to)),300);
			}
			exec('php index.php backgroundjobs send_alerts '.$this->society);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array(
				'alert_type'=>form_error('alert_type'),
				'alert_purpose'=>form_error('alert_purpose'),
				'alert_time'=>form_error('alert_time'),
				'alert_date'=>form_error('alert_date'),
				'alert_venue'=>form_error('alert_venue'),
				'send_to'=>form_error('send_to')
			));
		}
	}
}