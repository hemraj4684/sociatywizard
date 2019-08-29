<?php
class Societysettings extends Controlusers {
	public function index(){
		$this->load->model('societysettingmodel');
		$data = $this->societysettingmodel->get_my($this->society)->row();
		$this->js = '<script src="'.base_url('assets/js/s_setting.js').'"></script>';
		$this->load->view('header');
		$this->load->view('societysettings/index',array('data'=>$data));
		$this->load->view('footer');
	}
	public function edit_bill_group($id=0){
		if(is_valid_number($id)){
			$this->load->model('societysettingmodel');
			$bill = $this->societysettingmodel->single_bill_group($id,$this->society)->row();
			if(!empty($bill)){
				$data = $this->societysettingmodel->bill_particulars($bill->id)->result();
				$this->load->view('societysettings/edit_bill',array('data'=>$data,'id'=>$id,'bill'=>$bill));
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}
}