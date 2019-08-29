<?php
class Flatmodel extends MY_Model {
	public function add($data){
		$this->db->trans_start();
		$this->db->insert('flats',$data);
		$last_id = $this->db->insert_id();
		if(isset($data['flat_wing'])){
			$this->db->where(array('id'=>$data['flat_wing']))->limit(1)->set('total_flats','total_flats+1',FALSE)->update('wings');
		}
		$this->db->insert('flat_bill_relation',array('flat_id'=>$last_id));
		$this->db->trans_complete();
	}
	public function flat_exists($flat_no,$wing,$soc){
		$this->db->order_by('flats.id DESC');
		$this->by_flat_society($soc);
		if(!empty($wing)){
			$this->db->where(array('flat_wing'=>$wing));
		} else {
			$this->db->where('flat_wing IS NULL',null,false);
		}
		$query = $this->db->select('id')->limit(1)->get_where('flats',array('flat_no'=>$flat_no));
		if($query->num_rows()===1){
			return true;
		}
		return false;
	}
	public function flats_table($flat_id,$select){
		$this->db->order_by('flats.id DESC');
		return $this->db->limit(1)->select($select)->where(array('id'=>$flat_id))->get('flats');
	}
	public function verify_block($block,$soc){
		$this->by_society($soc);
		$query = $this->db->where(array('id'=>$block))->limit(1)->get('wings');
		if($query->num_rows() > 0){
			return true;
		}
		return false;
	}
	public function edit($data,$id,$soc,$do,$beforewing){
		$this->db->where('id',$id)->limit(1);
		$this->by_flat_society($soc);
		$this->db->update('flats',$data);
		if($do===1){
			$this->db->where(array('id'=>$data['flat_wing'],'society_id'=>$soc))->limit(1)->set('total_flats','total_flats+1',FALSE)->update('wings');
		} else if($do===2){
			$this->db->where(array('id'=>$beforewing,'society_id'=>$soc))->limit(1)->set('total_flats','total_flats-1',FALSE)->update('wings');
		} else if($do===3){
			$this->db->where(array('id'=>$data['flat_wing'],'society_id'=>$soc))->limit(1)->set('total_flats','total_flats+1',FALSE)->update('wings');
			$this->db->where(array('id'=>$beforewing,'society_id'=>$soc))->limit(1)->set('total_flats','total_flats-1',FALSE)->update('wings');
		}
	}
	public function user_flat_verify($user,$flat){
		return $this->db->where(array('user'=>$user,'flat_id'=>$flat))->limit(1)->get('user_flat');
	}
	public function get_list($soc){
		$this->db->select('flats.id as flat_id,flat_no,flat_wing,sq_foot,owner_name,owner_number,name as wing_name,intercom');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->db->order_by('flats.id DESC');
		$this->by_flat_society($soc);
		return $this->db->get('flats');
	}
	public function get_id_name($soc){
		$this->db->select('flats.id as flat_id,flat_no,name as wing_name,wings.id as wing_id');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->db->where(array('flats.society_id'=>$soc));
		return $this->db->get('flats');
	}
	public function get_single_user_flat($user){
		$this->db->select('flat_no,name as wing_name');
		$this->db->join('flats','flats.id=user_flat.flat_id','inner');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->db->where(array('user_flat.user'=>$user));
		return $this->db->get('user_flat');
	}
	public function get_id_name_one($flat){
		$this->db->select('flat_no,name as wing_name,wings.id as wing_id');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->db->where(array('flats.id'=>$flat))->limit(1)->order_by('flats.id DESC');
		return $this->db->get('flats');
	}
	public function get_id_name_by_flats($flats){
		$this->db->select('flats.id,flat_no,name as wing_name,wings.id as wing_id');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->db->where_in('flats.id',$flats)->order_by('flats.id DESC');
		return $this->db->get('flats');
	}
	public function multipayment_pre_data($ids){
		$this->db->select('flat_invoice.id as id,flat_no,name as wing_name,total_amount');
		$this->db->join('flats','flats.id=flat_invoice.flat_id','inner');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->db->where(array('flat_invoice.is_paid'=>2));
		$this->db->where_in('flat_invoice.id',$ids)->order_by('flat_invoice.id DESC');
		return $this->db->get('flat_invoice');
	}
	public function get_one($id,$soc){
		$this->db->select('flats.id as flat_id,flat_no,flat_wing,sq_foot,owner_name,name as wing_name,intercom,owner_number,status');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->db->order_by('flats.id DESC');
		$this->db->where('flats.id',$id);
		$this->by_flat_society($soc);
		return $this->db->get('flats');
	}
	public function get_user_flat($user){
		$this->db->where('user',$user);
		$this->db->select('flat_id,owner_tenant');
		return $this->db->get('user_flat');
	}
	public function get_user_flat_full($user,$soc){
		$this->db->where('user',$user);
		$this->by_flat_society($soc);
		$this->db->select('flat_id,owner_tenant,flat_no,flat_wing,name');
		$this->db->join('flats','flats.id=user_flat.flat_id','inner');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		return $this->db->get('user_flat');
	}
	public function get_users_in_flat($id){
		$this->db->select('users.id as id,firstname,lastname,email,mobile_no,picture');
		$this->db->join('user_flat','user_flat.user=users.id','inner');
		$this->db->where('user_flat.flat_id',$id);
		return $this->db->get('users');
	}
	public function get_users_in_flats($ids){
		$this->db->select('users.id as id,firstname,lastname,email,mobile_no,picture,user_flat.flat_id');
		$this->db->join('user_flat','user_flat.user=users.id','inner');
		$this->db->where_in('user_flat.flat_id',$ids);
		$this->db->group_by('users.id');
		return $this->db->get('users');
	}
	public function get_wings($soc){
		$this->by_society($soc);
		return $this->db->get('wings');
	}
	public function get_one_wing($id,$soc){
		$this->by_society($soc);
		return $this->db->get_where('wings',array('id'=>$id));
	}
	public function all_user_and_flat($wing){
		$this->db->join('flats','flats.id=user_flat.flat_id','inner');
		$this->db->join('users','users.id=user_flat.user','inner');
		$this->db->select('flat_id,flat_wing as wing');
		$this->db->where(array('flats.flat_wing'=>$wing));
		// $this->db->where(array('users.is_active'=>1));
		$this->db->group_by('user_flat.user');
		return $this->db->get('user_flat');
	}
	public function get_flat_status($id,$soc){
		if($id > 0){
			$this->db->where(array('flat_wing'=>$id));
		}
		$this->db->where(array('flats.society_id'=>$soc));
		$this->db->join('flats','flat_status.id=flats.status','left');
		$this->db->select('flat_status.id as id,name,status');
		return $this->db->get('flat_status');
	}
	public function get_block_status($soc){
		$this->db->select('flat_wing as wing');
		return $this->get_flat_status(0,$soc);
	}
	public function get_by_wings($id,$soc){
		$this->db->where(array('flat_wing'=>$id));
		return $this->get_list($soc);
	}
	public function one_status($id){
		return $this->db->get_where('flat_status',array('id'=>$id));
	}
	public function get_by_status($status,$block,$soc){
		if($block>0){
			$this->db->where(array('flats.flat_wing'=>$block));
		}
		$this->db->where(array('status'=>$status));
		return $this->get_list($soc);
	}
	public function get_society_data($id,$var=[]){
		if(!empty($var)){
			$this->db->select($var);
		}
		return $this->db->where(array('id'=>$id))->limit(1)->get('society_main');
	}
	public function secretary_signature(){
		return $this->get_society_branding(array(5));
	}
	public function search_flats_autocomplete($var,$soc){
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->by_flat_society($soc);
		$this->db->where(array('flat_no'=>$var))->select('flats.id,flat_no,name');
		return $this->db->limit(10)->get_where('flats');
	}
	public function update_user_flat($user,$soc,$data,$t_house){
		$verify = $this->db->limit(1)->get_where('user_society',array('society'=>$soc,'user'=>$user));
		if($verify->num_rows() > 0){
			$this->db->trans_start();
			$this->db->where(array('user'=>$user,'society_id'=>$soc));
			$this->db->delete('user_flat');
			$this->db->where(array('user'=>$user,'society'=>$soc));
			$this->db->set('no_of_flats',$t_house);
			$this->db->limit(1)->update('user_society');
			if(!empty($data)){
				$this->db->insert_batch('user_flat',$data);
			}
			$this->db->trans_complete();
		}
	}
	public function my_flats_full($flats){
		$this->db->select('flats.id,flat_no,name as wing_name,wings.id as wing_id,owner_number,owner_name,intercom');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->db->where_in('flats.id',$flats)->order_by('flats.id DESC');
		return $this->db->get('flats');
	}
	public function flat_and_bill_full($soc){
		$this->db->order_by('flats.id DESC');
		$this->db->select('flats.id,flat_no,wings.name as wing_name,bill_id');
		$this->db->join('wings','flats.flat_wing=wings.id','left');
		$this->db->join('flat_bill_relation','flat_bill_relation.flat_id=flats.id','inner');
		return $this->db->get_where('flats',array('flats.society_id'=>$soc));
	}
	public function assign_flat_bill($data){
		$this->db->trans_start();
		foreach ($data as $key => $value) {
			$this->db->set('bill_id',$key);
			$this->db->where_in('flat_id',$value)->update('flat_bill_relation');
		}
		unset($value);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	public function flat_id_of_bill($bill_id){
		return $this->db->select('flat_id')->get_where('flat_bill_relation',array('bill_id'=>$bill_id));
	}
	public function search_flats_table($flat_id,$select){
		$this->db->order_by('flats.id DESC');
		return $this->db->select($select)->where_in('id',$flat_id)->get('flats');
	}
}