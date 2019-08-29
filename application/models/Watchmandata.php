<?php
class Watchmandata extends MY_Model {
	public function add_one($data,$log,&$last_id=array()){
		$this->db->trans_start();
		$this->db->insert('society_visitors',$data);
		$log['parent_id'] = $this->db->insert_id();
		$this->db->insert('society_visitors_log',$log);
		$log_id = $this->db->insert_id();
		$this->db->trans_complete();
		$last_id = array($log['parent_id'],$log_id);
		return $this->db->trans_status();
	}
	public function watchman_verify($id,$token,$soc){
		$query = $this->db->limit(1)->select('token,society_id')->where(array('id'=>$id))->get('watchman_credentials');
		if($query->num_rows()===1){
			$data = $query->row();
			if($token===$data->token && $data->society_id===$soc){
				return true;
			}
		}
		return false;
	}
	public function watchman_username($soc){
		return $this->db->select('username')->limit(1)->where(array('society_id'=>$soc))->get('watchman_credentials')->row()->username;
	}
	public function update_credentials($soc,$data){
		return $this->db->limit(1)->where(array('society_id'=>$soc))->update('watchman_credentials',$data);
	}
	public function watchman_data_search_one($username,$select='*'){
		return $this->db->select($select)->limit(1)->where(array('username'=>$username))->get('watchman_credentials');
	}
	public function data_to_verify_before_delete($ids,$soc){
		$this->db->join('society_visitors','society_visitors.id=society_visitors_log.parent_id','inner');
		$this->db->select('parent_id')->where_in('society_visitors_log.id', $ids);
		return $this->db->get('society_visitors_log')->result();
	}
	public function delete_logs($ids){
		$this->db->where_in('society_visitors_log.id', $ids)->delete('society_visitors_log');
	}
	public function check_parent_count($ids){
		$this->db->join('society_visitors_log','society_visitors.id=society_visitors_log.parent_id','left');
		return $this->db->where_in('society_visitors.id', $ids)->select('count(society_visitors_log.parent_id) as total,visitor_image as image,society_visitors.id')->group_by('society_visitors.id')->get('society_visitors');
	}
	public function delete_single_record($id){
		return $this->db->where(array('id'=>$id))->limit(1)->delete('society_visitors');
	}
	public function visitors($soc){
		$this->db->select('society_visitors.id,society_visitors_log.id as log_id,visitor_name as name,visitor_number as number,visitor_purpose as purpose,visitor_image as image,flat_no,date_of_entry as entry_date,wings.name as wing');
		$this->db->join('society_visitors_log','society_visitors_log.parent_id=society_visitors.id','inner');
		$this->db->join('flats','society_visitors.visitor_flat=flats.id','left');
		$this->db->join('wings','flats.flat_wing=wings.id','left');
		return $this->db->order_by('society_visitors_log.date_of_entry DESC')->get_where('society_visitors',array('society_visitors.society_id',$soc));
	}
	public function search_by_date($soc,$start,$end=''){
		if($end==''){
			$this->db->where(array('date_of_entry >= '=>$start.' 00:00:00','date_of_entry <= '=>$start.' 23:59:59'));
		} else {
			$this->db->where(array('date_of_entry >= '=>$start.' 00:00:00','date_of_entry <= '=>$end.' 23:59:59'));
		}
		return $this->visitors($soc);
	}
}