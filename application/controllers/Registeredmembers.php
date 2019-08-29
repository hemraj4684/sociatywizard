<?php
class Registeredmembers extends Controlusers {
	public $p_var='Society Members';
	public function index(){
		$this->js = '<script type="text/javascript" src="'.base_url('assets/js/Chart.min.js').'"></script><script type="text/javascript" src="'.base_url('assets/js/member_index.js').'"></script>';
		$this->load->view('header');
		$assoc = $this->user->total_assoc($this->society)->row();
		$newreq = $this->user->total_society_request($this->society)->row();
		$unassigned = $this->user->total_unassigned($this->society)->row();
		$multi_flat = $this->user->total_multi_flat($this->society)->row();
		$this->load->view('registeredmembers/index',array('unassigned'=>$unassigned,'assoc'=>$assoc,'multi_flat'=>$multi_flat,'newreq'=>$newreq));
		$this->load->view('footer');
	}
	public function member_list($id = 0) {
		$this->css = dt_css();
		$this->js = dt_options().'<script src="'.base_url('assets/js/admin_users_1.1.js').'"></script>';
		$wing = [];
		if(!is_valid_number($id)){$id=0;}else{$this->load->model('flatmodel');$wing = $this->flatmodel->get_one_wing($id,$this->session->soc)->row();}
		$data = $this->user->get_all_for_list($this->session->soc,$id)->result();
		$this->load->view('header');
		$this->load->view('registeredmembers/list',array('data'=>$data,'id'=>$id,'wing'=>$wing));
		$this->load->view('layout/sms_modal');
		$this->load->view('footer');
	}
	public function edit($id = 0) {
		$this->js = '<script type="text/javascript" src="'.base_url('assets/js/user_edit.js').'"></script>';
		$this->load->model(array('flatmodel'));
		$user = $this->user->get_one($id,$this->session->soc)->row();
		if(!empty($user)){
			$ref = strtolower($this->input->get('ref'));
			$this->load->view('header');
			$u_flat = $this->flatmodel->get_user_flat_full($id,$this->session->soc)->result();
			$this->load->view('registeredmembers/edit',array('id'=>$id,'user'=>$user,'u_flat'=>$u_flat,'ref'=>$ref));
			$this->load->view('footer');
		} else {
			show_404();
		}
	}
	public function members_list_type($data,$type){
		$this->css = dt_css();
		$this->js = dt_options().'<script src="'.base_url('assets/js/admin_users_1.1.js').'"></script>';
		$this->load->view('header');
		$this->load->view('registeredmembers/members_list_type',array('data'=>$data,'type'=>$type));
		$this->load->view('layout/sms_modal');
		$this->load->view('footer');
	}
	public function association_members(){
		$data = $this->user->association_members($this->session->soc)->result();
		$this->members_list_type($data,2);
	}
	public function unassigned_flats(){
		$data = $this->user->unassigned_members($this->session->soc)->result();
		$this->members_list_type($data,3);
	}
	public function multiple_flats(){
		$data = $this->user->multi_members($this->session->soc)->result();
		$this->members_list_type($data,4);
	}
	public function add_member(){
		$this->js = '<script src="'.base_url('assets/js/new_member.js').'"></script>';
		$this->load->view('header');
		$this->load->view('registeredmembers/new_member');
		$this->load->view('footer');
	}
	public function requests(){
		$this->js = '<script src="'.base_url('assets/js/request_s_page.js').'"></script>';
		$data = $this->user->request_data($this->society);
		$this->load->view('header');
		$this->load->view('registeredmembers/requests',array('data'=>$data->result()));
		$this->load->view('footer');
	}
}