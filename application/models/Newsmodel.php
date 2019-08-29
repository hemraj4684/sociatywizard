<?php
class Newsmodel extends MY_Model {
	public function news_category_parent(){
		// store in redis
		return $this->db->select('id,category_name,c_icon')->where('parent_id IS NULL',null,FALSE)->get('news_category')->result();
	}
	public function news_category_exist($id,$select='id',&$return=''){
		$query = $this->db->select($select)->limit(1)->get_where('news_category',array('id'=>$id));
		if($query->num_rows()===1){
			$return = $query->row();
			return true;
		}
		return false;
	}
	public function news_exist($id,$select='id',&$return=''){
		$query = $this->db->select($select)->limit(1)->order_by('id DESC')->get_where('news_data',array('id'=>$id));
		if($query->num_rows()===1){
			$return = $query->row();
			return true;
		}
		return false;
	}
	public function get_category_wise_news_list($id){
		return $this->db->order_by('id DESC')->select('id,title,news_cover')->get_where('news_data',array('category_id'=>$id));
	}
}