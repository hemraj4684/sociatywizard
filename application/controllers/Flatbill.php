<?php
class Flatbill extends Controlusers {
	public $css,$js;
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$this->js = '<script src="'.base_url('assets/js/Chart.min.js').'"></script><script src="'.base_url('assets/js/bill_index.js').'"></script>';
		$this->load->model(array('invoicemodel'));
		$months = $this->invoicemodel->get_monthly_collection($this->session->soc)->result();
		$this->load->view('header');
		$this->load->view('flatbill/index',array('months'=>$months));
		$this->load->view('footer');
	}
	public function month($month='',$year=''){
		if(is_valid_number($month) && is_valid_number($year) && $month < 13 && strlen($year)===4){
			$this->load->model(array('invoicemodel'));
			$data = $this->invoicemodel->get_month_wise($year.'-'.$month.'-01',$this->session->soc)->result();
			$this->css = dt_css();
			$this->js = dt_options().'<script src="'.base_url('assets/js/monthlist_bill.js').'"></script>';
			$this->load->view('header');
			$this->load->view('flatbill/monthwise',array('month_of'=>$year.'-'.$month.'-01','month'=>$month,'year'=>$year,'data'=>$data));
			$this->load->view('footer');
		} else {
			show_404();
		}
	}
	public function not_received($month='',$year=''){
		if(is_valid_number($month) && is_valid_number($year) && $month < 13 && strlen($year)===4){
			$this->load->model(array('invoicemodel'));
			$data = $this->invoicemodel->not_received_monthwise($year.'-'.$month.'-01')->result();
			$this->load->view('header');
			$this->load->view('flatbill/not_received',array('data'=>$data));
			$this->load->view('footer');
		} else {
			show_404();
		}
	}
	public function generate(){
		$this->js = '<script type="text/javascript" src="'.base_url('assets/js/angular.min.js').'"></script><script type="text/javascript" src="'.base_url('assets/js/invoice.js').'"></script><script type="text/javascript" src="'.base_url('assets/js/bill_calci.js').'"></script>';
		$this->load->model(array('invoicemodel'));
		$bill_group = $this->invoicemodel->get_bill_group($this->session->soc)->result();
		$this->load->view('header');
		$this->load->view('flatbill/generate_bill',array('bill_group'=>$bill_group));
		$this->load->view('footer');
	}
	public function pending(){
		$this->css = dt_css();
		$this->js = dt_options().'<script src="'.base_url('assets/js/pending_bill.js').'"></script>';
		$this->load->model('invoicemodel');
		$data = $this->invoicemodel->pending_bill(0,$this->session->soc)->result();
		$this->load->view('header');
		$this->load->view('flatbill/pending',array('data'=>$data));
		$this->load->view('footer');
	}
	public function arrears(){
		$this->js = dt_options().'<script src="'.base_url('assets/js/arrears.js').'"></script>';
		$this->css = '<link rel="stylesheet" href="'.base_url('assets/css/buttons.dataTables.min.css').'"><link rel="stylesheet" href="'.base_url('assets/css/jquery.dataTables.min.css').'">';
		$this->load->model('invoicemodel');
		$data = $this->invoicemodel->flatwise_collection(null,1,0,$this->session->soc)->result();
		$this->load->view('header');
		$this->load->view('flatbill/arrears',array('data'=>$data));
		$this->load->view('footer');
	}
	public function pending_cheques(){
		$this->css = dt_css();
		$this->js = dt_options().'<script src="'.base_url('assets/js/pending_cheques.js').'"></script>';
		$this->load->model('invoicemodel');
		$data = $this->invoicemodel->pending_cheques($this->session->soc)->result();
		$this->load->view('header');
		$this->load->view('flatbill/pending_cheques',array('data'=>$data));
		$this->load->view('footer');
	}
}