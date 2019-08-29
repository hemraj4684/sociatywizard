<?php
class Documentsmodel extends MY_Model {
	public function __construct() {
		parent::__construct();
	}
	public function get_all_folders($soc){
		$this->by_society($soc);
		return $this->db->order_by('id DESC')->get('document_folder');
	}
	public function get_all_for_dropdown(){
		return $this->db->order_by('id DESC')->select('id,folder_name')->get('document_folder');
	}
	public function get_a_folder($id,$soc){
		$this->db->join('users','users.id=document_folder.created_by','inner')->select('document_folder.id as id,folder_name,description,date_created,firstname,lastname');
		return $this->db->limit(1)->get_where('document_folder',array('document_folder.id'=>$id,'document_folder.society_id'=>$soc));
	}
	public function folder_files($id){
		return $this->db->order_by('id DESC')->get_where('society_documents',array('folder_id'=>$id));
	}
	public function add($data){
		return $this->db->insert('document_folder',$data);
	}
	public function update($data,$id,$soc){
		$this->by_society($soc);
		return $this->db->limit(1)->where(array('id'=>$id))->update('document_folder',$data);
	}
	public function insert_files($data){
		if(!empty($data)){
			$this->db->insert_batch('society_documents',$data);
		}
	}
	public function remove_file($id,$folder,$soc){
		$file = array();
		if($this->verify_folder($folder,$soc)){
			$this->db->where_in('id',$id);
			$file = $this->db->get('society_documents')->result();
			if(!empty($file)){
				$this->db->where_in('id',$id);
				$this->db->delete('society_documents');
			}
		}
		return $file;
	}
	public function get_files_by_id($id,$folder,$soc){
		if($this->verify_folder($folder,$soc)){
			$this->db->where_in('id',$id);
			return $this->db->get('society_documents')->result();
		}
		return array();
	}
	public function verify_folder($id,$soc){
		$this->by_society($soc);
		$this->db->where(array('id'=>$id));
		$res = $this->db->select('id')->limit(1)->get('document_folder');
		if($res->num_rows()===1){
			return true;
		}
		return false;
	}
	public function delete_folder($id,$soc){
		$this->by_society($soc);
		$this->db->where(array('id'=>$id));
		return $this->db->limit(1)->delete('document_folder');
	}
	public function folder_exist($name,$soc){
		$res = $this->db->limit(1)->get_where('document_folder',array('folder_name'=>$name,'society_id'=>$soc));
		if($res->num_rows()===1){
			return true;
		}
		return false;
	}
}