<?php
class User extends MY_Model {
	public function register($data,$code) {
		$this->db->trans_start();
		$this->db->insert('users',$data);
		$last_id = $this->db->insert_id();
		$this->db->insert('users_single_data',array('user'=>$last_id,'phone_code'=>$code));
		$this->db->trans_complete();
		return $last_id;
	}
	public function admin_user_add($first,$soc,$assoc,$flat_id,$ot,$nof,$is_admin,$desig){
		$this->db->trans_start();
		$this->db->insert('users',$first);
		$last_id = $this->db->insert_id();
		$this->db->insert('users_single_data',array('user'=>$last_id));
		$this->db->insert('user_society',array('user'=>$last_id,'assoc_member'=>$assoc,'society'=>$soc,'no_of_flats'=>$nof,'is_admin'=>$is_admin,'designation'=>$desig));
		if(!empty($flat_id)){
			$this->db->insert('user_flat',array('user'=>$last_id,'society_id'=>$soc,'flat_id'=>$flat_id,'owner_tenant'=>$ot));
		}
		$this->db->trans_complete();
	}
	public function user_flat_query() {
		$this->db->join('user_flat','user_flat.user=users.id','left');
		$this->db->join('flats','flats.id=user_flat.flat_id','left');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->db->select('name,flat_no');
	}
	public function update_new_phone_code($user,$code){
		$this->db->where(array('user'=>$user))->update('users_single_data',array('phone_code'=>$code));
	}
	public function user_by_id($id,$select){
		return $this->db->limit(1)->order_by('id DESC')->select($select)->get_where('users',array('id'=>$id));
	}
	public function user_with_society($id,$soc,$select){
		$this->db->join('user_society','user_society.user=users.id','inner');
		$this->db->join('society_main','society_main.id=user_society.society','inner');
		return $this->db->limit(1)->order_by('users.id DESC')->select($select)->get_where('users',array('users.id'=>$id));
	}
	public function user_with_society_init($id,$soc,$select){
		$this->db->join('user_society','user_society.user=users.id','left');
		$this->db->join('society_main','society_main.id=user_society.society','left');
		return $this->db->limit(1)->order_by('users.id DESC')->select($select)->get_where('users',array('users.id'=>$id));
	}
	public function search_in_user_main($from,$select,$limit='1'){
		return $this->db->limit($limit)->select($select)->get_where('users',$from);
	}
	public function verify_phone_code($user){
		return $this->db->limit(1)->order_by('user DESC')->select('phone_code')->get_where('users_single_data',array('user'=>$user));
	}
	public function update_users_main($user,$data){
		return $this->db->limit(1)->order_by('id DESC')->where(array('id'=>$user))->update('users',$data);
	}
	public function check_number_exists($number,$select='id'){
		return $this->db->limit(1)->select($select)->get_where('users',array('mobile_no'=>$number));
	}
	public function update_phone_code($user,$code){
		$this->db->trans_start();
		$this->update_users_main($user,array('phone_verified'=>1));
		$this->db->limit(1)->order_by('user DESC')->where(array('user'=>$user))->update('users_single_data',array('phone_code'=>$code));
		$this->db->trans_complete();
	}
	public function get_admins_list($soc){
		$this->user_flat_query();
		$this->db->join('user_society','user_society.user=users.id','inner');
		return $this->db->select('users.id,firstname,lastname,mobile_no,email,picture')->where(array('is_admin'=>1,'society'=>$soc))->get('users');
	}
	public function mobile_register($data,$code) {
		return $this->register($data,$code);
	}
	public function get_users_society($id){
		return $this->db->select('society,is_admin')->get_where('user_society',array('user'=>$id));
	}
	public function update($data,$data_s,$id,$soc) {
		$this->db->trans_start();
		$verify = $this->db->limit(1)->get_where('user_society',array('society'=>$soc,'user'=>$id));
		if($verify->num_rows() > 0){
			$this->db->where('id',$id)->limit(1);
			$this->db->update('users',$data);
			$this->db->where(array('society'=>$soc,'user'=>$id))->limit(1);
			$this->db->update('user_society',$data_s);
		}
		$this->db->trans_complete();
	}
	public function update_users_flat($flat,$old_flats,$flats){
		if($flat!==$old_flats){
			$this->db->where('user',$id);
			$this->db->delete('user_flat');
			if(!empty($flats)){
				$this->db->insert_batch('user_flat',$flats);
			}
		}
	}
	public function one_wing($id) {
		return $this->db->get_where('wings',array('id'=>$id));
	}
	public function get_all_for_list($soc,$wing=0){
		$this->db->select('users.id as id,firstname,lastname,email,mobile_no,designation,picture');
		$this->db->order_by('users.id desc');
		$this->db->join('users','user_society.user=users.id','inner');
		$this->db->from('user_society');
		$this->user_society($soc);
		if($wing!=0){
			$this->db->group_by('users.id');
			$this->db->join('user_flat','users.id=user_flat.user','inner');
			$this->db->join('flats','flats.id=user_flat.flat_id','inner');
			$this->db->where(array('flats.flat_wing'=>$wing));
			$this->db->select('flat_no');
		} else {
			$this->user_flat_query();
		}
		return $this->db->get();
	}
	public function get_one($id,$soc){
		$this->db->order_by('users.id desc');
		$this->db->where('users.id',$id);
		$this->db->join('user_society','user_society.user=users.id','inner');
		$this->user_society($soc);
		$this->db->from('users')->limit(1);
		return $this->db->get();
	}
	public function total_assoc($soc){
		$this->user_society($soc);
		$this->db->select('count(user) as total');
		return $this->db->get_where('user_society',array('user_society.assoc_member'=>1));
	}
	public function total_unassigned($soc){
		return $this->db->query("SELECT count(user_society.user) as total FROM user_society WHERE user_society.society = ".$this->db->escape($soc)." AND user_society.user NOT IN (SELECT user_flat.user FROM user_flat)");
	}
	public function total_society_request($soc){
		$this->by_society($soc);
		return $this->db->select('count(user_id) as total')->get('users_request');
	}
	public function total_multi_flat($soc){
		$this->user_society($soc);
		$this->db->select('count(user) as total');
		return $this->db->get_where('user_society',array('no_of_flats>'=>1));
	}
	public function association_members($soc){
		$this->db->where(array('user_society.society'=>$soc));
		$this->db->join('users','user_society.user=users.id','inner');
		$this->user_flat_query();
		$this->db->select('users.id as id,firstname,lastname,mobile_no,email,designation,picture');
		return $this->db->get_where('user_society',array('user_society.assoc_member'=>1));
	}
	public function unassigned_members($soc){
		$this->db->join('users','user_society.user=users.id','inner');
		$this->user_flat_query();
		$this->db->select('users.id as id,firstname,lastname,mobile_no,email,designation,picture');
		$this->db->where_not_in('users.id','SELECT user_flat.user FROM user_flat',FALSE);
		$this->user_society($soc);
		return $this->db->get('user_society');
	}
	public function multi_members($soc){
		$this->db->where(array('user_society.society'=>$soc));
		$this->db->join('users','user_society.user=users.id','inner');
		$this->user_flat_query();
		$this->db->select('users.id as id,firstname,lastname,mobile_no,email,designation,picture');
		$this->db->where(array('user_society.no_of_flats>'=>1));
		return $this->db->get('user_society');
	}
	public function remove_from_society($ids,$soc){
		if(!empty($ids)){
			$this->db->trans_start();
			$this->db->where_in('user',$ids)->delete('user_society',array('society'=>$soc));
			$this->db->where_in('user',$ids)->delete('user_flat',array('society_id'=>$soc));
			$this->db->trans_complete();
		}
	}
	public function verify_user($user,$soc){
		$verify = $this->db->limit(1)->get_where('user_society',array('society'=>$soc,'user'=>$user));
		if($verify->num_rows() > 0){
			return true;
		}
		return false;
	}
	public function verify_users($users,$soc){
		if(!empty($users)){
			$verify = $this->db->where_in('user',$users)->get_where('user_society',array('society'=>$soc));
			if($verify->num_rows() === count($users)){
				return true;
			}
		}
		return false;
	}
	public function request_data($soc) {
		$this->db->join('users','users.id=users_request.user_id','inner');
		return $this->db->select('id,firstname,picture,lastname,mobile_no,status,date_requested')->get_where('users_request',array('society_id'=>$soc));
	}
	public function admin_request_verify($user,$soc){
		$query = $this->db->limit(1)->get_where('users_request',array('user_id'=>$user,'society_id'=>$soc));
		if($query->num_rows()>0){
			return true;
		}
		return false;
	}
	public function accept_admin_request($user,$soc){
		$this->db->trans_start();
		$this->db->limit(1)->where(array('user_id'=>$user,'society_id'=>$soc))->delete('users_request');
		$this->db->insert('user_society',array('user'=>$user,'society'=>$soc));
		$this->db->trans_complete();
	}
	public function reject_admin_request($user,$soc){
		$this->db->limit(1)->where(array('user_id'=>$user,'society_id'=>$soc))->update('users_request',array('status'=>2));
	}
	public function remove_from_request($user,$soc){
		$this->db->limit(1)->where(array('user_id'=>$user,'society_id'=>$soc))->delete('users_request');
	}
	public function get_pushTokens($soc){
		$this->db->join('user_society','user_society.user=users_single_data.user','inner')->where('push_token IS NOT NULL',NULL,FALSE);
		return $this->db->where(array('society'=>$soc))->select('push_token')->get('users_single_data')->result();
	}
	public function get_pushToken($id){
		return $this->db->select('push_token')->limit(1)->where(array('user'=>$id))->get('users_single_data')->row();
	}
	public function login_track($user,$data){
		$this->db->insert('user_login_tracking',array('user_id'=>$user,'server_data'=>$data));
	}
	public function user_adding_user($data,$code,$log,$flat,$owner_tenant){
		$this->db->trans_start();
		$this->db->insert('users',$data);
		$last_id = $this->db->insert_id();
		$this->db->insert('users_single_data',array('user'=>$last_id,'phone_code'=>$code));
		$log['added_to'] = $last_id;
		$this->db->insert('user_created_by_user_logs',$log);
		$this->db->insert('user_society',array('user'=>$last_id,'society'=>$log['society_id'],'no_of_flats'=>count($flat)));
		foreach ($flat as $key => $value) {
			$this->db->insert('user_flat',array('user'=>$last_id,'flat_id'=>$value->flat_id,'society_id'=>$log['society_id'],'owner_tenant'=>$owner_tenant));
		}
		$this->db->insert('admin_notifications',array('notification_type'=>1,'user_id'=>$log['added_by'],'user_refer'=>$last_id,'society_id'=>$log['society_id']));
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	public function association_members_simple($soc,$select=''){
		if($select==''){
			$select = 'users.id as id,firstname,lastname';
		}
		$this->db->where(array('user_society.society'=>$soc));
		$this->db->join('users','user_society.user=users.id','inner');
		$this->db->select($select);
		return $this->db->get_where('user_society',array('user_society.assoc_member'=>1));
	}
	public function valid_assoc_member($user,$soc){
		if($this->db->select('user')->where(array('user'=>$user,'user_society.society'=>$soc))->limit(1)->get('user_society')->num_rows() === 1){
			return true;
		}
		return false;
	}
	public function get_members_for_user($soc){
		$this->db->where(array('user_society.society'=>$soc));
		$this->db->join('users','user_society.user=users.id','inner');
		$this->db->select('users.id as id,firstname,lastname,mobile_no,designation,picture,phone_privacy');
		return $this->db->order_by('id DESC')->get('user_society');
	}
	public function get_users_simple($soc,$select){
		$this->db->join('user_society','user_society.user=users.id','inner');
		$this->db->where(array('user_society.society'=>$soc));
		return $this->db->select($select)->get('users');
	}
}