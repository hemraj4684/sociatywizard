<?php
class Paymentgateway extends CI_Controller {
	public function paybill($id = 0,$soc = 0,$user = 0){
		if(is_valid_number($id)){
			$this->load->model(array('invoicemodel','user'));
			$data = $this->invoicemodel->get_invoice_one($id,$soc);
			if($data->num_rows() == 1){
				$u_info = $this->user->user_with_society($user,$soc,'firstname as fn,lastname as ln,mobile_no,email,society_name,society_address,society_pincode,society_main.id')->row();
				if($u_info){
					$this->load->view('userrole/paybill',array('data' => $data->row(),'u_info' => $u_info));
				}
			} else {
				show_404();
			}
		}
	}
	
	public function forwarding_to_payment_gateway(){
		$this->load->view('payment_gateway/Crypto.php');
		$this->load->view('payment_gateway/ccavRequestHandler.php');
	}
}