<?php
class Helpdesk_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	public function get_helpdesk_by_type($type,$soc){
		$this->db->select('members_messages.id,firstname,lastname,picture,date_sent,message_subject,read_by_reciever,read_by_sender');
		$this->db->join('users','users.id=members_messages.sender_id','inner');
		return $this->db->order_by('members_messages.id DESC')->where(array('message_type'=>$type,'society_id'=>$soc,'conv_type'=>1))->get('members_messages');
	}
	public function get_closed_helpdesk($soc){
		$this->db->select('members_messages.id,firstname,lastname,picture,date_sent,message_subject,read_by_reciever,read_by_sender');
		$this->db->join('users','users.id=members_messages.sender_id','inner');
		return $this->db->order_by('members_messages.id DESC')->where(array('society_id'=>$soc,'conv_type'=>2))->get('members_messages');
	}
	public function message_item($id,$soc){
		$this->db->join('users','users.id=members_messages.sender_id','inner');
		$this->db->select('members_messages.id as id,sender_id,message_subject,message,message_type,sent_from,conv_type,date_sent,firstname,lastname,picture');
		return $this->db->order_by('members_messages.id desc')->limit(1)->get_where('members_messages',array('society_id'=>$soc,'members_messages.id'=>$id));
	}
	public function verify_helpdesk($id,$soc,$select='',&$out=''){
		$query = $this->db->limit(1)->select('id'.$select)->get_where('members_messages',array('society_id'=>$soc,'id'=>$id));
		$out = $query->row();
		return ($query->num_rows()>0) ? true : false;
	}
	public function insert_comment($data){
		$this->db->insert('members_messages_reply',$data);
	}
	public function get_message_replies($id){
		$this->db->join('users as admin_user','admin_user.id=members_messages_reply.admin_id','left');
		$this->db->join('users','users.id=members_messages_reply.user_id','left');
		return $this->db->order_by('members_messages_reply.id asc')->select('members_messages_reply.user_id as userid,members_messages_reply.admin_id as adminid,admin_user.firstname as admin_fn,admin_user.lastname as admin_ln,users.firstname as user_fn,users.lastname as user_ln,users.picture as userpic,admin_user.picture as adminpic,reply_text,reply_date,reply_from')->get_where('members_messages_reply',array('parent_id'=>$id));
	}
	public function conv_type_change($id,$type){
		return $this->db->set('conv_type',$type)->where(array('id'=>$id))->limit(1)->update('members_messages');
	}
	public function helpdesk_counters($soc){
		$gn = $this->db->where(array('message_type'=>1,'conv_type'=>1,'society_id'=>$soc))->count_all_results('members_messages');
		$co = $this->db->where(array('message_type'=>2,'conv_type'=>1,'society_id'=>$soc))->count_all_results('members_messages');
		$cl = $this->db->where(array('conv_type'=>2,'society_id'=>$soc))->count_all_results('members_messages');
		return array($gn,$co,$cl);
	}
	public function update_help($data,$id){
		$this->db->limit(1)->order_by('members_messages.id DESC')->where(array('id'=>$id))->update('members_messages',$data);
	}
	public function user_wise($id,$soc){
		return $this->db->select('id,message_subject,date_sent,conv_type,read_by_sender')->order_by('members_messages.id DESC')->where(array('sender_id'=>$id,'society_id'=>$soc))->get('members_messages');
	}
}