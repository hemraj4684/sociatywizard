<?php
class Reports extends Controlusers {
	public function index(){
		$this->header();
		$this->load->model('iemodel');
		$data = $this->iemodel->get_category($this->society,2)->result();
		$data1 = $this->iemodel->get_category($this->society,1)->result();
		$this->load->view('reports/index',array('data'=>$data,'data1'=>$data1));
		$this->footer();
	}
	public function view($id=''){
		$this->css = dt_css();
		$this->js = dt_options();
		$this->load->view('layout/head');
		$this->load->model('reportsmodel');
		switch ($id) {
			case '1':
			case '7':
				if($id==1) {
					$what='2';
					$report_title = 'Current Month Expense ('.date('F Y').')';
				} else {
					$what = '1';
					$report_title = 'Current Month Income ('.date('F Y').')';
				}
				$data = $this->reportsmodel->date_wise(date('Y-m-01'),date('Y-m-d'),$what,$this->society)->result();
				$this->js .= '<script src="'.base_url('assets/js/expense_report.js').'"></script>';
				$this->load->view('reports/expense',array('data'=>$data,'report_title'=>$report_title,'what'=>$what));
				break;
			case '2':
			case '8':
				$last = strtotime(date('Y-m')." -1 month");
				if($id==2) {
					$what='2';
					$report_title = 'Last Month Expense ('.date('F Y',$last).')';
				} else {
					$what = '1';
					$report_title = 'Last Month Income ('.date('F Y',$last).')';
				}
				$data = $this->reportsmodel->date_wise(date('Y-m-01',$last),date('Y-m-d',strtotime("last day of previous month")),$what,$this->society)->result();
				$this->js .= '<script src="'.base_url('assets/js/expense_report.js').'"></script>';
				$this->load->view('reports/expense',array('data'=>$data,'report_title'=>$report_title,'what'=>$what));
				break;
			case '3':
			case '9':
				$last = strtotime(date('Y-m')." -2 month");
				if($id==3) {
					$what='2';
					$report_title = 'Last Three Month Expense ('.date('F Y',$last).' to '.date('F Y').')';
				} else {
					$what = '1';
					$report_title = 'Last Three Month Income ('.date('F Y',$last).' to '.date('F Y').')';
				}
				$data = $this->reportsmodel->date_wise(date('Y-m-01',$last),date('Y-m-d'),$what,$this->society)->result();
				$this->js .= '<script src="'.base_url('assets/js/expense_report.js').'"></script>';
				$this->load->view('reports/expense',array('data'=>$data,'report_title'=>$report_title,'what'=>$what));
				break;
			case '4':
			case '10':
				if($id==4) {
					$what='2';
					$report_title = 'Expense In Last 30 Days';
				} else {
					$what = '1';
					$report_title = 'Income In Last 30 Days';
				}
				$data = $this->reportsmodel->date_wise(date('Y-m-d', strtotime('-30 days')),date('Y-m-d'),$what,$this->society)->result();
				$this->js .= '<script src="'.base_url('assets/js/expense_report.js').'"></script>';
				$this->load->view('reports/expense',array('data'=>$data,'report_title'=>$report_title,'what'=>$what));
				break;
			case '5':
			case '6':
				$data = array();
				if($id==5) {
					$what='2';
					$report_title = 'Expenses';
				} else {
					$what = '1';
					$report_title = 'Income';
				}
				$this->js .= '<script src="'.base_url('assets/js/expense_report.js').'"></script>';
				$this->load->view('reports/date_selector',array('id'=>'custom_ie','type'=>$id));
				$this->load->view('reports/expense',array('data'=>$data,'report_title'=>$report_title,'what'=>$what));
				break;
			default:
				show_404();
				break;
		}

		$this->load->view('layout/foot');
	}
	public function expense_category($id=''){
		$this->category_wise($id,2);
	}
	public function income_category($id=''){
		$this->category_wise($id,1);
	}
	public function category_wise($id,$type){
		if(is_valid_number($id)){
			$this->load->model('iemodel');
			$valid = $this->iemodel->valid_ie_category($id,$this->society,$return);
			if($valid){
				$data = array();
				if($type==1){
					$ietype = 5;
					$report_title = 'Income - '.h($return->name);
				} else {
					$ietype = 6;
					$report_title = 'Expense - '.h($return->name);
				}
				$this->css = dt_css();
				$this->js = dt_options();
				$this->js .= '<script src="'.base_url('assets/js/expense_report.js').'"></script>';
				$this->load->view('layout/head');
				$this->load->view('reports/date_selector',array('id'=>'custom_ie_category','cat'=>$id,'type'=>$ietype));
				$this->load->view('reports/expense',array('data'=>$data,'report_title'=>$report_title,'what'=>$type));
				$this->load->view('layout/foot');
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}
}