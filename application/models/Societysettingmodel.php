<?php
class Societysettingmodel extends MY_Model {
	public function get_my($soc){
		return $this->db->limit(1)->where(array('id'=>$soc))->get('society_main');
	}
	public function update_data($data,$soc){
		$this->db->limit(1)->where(array('id'=>$soc))->update('society_main',$data);
	}
	public function insert_bill_group($first,$data){
		$this->db->trans_start();
		$this->db->insert('bill_group',$first);
		$last_id = $this->db->insert_id();
		$second = array();
		foreach ($data as $key => $value) {
			$value['group_id'] = $last_id;
			array_push($second,$value);
		}
		unset($value);
		$this->db->insert_batch('default_particulars',$second);
		$this->db->trans_complete();
	}
	public function my_bill_groups($soc){
		$this->by_society($soc);
		return $this->db->order_by('id DESC')->get('bill_group');
	}
	public function single_bill_group($id,$soc){
		$this->db->where(array('id'=>$id))->limit(1);
		return $this->my_bill_groups($soc);
	}
	public function bill_particulars($ids){
		return $this->db->order_by('group_id DESC')->where_in('group_id',$ids)->get('default_particulars');
	}
	public function remove_bill($id,$soc){
		$this->by_society($soc);
		$this->db->limit(1)->where(array('id'=>$id))->delete('bill_group');
	}
	public function verify_bill_particulars($ids,$main_id,$soc){
		if(!empty($ids) && $this->db->limit(1)->where(array('id'=>$main_id,'society_id'=>$soc))->select('id')->get('bill_group')->num_rows()===1){
			return $this->db->order_by('id DESC')->select('id')->where_in('id',$ids)->where(array('group_id'=>$main_id))->get('default_particulars')->num_rows();
		}
		return 0;
	}
	public function update_group_bill($first,$data,$bill_id,$rem,$new){
		$this->db->trans_start();
		$this->db->where(array('id'=>$bill_id))->update('bill_group',$first);
		if(!empty($data)){
			foreach ($data as $key => $value) {
				$this->db->where(array('id'=>$key))->limit(1)->update('default_particulars',$value);
			}
			unset($value);
		}
		if(!empty($rem)){
			$this->db->where_in('id',$rem)->delete('default_particulars');
		}
		if(!empty($new)){
			$addnew = array();
			foreach ($new as $key => $value) {
				$value['group_id'] = $bill_id;
				array_push($addnew,$value);
			}
			unset($value);
			$this->db->insert_batch('default_particulars',$addnew);
		}
		$this->db->trans_complete();
	}
	public function get_current_subscription_date($soc){
		return $this->db->select('subscribed_until')->limit(1)->where(array('id'=>$soc))->get('society_main');
	}
	public function update_subscription($data, $soc, $data2){
		$this->db->limit(1)->where(array('id'=>$soc))->update('society_main', $data);
		$this->db->insert('subcription_history', $data2);
	}
}