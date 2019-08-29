<?php
class Iemodel extends MY_Model {
	public function add_transaction($data){
		$this->db->insert('income_expense',$data);
	}
	public function last_30_days_trans($soc,$type){
		$this->db->where(array('income_expense.society_id'=>$soc));
		$this->db->join('users','users.id=income_expense.added_by','inner');
		$this->db->join('ie_category','ie_category.id=income_expense.category_id','left')->select('name');
		return $this->db->order_by('income_expense.id DESC')->select('income_expense.id,amount,date_of_payment,giver_taker,payment_method,cheque_no,firstname,lastname,bill_id')->where(array('trans_type'=>$type,'date_of_payment>='=>date('Y-m-d', strtotime('-30 days'))))->get('income_expense');
	}
	public function verify_ie_by_society($ids,$soc){
		$this->by_society($soc);
		return $this->db->where_in('id',$ids)->get('income_expense');
	}
	public function delete_ie($ids,$soc){
		$this->by_society($soc);
		return $this->db->where_in('id',$ids)->delete('income_expense');
	}
	public function date_wise($start,$end,$type,$soc){
		$this->db->where(array('income_expense.society_id'=>$soc));
		$this->db->join('users','users.id=income_expense.added_by','inner');
		$this->db->join('ie_category','ie_category.id=income_expense.category_id','left')->select('name');
		return $this->db->order_by('income_expense.id DESC')->select('income_expense.id,amount,date_of_payment,giver_taker,payment_method,cheque_no,firstname,lastname,bill_id')->where(array('trans_type'=>$type,'date_of_payment>='=>$start,'date_of_payment<='=>$end))->get('income_expense');
	}
	public function sum_trans($soc,$type){
		$this->by_society($soc);
		return $this->db->where(array('trans_type'=>$type))->select_sum('amount','total')->get('income_expense');
	}
	public function yearly_data($year,$soc){
		$this->by_society($soc);
		$this->db->where(array('trans_type'=>1,'date_of_payment>='=>$year.'-01-01','date_of_payment<='=>$year.'-12-31 23:59:59'))->group_by('MONTH(date_of_payment)');
		$income = $this->db->select('sum(amount) as amount,MONTH(date_of_payment) as month_val')->get('income_expense')->result();
		$this->by_society($soc);
		$this->db->where(array('trans_type'=>2,'date_of_payment>='=>$year.'-01-01','date_of_payment<='=>$year.'-12-31 23:59:59'))->group_by('MONTH(date_of_payment)');
		$expense = $this->db->select('sum(amount) as amount,MONTH(date_of_payment) as month_val')->get('income_expense')->result();
		return array($income,$expense);
	}
	public function get_one($id,$soc){
		$this->by_society($soc);
		return $this->db->where(array('id'=>$id))->order_by('id DESC')->limit(1)->get('income_expense');
	}
	public function is_editable_trans($id,$soc){
		$this->by_society($soc);
		$this->db->where('bill_id IS NULL', null, false);
		$query = $this->db->where(array('id'=>$id))->order_by('id DESC')->limit(1)->get('income_expense');
		if($query->num_rows()===1){
			return true;
		}
		return false;
	}
	public function update_trans($data,$id,$soc){
		$this->by_society($soc);
		$this->db->where(array('id'=>$id))->order_by('id DESC')->limit(1)->update('income_expense',$data);
	}
	public function get_category($soc,$type){
		$this->by_society($soc);
		return $this->db->order_by('id DESC')->where(array('c_type'=>$type))->get('ie_category');
	}
	public function add_category($data){
		$this->db->insert('ie_category',$data);
		return $this->db->insert_id();
	}
	public function delete_category($id,$soc){
		$this->by_society($soc);
		return $this->db->where(array('id'=>$id))->delete('ie_category');
	}
	public function edit_category($data,$soc,$id){
		$this->by_society($soc);
		return $this->db->where(array('id'=>$id))->update('ie_category',$data);
	}
	public function valid_ie_category($id,$soc,&$data){
		$this->by_society($soc);
		$query = $this->db->limit(1)->get_where('ie_category',array('id'=>$id));
		if($query->num_rows()===1){
			$data = $query->row();
			return true;
		}
		return false;
	}
	public function voucher_data($id,$soc){
		$this->db->join('users as u1','u1.id=income_expense.added_by','inner');
		$this->db->join('users as u2','income_expense.authorised_by=u2.id','left');
		$this->by_society($soc);
		return $this->db->limit(1)->select('income_expense.id,date_of_payment,cheque_no,giver_taker,amount,u1.firstname as fn_init,u1.lastname as ln_init,u2.firstname as fn_auth,u2.lastname as ln_auth')->where(array('income_expense.id'=>$id))->get('income_expense');
	}
}