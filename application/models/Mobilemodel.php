<?php
class Mobilemodel extends MY_Model {
	public function __construct() {
		parent::__construct();
	}
	public function login($user){
		return $this->db->limit(1)->select('id,password,salt,token,phone_verified')->order_by('id DESC')->where('mobile_no',$user)->get('users');
	}
	public function valid_user_check($id) {
		return $this->db->select('token')->limit(1)->get_where('users',array('id'=>$id));
	}
	public function valid_user_society($id,$soc) {
		$this->user_society($soc);
		return $this->db->select('society')->limit(1)->get_where('user_society',array('user'=>$id));
	}
	public function insert_help($data){
		$this->db->insert('members_messages',$data);
		return $this->db->insert_id();
	}
	public function get_help_history($id){
		return $this->db->order_by('id desc')->get_where('members_messages',array('sender_id'=>$id));
	}
	public function get_history_by_type($id,$type){
		$this->db->where(array('message_type'=>$type));
		return $this->get_help_history($id);
	}
	public function get_single_helpdesk($id,$sender){
		return $this->db->order_by('id desc')->limit(1)->get_where('members_messages',array('sender_id'=>$sender,'id'=>$id));
	}
	public function get_message_replies($user,$id){
		$check = $this->db->select('id')->get_where('members_messages',array('sender_id'=>$user,'id'=>$id));
		if($check->num_rows() > 0){
			$this->db->join('users as admin_user','admin_user.id=members_messages_reply.admin_id','left');
			$this->db->join('users','users.id=members_messages_reply.user_id','left');
			return $this->db->order_by('members_messages_reply.id desc')->select('members_messages_reply.user_id as userid,members_messages_reply.admin_id as adminid,admin_user.firstname as admin_fn,admin_user.lastname as admin_ln,users.firstname as user_fn,users.lastname as user_ln,reply_text,reply_date,reply_from')->get_where('members_messages_reply',array('parent_id'=>$id));
		}
		return false;
	}
	public function insert_help_comment($data,$user,$id){
		$this->db->trans_start();
		$check = $this->db->select('id')->order_by('members_messages.id DESC')->get_where('members_messages',array('sender_id'=>$user,'id'=>$id,'conv_type!='=>2));
		if($check->num_rows() > 0){
			$this->db->insert('members_messages_reply',$data);
			$this->db->set('read_by_reciever',2)->order_by('members_messages.id DESC')->where(array('id'=>$id))->limit(1)->update('members_messages');
		}
		$this->db->trans_complete();
	}
	public function get_user_name($id){
		return $this->db->limit(1)->order_by('id DESC')->select('firstname,lastname')->get_where('users',array('id'=>$id));
	}
	public function get_userpic($id){
		return $this->db->limit(1)->order_by('id DESC')->select('picture')->get_where('users',array('id'=>$id));
	}
	public function get_user_flats($user,$soc){
		$this->by_society($soc);
		return $this->db->select('flat_id')->get_where('user_flat',array('user'=>$user));
	}
	public function fetch_flat_details($id){
		$this->db->where_in('flats.id',$id);
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		return $this->db->select('flats.id,flat_no,flat_wing,name,sq_foot,owner_name,owner_number,intercom,status,total_parking')->get('flats');
	}
	public function count_users_in_flat($id){
		$this->db->where_in('user_flat.flat_id',$id);
		return $this->db->select('count(user) as total,flat_id')->group_by('flat_id')->get('user_flat');
	}
	public function get_invoice_for_flat($id){
		$this->db->where('flat_id',$id)->order_by('id DESC');
		return $this->db->select('id,invoice_month,advance_month,total_amount,due_date,amount_paid,payment_method,is_paid')->get('flat_invoice');
	}
	public function get_invoice_for_flats($ids){
		$this->db->where_in('flat_id',$ids)->order_by('id DESC,flat_id ASC');
		return $this->db->select('id,invoice_month,flat_id,advance_month,total_amount,due_date,amount_paid,payment_method,is_paid,late_fee_amonut')->get('flat_invoice');
	}
	public function member_in_flat($id,$soc){
		$this->db->where('user_flat.flat_id',$id);
		$this->db->join('users','users.id=user_flat.user','inner');
		$this->db->join('user_society','user_society.user=users.id','inner');
		$this->user_society($soc);
		return $this->db->select('users.id,firstname,lastname,mobile_no,email,picture,owner_tenant')->get('user_flat');
	}
	public function get_flat_basics($id){
		$this->db->where('flats.id',$id);
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		return $this->db->select('flat_no,name')->get('flats');
	}
	public function flat_basic_by_ids($ids){
		$this->db->where_in('flats.id',$ids)->order_by('flats.id DESC');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		return $this->db->select('flats.id,flat_no,name')->get('flats');
	}
	public function for_user_settings($id){
		return $this->db->limit(1)->select('firstname,lastname,gender,date_of_birth,picture,phone_privacy')->get_where('users',array('id'=>$id));
	}
	public function update_user($id,$data){
		$this->db->where(array('id'=>$id));
		return $this->db->limit(1)->update('users',$data);
	}
	public function get_gallery_folders($soc){
		$this->by_society($soc);
		return $this->db->order_by('id DESC')->select('id,folder_name,no_of_pics')->get('gallery_folder');
	}
	public function get_gallery_files($id,$soc){
		if($this->verify_gallery_folder($id,$soc)){
			$this->db->where(array('folder_id'=>$id));
			return $this->db->order_by('id DESC')->select('id,image_name,caption')->get('gallery_files')->result();
		}
	}
	public function fetch_notices($soc){
		$this->by_society($soc);
		return $this->db->order_by('id DESC')->get('notice_board');
	}
	public function get_society_branding($type=0){
		if($type!=0){
			$this->db->where(array('content_type'=>5));
			$this->db->limit(1);
		}
		return $this->db->get('society_branding');
	}
	public function get_invoice_one($id,$soc){
		return $this->db->limit(1)->order_by('id DESC')->get_where('flat_invoice',array('society_id'=>$soc,'id'=>$id));
	}
	public function get_invoice_particulars($id){
		return $this->db->get_where('invoice_particular',array('invoice_id'=>$id));
	}
	public function verify_gallery_folder($id,$soc){
		$this->by_society($soc);
		$this->db->where(array('id'=>$id));
		$res = $this->db->select('id')->limit(1)->get('gallery_folder');
		if($res->num_rows()===1){
			return true;
		}
		return false;
	}
	public function society_info($soc){
		return $this->db->limit(1)->select('society_name,society_address,registration_number')->get_where('society_main',array('id'=>$soc));
	}
	public function vendors_list($soc){
		$this->by_society($soc);
		return $this->db->order_by('id DESC')->get('vendors_data');
	}
	public function all_societies(){
		return $this->db->order_by('id DESC')->select('id,society_name,society_address')->get('society_main');
	}
	public function society_exist($soc) {
		$query = $this->db->limit(1)->select('id')->get_where('society_main',array('id'=>$soc));
		if($query->num_rows()>0){
			return true;
		}
		return false;
	}
	public function request_verify($user){
		$query = $this->db->limit(1)->get_where('users_request',array('user_id'=>$user));
		if($query->num_rows()>0){
			return true;
		}
		return false;
	}
	public function society_request_entry($data){
		$this->db->insert('users_request',$data);
	}
	public function requested_society($id){
		return $this->db->select('society_name,society_address,status,date_requested')->limit(1)->join('society_main','society_main.id=users_request.society_id','inner')->where(array('user_id'=>$id))->get('users_request');
	}
	public function single_data_add($data,$user){
		$this->db->limit(1)->where(array('user'=>$user))->update('users_single_data',$data);
	}
	public function flat_parking($flat,$limit){
		return $this->db->order_by('id DESC')->limit($limit)->where(array('flat_id'=>$flat))->get('parking_data');
	}
	public function members_list_by_society($soc){
		$this->user_society($soc);
		$this->db->join('user_society','user_society.user=users.id','inner');
		return $this->db->select('id,firstname,lastname,picture,designation,mobile_no,phone_privacy')->get('users');
	}
}