<?php
class Helpdesk extends Controlusers {
	public function index(){
		
	}
	public function messages($var='general'){
		$this->load->model('helpdesk_model');
		$type = '0';
		if($var==='general'){
			$type = '1';
			$data = $this->helpdesk_model->get_helpdesk_by_type($type,$this->society)->result();
		} else if($var=='complaints'){
			$type = '2';
			$data = $this->helpdesk_model->get_helpdesk_by_type($type,$this->society)->result();
		} else if($var=='closed'){
			$type = '3';
			$data = $this->helpdesk_model->get_closed_helpdesk($this->society)->result();
		}
		if($type!=='0'){
			$this->css = dt_css();
			$this->load->view('header');
			$counts = $this->helpdesk_model->helpdesk_counters($this->society);
			$this->load->view('helpdesk/index',array('data'=>$data,'counts'=>$counts));
			$this->js = dt_options().'<script>$(d).ready(function(){$(".helpdesk_list").dataTable({"bSort":false});$(".dataTables_wrapper select").show();})</script>';
			$this->load->view('footer');
		} else {
			show_404();
		}
	}
	public function message_item($id=0){
		if(is_valid_number($id)){
			$this->load->model(array('flatmodel','helpdesk_model'));
			$item = $this->helpdesk_model->message_item($id,$this->session->soc)->row();
			if(!empty($item)){
				$user = $this->flatmodel->get_single_user_flat($item->sender_id)->result();
				$counts = $this->helpdesk_model->helpdesk_counters($this->society);
				$this->load->helper('helpdesk_helper');
				$replies = $this->helpdesk_model->get_message_replies($id)->result();
				$this->helpdesk_model->update_help(array('read_by_reciever'=>1),$id);
				$this->load->view('header');
				$this->load->view('helpdesk/message_item',array('item'=>$item,'id'=>$id,'replies'=>$replies,'counts'=>$counts,'user'=>$user));
				$this->js = '<script src="'.base_url('assets/js/helpdesk_item.js').'"></script>';
				$this->load->view('footer');
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}
}