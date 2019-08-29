<?php
class Adminnotificationdata extends MY_Model {
	public function get_all($soc){
		$this->by_society($soc);
		$this->db->join('users as u1','u1.id=admin_notifications.user_id','inner');
		$this->db->join('users as u2','admin_notifications.user_refer=u2.id','left');
		return $this->db->where(array('is_active'=>1))->select('admin_notifications.id,user_id,notification_type,u1.firstname as fn_u1,u1.lastname as ln_u1,created_date,u2.firstname as fn_u2,u2.lastname as ln_u2')->order_by('admin_notifications.id')->get('admin_notifications');
	}
	public function valid_notification($soc,$id){
		$this->by_society($soc);
		$query = $this->db->limit(1)->select('admin_notifications.id')->order_by('admin_notifications.id')->get('admin_notifications',array('id'=>$id))->num_rows();
		return ($query === 1) ? true : false;
	}
	public function notification_dismiss($soc,$id){
		$this->by_society($soc);
		$this->db->limit(1)->where(array('id'=>$id))->order_by('admin_notifications.id')->update('admin_notifications',array('is_active'=>2));
	}
}