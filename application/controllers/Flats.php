<?php
class Flats extends Controlusers {
	public $css,$js;
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$this->js = '<script type="text/javascript" src="'.base_url('assets/js/Chart.min.js').'"></script><script type="text/javascript" src="'.base_url('assets/js/flat_index.js').'"></script>';
		$this->load->view('header');
		$this->load->model('flatmodel');
		$wings = $this->flatmodel->get_wings($this->session->soc)->result();
		$this->load->view('flats/index',array('wings'=>$wings));
		$this->load->view('footer');
	}
	public function flat_list($data,$t_title,$id=0){
		$this->js = '<script type="text/javascript" src="'.base_url('assets/js/Chart.min.js').'"></script><script type="text/javascript" src="'.base_url('assets/js/flat_list.js').'"></script>'.dt_options();
		$this->css = dt_css();
		$this->load->view('header');
		$this->load->view('flats/list',array('data'=>$data,'top_title'=>$t_title,'id'=>$id));
		$this->load->view('layout/sms_modal');
		$this->load->view('footer');
	}
	public function all_flats(){
		$this->load->model('flatmodel');
		$data = $this->flatmodel->get_list($this->session->soc)->result();
		$this->flat_list($data,'All Flats List');
	}
	public function list_by_block($id=0){
		if(is_valid_number($id)){
			$this->load->model('flatmodel');
			$wing = $this->flatmodel->get_one_wing($id,$this->session->soc)->row();
			$data = [];
			if(!empty($wing)){
				$data = $this->flatmodel->get_by_wings($id,$this->session->soc)->result();
				$this->flat_list($data,'Flats In Block '.h($wing->name),$id);
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}
	public function by_type($id=0,$block=0){
		if(is_valid_number($id)){
			$this->load->model('flatmodel');
			$status = $this->flatmodel->one_status($id)->row();
			if(!empty($status)){
				if(!is_valid_number($block)){$block=0;}
				$data = $this->flatmodel->get_by_status($id,$block,$this->session->soc)->result();
				$status = $this->flatmodel->one_status($id)->row();
				$wing = [];
				if($block>0){
					$wing = $this->flatmodel->get_one_wing($block,$this->session->soc)->row();
				}
				$this->js = dt_options().'<script src="'.base_url('assets/js/flat_list.js').'"></script>';
				$this->css = dt_css();
				$this->load->view('header');
				$this->load->view('flats/by_status',array('data'=>$data,'status'=>$status,'wing'=>$wing));
				$this->load->view('layout/sms_modal');
				$this->load->view('footer');
			} else {
				show_404();
			}
		} else {
		}
	}
	public function add(){
		$this->js = '<script type="text/javascript" src="'.base_url('assets/js/flats.js').'"></script>';
		$this->load->model('flatmodel');
		$wings = $this->flatmodel->get_wings($this->society)->result();
		$this->load->view('header');
		$this->load->view('flats/add',array('wings'=>$wings));
		$this->load->view('footer');
	}
	public function edit($id = 0){
		if(is_valid_number($id)){
			$this->js = '<script type="text/javascript" src="'.base_url('assets/js/flats.js').'"></script>';
			$this->load->model(array('flatmodel','user'));
			$data = $this->flatmodel->get_one($id,$this->society)->row();
			if(!empty($data)){
				$wings = $this->flatmodel->get_wings($this->society)->result();
				$this->load->view('header');
				$this->load->view('flats/edit',array('wings'=>$wings,'data'=>$data,'id'=>$id));
				$this->load->view('footer');
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}
	public function flat_details($id = 0){
		$this->load->model('flatmodel');
		$data = $this->flatmodel->get_one($id,$this->session->soc)->row();
		if(!empty($data)){
			$users = $this->flatmodel->get_users_in_flat($id)->result();
			$this->load->view('header');
			$this->load->view('flats/detail',array('data'=>$data,'users'=>$users));
			$this->load->view('layout/sms_modal');
			$this->load->view('footer');
		} else {
			show_404();
		}
	}
	public function old_invoices($id=0){
		$ref = strtolower($this->input->get('ref'));
		$this->load->model(array('Invoicemodel','flatmodel'));
		$flat = $this->flatmodel->get_one($id,$this->session->soc)->row();
		if(!empty($flat)){
			$invoices = $this->Invoicemodel->get_all_by_user($id,$this->session->soc)->result();
			$this->load->view('header');
			$this->load->view('flats/old_invoices',array('id'=>$id,'flat'=>$flat,'invoices'=>$invoices,'ref'=>$ref));
			$this->load->view('footer');
		} else {
			show_404();
		}
	}
	public function view_bill($id = 0){
		if(is_valid_number($id)){
			$this->load->model(array('Invoicemodel','flatmodel'));
			$month = $this->Invoicemodel->get_invoice_one($id,$this->session->soc)->row();
			if($month){
				$partic = $this->Invoicemodel->get_invoice_particulars($id)->result();
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
				$prev_bill = $this->Invoicemodel->bill_sum_before_date($month->invoice_month,$month->flat_id);
				$prev_bal = $prev_bill->row();
				$this->load->view('flats/single_bill',array('id'=>$id,'partic'=>$partic,'month'=>$month,'flat'=>$flat,'soc_name'=>$soc_name,'soc_addr'=>$soc_addr,'notes'=>$notes,'reg_no'=>$reg_no,'prev_bal'=>$prev_bal->total_amount));
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}
	public function edit_bill($id=0){
		if(is_valid_number($id)){
			$this->load->model(array('Invoicemodel','flatmodel'));
			$month = $this->Invoicemodel->get_invoice_one($id,$this->session->soc)->row();
			if(!empty($month)){
				if($month->is_paid!=='1'){
					$flat = $this->flatmodel->get_one($month->flat_id,$this->session->soc)->row();
					$this->load->view('flats/edit_bill',array('month'=>$month,'flat'=>$flat,'id'=>$id));
				} else {
					show_404();
				}
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}
	public function payment_details($id=0){
		if(is_valid_number($id)){
			$this->load->model(array('invoicemodel','flatmodel'));
			$details = $this->invoicemodel->get_bill_details($id,$this->session->soc)->row();
			if(!empty($details)){
				$this->load->view('flats/bill_details',array('details'=>$details,'id'=>$id));
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}
}