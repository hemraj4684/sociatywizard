<?php
class Incomeexpense extends Controlusers {
	public function index(){
		$this->css = '<link href="'.base_url('assets/css/dashboard.css').'" rel="stylesheet">';
		$this->js = '<script src="'.base_url('assets/js/Chart.min.js').'"></script><script src="'.base_url('assets/js/ie_index.js').'"></script>';
		$this->load->model('iemodel');
		$expense = $this->iemodel->sum_trans($this->society,2)->row()->total;
		$income = $this->iemodel->sum_trans($this->society,1)->row()->total;
		$balance = $income-$expense;
		$e_cat = $this->iemodel->get_category($this->society,2)->result();
		$i_cat = $this->iemodel->get_category($this->society,1)->result();
		$this->load->view('header');
		$this->load->view('income_expense/index',array('expense'=>$expense,'income'=>$income,'balance'=>$balance,'e_cat'=>$e_cat,'i_cat'=>$i_cat));
		$this->load->view('footer');
	}
	public function add_expense(){
		$get = strtolower($this->input->get('type'));
		$this->js = '<script src="'.base_url('assets/js/trans.js').'"></script>';
		$this->load->view('header');
		$this->load->model('iemodel');
		$drop = $assocs = array();
		if($get=='income'){
			$drop = $this->iemodel->get_category($this->society,1)->result();
		} else {
			$assocs = $this->user->association_members_simple($this->society)->result();
			$drop = $this->iemodel->get_category($this->society,2)->result();
		}
		$this->load->view('income_expense/new_expense',array('get'=>$get,'drop'=>$drop,'assocs'=>$assocs));
		$this->load->view('footer');
	}
	public function expense_list(){
		$this->js = dt_options().'<script src="'.base_url('assets/js/expenses.js').'"></script>';
		$this->css = dt_css();
		$this->load->view('header');
		$this->load->view('income_expense/expenses');
		$this->load->view('footer');
	}
	public function edit_ie($id){
		if(is_valid_number($id)){
			$this->load->model('iemodel');
			$this->load->model('user');
			$data = $this->iemodel->get_one($id,$this->society)->row();
			if(!empty($data)){
				$drop = $assocs = array();
				if($data->trans_type==='2'){
					$drop = $this->iemodel->get_category($this->society,2)->result();
					$assocs = $this->user->association_members_simple($this->society)->result();
				} else {
					$drop = $this->iemodel->get_category($this->society,1)->result();
				}
				$this->load->view('income_expense/edit_ie',array('data'=>$data,'id'=>$id,'drop'=>$drop,'assocs'=>$assocs));
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}
	public function voucher($id=0){
		if(is_valid_number($id)){
			$this->load->model('societysettingmodel');
			$society = $this->societysettingmodel->get_my($this->society)->row();
			if(!empty($society)){
				$this->load->model('iemodel');
				$data = $this->iemodel->voucher_data($id,$this->society)->row();
				if(!empty($data)){
					$this->load->view('income_expense/voucher',array('society'=>$society,'data'=>$data));
				} else {
					show_404();
				}
			}
		} else {
			show_404();
		}
	}
}