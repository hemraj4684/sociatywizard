<?php
class Noticeboard_model extends MY_Model {
	public function __construct() {
		parent::__construct();
	}
	public function fetch_notices($soc){
		$this->by_society($soc);
		return $this->db->order_by('id DESC')->get('notice_board');
	}
	public function add($data){
		return $this->db->insert('notice_board',$data);
	}
	public function update($data,$id,$soc){
		$this->by_society($soc);
		return $this->db->limit(1)->where(array('id'=>$id))->update('notice_board',$data);
	}
	public function remove_notice($id,$soc){
		$this->by_society($soc);
		return $this->db->limit(1)->where(array('id'=>$id))->delete('notice_board');
	}
}