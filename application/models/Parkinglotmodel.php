<?php
class Parkinglotmodel extends MY_Model {
	public function get_parking_list($soc){
		$this->db->join('flats','parking_data.flat_id=flats.id','left');
		$this->db->join('wings','flats.flat_wing=wings.id','left')->order_by('parking_data.id DESC');
		return $this->db->select('parking_data.id,slot_label,no_plate,vehicle_model,vehicle_type,flat_no,name,flat_id')->get_where('parking_data',array('parking_data.society_id'=>$soc));
	}
	public function add($data){
		$this->db->trans_start();
		$this->db->insert('parking_data',$data);
		if(isset($data['flat_id'])){
			$this->db->set('total_parking','total_parking+1',FALSE);
			$this->db->order_by('id DESC')->where(array('id'=>$data['flat_id']))->limit(1)->update('flats');
		}
		$this->db->trans_complete();
	}
	public function delete($id,$soc){
		$this->db->trans_start();
		$query = $this->db->select('flat_id')->order_by('id DESC')->limit(1)->get_where('parking_data',array('id'=>$id,'society_id'=>$soc));
		if($query->num_rows()>0){
			$flat = $query->row()->flat_id;
			$this->db->order_by('id DESC')->limit(1)->where(array('id'=>$id))->delete('parking_data');
			if(!empty($flat)){
				$this->db->set('total_parking','total_parking-1',FALSE);
				$this->db->order_by('id DESC')->where(array('id'=>$flat))->limit(1)->update('flats');
			}
		}
		$this->db->trans_complete();
	}
	public function edit($id,$soc,$data){
		$this->db->trans_start();
		$query = $this->db->select('flat_id')->order_by('id DESC')->limit(1)->get_where('parking_data',array('id'=>$id,'society_id'=>$soc));
		if($query->num_rows()>0){
			$flat = $query->row()->flat_id;
			if(isset($data['flat_id'])){
				if(!($data['flat_id']===$flat)){
					$this->db->set('total_parking','total_parking+1',FALSE);
					$this->db->order_by('id DESC')->where(array('id'=>$data['flat_id']))->limit(1)->update('flats');
					if(!empty($flat)){
						$this->db->set('total_parking','total_parking-1',FALSE);
						$this->db->order_by('id DESC')->where(array('id'=>$flat))->limit(1)->update('flats');
					}
				}
			} else {
				if(!empty($flat)){
					$this->db->set('total_parking','total_parking-1',FALSE);
					$this->db->order_by('id DESC')->where(array('id'=>$flat))->limit(1)->update('flats');
				}
			}
		}
		$this->db->limit(1)->order_by('id DESC')->where(array('id'=>$id))->update('parking_data',$data);
		$this->db->trans_complete();
	}
}