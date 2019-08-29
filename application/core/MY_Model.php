<?php
class MY_Model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	protected function by_society($soc){
		$this->db->where(array('society_id'=>$soc));
	}
	protected function user_society($soc){
		$this->db->where(array('society'=>$soc));
	}
	protected function by_flat_society($soc){
		$this->db->where(array('flats.society_id'=>$soc));
	}
}