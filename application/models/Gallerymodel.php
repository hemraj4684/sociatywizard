<?php
class Gallerymodel extends MY_Model {
	public function __construct() {
		parent::__construct();
	}
	public function get_all_folders($soc){
		$this->by_society($soc);
		return $this->db->order_by('id DESC')->get('gallery_folder');
	}
	public function get_active_folders($soc){
		$this->by_society($soc);
		return $this->db->order_by('id DESC')->get('gallery_folder');
	}
	public function add_folder($data){
		$this->db->insert('gallery_folder',$data);
	}
	public function update($data,$id,$soc){
		$this->by_society($soc);
		$this->db->where(array('id'=>$id))->limit(1);
		$this->db->update('gallery_folder',$data);
	}
	public function get_one_folder($id,$soc){
		$this->db->join('users','users.id=gallery_folder.uploaded_by','inner')->select('gallery_folder.id as id,description,date_created,firstname,lastname,folder_name');
		return $this->db->limit(1)->get_where('gallery_folder',array('gallery_folder.id'=>$id,'gallery_folder.society_id'=>$soc));
	}
	public function folder_files($id){
		return $this->db->select('id,image_name')->order_by('id DESC')->get_where('gallery_files',array('folder_id'=>$id));
	}
	public function insert_files($data,$id,$no_of){
		if(!empty($data)){
			$this->db->set('no_of_pics', 'no_of_pics+'.$no_of, FALSE);
			$this->db->where(array('id'=>$id))->limit(1)->update('gallery_folder');
			$this->db->insert_batch('gallery_files',$data);
		}
	}
	public function get_files_by_id($id,$folder,$soc){
		if($this->verify_folder($folder,$soc)){
			$this->db->where_in('id',$id);
			return $this->db->get('gallery_files')->result();
		}
		return array();
	}
	public function remove_file($id,$folder,$soc){
		$file = array();
		if($this->verify_folder($folder,$soc)){
			$this->db->where_in('id',$id);
			$file = $this->db->get('gallery_files')->result();
			if(!empty($file)){
				$this->db->where_in('id',$id);
				$this->db->delete('gallery_files');
				$no_of = $this->db->affected_rows();
				$this->db->set('no_of_pics', 'no_of_pics-'.$no_of, FALSE);
				$this->db->where(array('id'=>$folder))->limit(1)->update('gallery_folder');
			}
		}
		return $file;
	}
	public function delete_folder($id,$soc){
		$this->by_society($soc);
		$this->db->where(array('id'=>$id));
		return $this->db->limit(1)->delete('gallery_folder');
	}
	public function folder_exist($name,$soc){
		$res = $this->db->limit(1)->get_where('gallery_folder',array('folder_name'=>$name,'society_id'=>$soc));
		if($res->num_rows()===1){
			return true;
		}
		return false;
	}
	public function verify_folder($id,$soc){
		$this->by_society($soc);
		$this->db->where(array('id'=>$id));
		$res = $this->db->select('id')->limit(1)->get('gallery_folder');
		if($res->num_rows()===1){
			return true;
		}
		return false;
	}
}