<?php
class Me extends Userrole {
	public function index() {
		$this->header();
		$flat = $flats = array();
		$members = array();
		if($this->society){
			$this->load->model('flatmodel');
			$data = $this->flatmodel->get_user_flat($this->session->user)->result();
			foreach ($data as $key => $value) {
				array_push($flat, $value->flat_id);
			}
			unset($value);
			if(!empty($flat)){
				$flats = $this->flatmodel->my_flats_full($flat)->result();
				if(count($flat)>1){
					$users = $this->flatmodel->get_users_in_flats($flat)->result();
					foreach ($users as $key => $value) {
						if(!isset($members[$value->flat_id])){
							$members[$value->flat_id] = array();
						}
						array_push($members[$value->flat_id], $value);
					}
					unset($value);
				} else {
					$members[$flat[0]] = $this->flatmodel->get_users_in_flat($flat[0])->result();
				}
			}
		}
		$this->load->view('userrole/flat',array('members'=>$members,'flats'=>$flats));
		$this->footer();
	}
	public function noticeboard(){
		$this->header();
		$this->load->model('noticeboard_model');
		$notices = array();
		if($this->society){
			$notices = $this->noticeboard_model->fetch_notices($this->society)->result();
		}
		$this->load->view('userrole/nb',array('notices'=>$notices));
		$this->footer();
	}
	public function gallery(){
		$this->header();
		$this->load->model('gallerymodel');
		$folders = array();
		if($this->society){
			$folders = $this->gallerymodel->get_active_folders($this->society)->result();
		}
		$this->load->view('userrole/g_folder',array('folders'=>$folders));
		$this->footer();
	}
	public function gallery_folder($id=0){
		if(is_valid_number($id) && $this->society){
			$this->js = '<script src="'.base_url('assets/js/lightbox.js').'"></script><script src="'.base_url('assets/js/gallery.js').'"></script>';
			$this->css = '<link href="'.base_url('assets/css/lightbox.css').'" rel="stylesheet">';
			$this->load->model('gallerymodel');
			$folder = $this->gallerymodel->get_one_folder($id,$this->society)->row();
			if(!empty($folder)){
				$this->header();
				$files = array();
				if($this->society){
					$files = $this->gallerymodel->folder_files($id)->result();
				}
				$this->load->view('userrole/g_files',array('files'=>$files,'id'=>$id,'folder'=>$folder));
				$this->footer();
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}
	public function flatbill(){
		$this->header();
		$bills = array();
		$flat = array();
		if($this->society){
			$this->load->model(array('flatmodel','invoicemodel'));
			$flat = $this->flatmodel->get_user_flat($this->session->user);
			if($flat->num_rows()>0){
				if($flat->num_rows()===1){
					$flats = $flat->row();
					$bills = $this->invoicemodel->get_all_by_user($flats->flat_id,$this->society)->result();
				} else {
					$flats = $flat->result();
					$flm = [];
					foreach ($flats as $key => $value) {
						array_push($flm, $value->flat_id);
					}
					unset($value);
					$bills = $this->invoicemodel->get_all_by_users($flm,$this->society)->result();
				}
			}
		}
		$this->load->view('userrole/my_bill',array('bills'=>$bills,'flat'=>$flat));
		$this->footer();
	}
	public function my_bill($id=0){
		if(is_valid_number($id)){
			$this->load->model(array('invoicemodel','flatmodel'));
			$month = $this->invoicemodel->get_invoice_one($id,$this->session->soc)->row();
			if(!empty($month)){
				if($this->is_my_flat($this->session->user,$month->flat_id)){
					$partic = $this->invoicemodel->get_invoice_particulars($id)->result();
					$flat = $this->flatmodel->get_one($month->flat_id,$this->session->soc)->row();
					$brand = $this->flatmodel->get_society_data($this->session->soc,'society_name,society_address,invoice_note,registration_number')->row();
					$soc_name = '<h5 class="bold-500 mt0">'.h($brand->society_name).'</h5>';
					$soc_addr = '<p class="mt0 mb0">'.h($brand->society_address).'</p>';
					$reg_no = '';
					if(!empty($brand->registration_number)){
						$reg_no = '<p class="mb0 mt0">Registration Number : '.h($brand->registration_number).'</p>';
					}
					$notes = '';
					if(!empty($brand->invoice_note)){
						$notes = '<div class="grey-text text-darken-1 font-12"><p><b>Notes :</b> '.para($brand->invoice_note).'</p></div>';
					}
					$prev_bill = $this->invoicemodel->bill_sum_before_date($month->invoice_month,$month->flat_id);
					$prev_bal = $prev_bill->row();
					$this->load->view('flats/single_bill',array('id'=>$id,'partic'=>$partic,'month'=>$month,'flat'=>$flat,'soc_name'=>$soc_name,'soc_addr'=>$soc_addr,'notes'=>$notes,'reg_no'=>$reg_no,'prev_bal'=>$prev_bal->total_amount));
				}
			}
		}
	}
	public function vendors(){
		$this->js = dt_options().'<script src="'.base_url('assets/js/uservendor.js').'"></script>';
		$this->css = dt_css();
		$this->load->model('vendorsmodel');
		$data = $this->vendorsmodel->get_vendors($this->society)->result();
		$this->header();
		$this->load->view('userrole/vendors',array('data'=>$data));
		$this->footer();
	}
	public function helpdesk(){
		$this->header();
		$data = array();
		$this->load->model('helpdesk_model');
		$data = $this->helpdesk_model->user_wise($this->session->user,$this->society)->result();
		$this->load->view('userrole/helpdesk',array('data'=>$data));
		$this->js = '<script src="'.base_url('assets/js/userhelpdesk.js').'"></script>';
		$this->footer();
	}
	public function paybill($id = 0){
		if(is_valid_number($id)){
			$this->load->model(array('invoicemodel','user'));
			$data = $this->invoicemodel->get_invoice_one($id,$this->society);
			if($data->num_rows() == 1){
				$u_info = $this->user->user_with_society($this->session->user,$this->society,'mobile_no,email,society_name,society_address,society_pincode,society_main.id')->row();
				$this->load->view('userrole/paybill',array('data' => $data->row(),'u_info' => $u_info));
			} else {
				show_404();
			}
		}
	}
	public function society_members(){
		$this->js = dt_options().'<script src="'.base_url('assets/js/society_members_user.js').'"></script>';
		$this->css = dt_css();
		$this->load->model('user');
		$data = $this->user->get_members_for_user($this->society)->result();
		$this->header();
		$this->load->view('userrole/society_members',array('data'=>$data));
		$this->footer();
	}
	public function forwarding_to_payment_gateway(){
		$this->load->view('payment_gateway/Crypto.php');
		$this->load->view('payment_gateway/ccavRequestHandler.php');
	}
	public function payment_completed(){
		if(isset($_POST["encResp"])){
			$data = array(
				'note'=>'Paid Through Payment Gateway',
				'amount_paid'=>'0.00',
				'date_of_payment'=>date('Y-m-d'),
				'cheque_amount'=>'0.00',
				'payment_method'=>3,
				'is_paid' => 1,
				'cheque_no' => '',
				'cheque_status' => 3,
				'cheque_date' => NULL
			);
			$order_id = 0;
			$this->load->view('payment_gateway/Crypto.php');
			$workingKey='927C0235026505239CD6FB48E1AD37C1';
			$encResponse=$_POST["encResp"];
			$rcvdString=decrypt($encResponse,$workingKey);
			$order_status="";
			$decryptValues=explode('&', $rcvdString);
			$dataSize=sizeof($decryptValues);
			for($i = 0; $i < $dataSize; $i++) 
			{
				$information=explode('=',$decryptValues[$i]);
				if($i==3)	$order_status=$information[1];
				if('merchant_param1'==$information[0]){
		    		$months = $information[1];
		    	}
		    	if('amount'==$information[0]){
		    		$data['amount_paid'] = $information[1];
		    		$amount=$information[1];
		    	}
		    	if('order_id'==$information[0]){
		    		$order_id=$information[1];
		    	}
			}
			if($order_status==="Success" && $order_id > 0){
				$this->session->set_flashdata('mb_payment',array($amount,$order_id));
				$this->load->model('invoicemodel');
				$ie_entry = array('amount'=>$amount,'date_of_payment'=>$data['date_of_payment'],'payment_method'=>$data['payment_method'],'cheque_no'=>$data['cheque_no'],'added_by'=>$this->session->user,'society_id'=>$this->society,'trans_type'=>1,'bill_id'=>$order_id);
				$this->invoicemodel->update_details($data,$order_id,$amount,$this->society,$ie_entry);
				redirect('me/payment_success');
			}
		}
		redirect('me/payment_error');
	}
	public function payment_error(){
		$this->header();
		$this->load->view('userrole/payment_error');
		$this->footer();
	}
	public function payment_success(){
		$data = $this->session->flashdata('mb_payment');
		if(!empty($data) && isset($data[0],$data[1])){
			$this->header();
			$this->load->view('userrole/payment_success',array('amount'=>$data[0],'order_id'=>$data[1]));
			$this->footer();
		} else {
			redirect('me/flatbill');
		}
	}
}