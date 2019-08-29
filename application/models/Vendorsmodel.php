<?php
class Vendorsmodel extends MY_Model {
	public function get_vendors($soc){
		$this->by_society($soc);
		return $this->db->order_by('id DESC')->get('vendors_data');
	}
	public function add_vendor($data){
		$this->db->insert('vendors_data',$data);
	}
	public function remove_vendor($id,$soc){
		$this->by_society($soc);
		$this->db->where(array('id'=>$id))->limit(1)->delete('vendors_data');
	}
	public function update_vendor($data,$id,$soc){
		$this->by_society($soc);
		$this->db->where(array('id'=>$id))->limit(1)->update('vendors_data',$data);
	}
}