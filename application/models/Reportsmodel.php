<?php
class Reportsmodel extends MY_Model {
	public function date_wise($start,$end,$type,$soc){
		$this->db->where(array('income_expense.society_id'=>$soc));
		$this->db->join('ie_category','ie_category.id=income_expense.category_id','left')->select('name');
		return $this->db->order_by('income_expense.id DESC')->select('income_expense.id,amount,date_of_payment,giver_taker,payment_method,cheque_no')->where(array('trans_type'=>$type,'date_of_payment>='=>$start,'date_of_payment<='=>$end))->get('income_expense');
	}
	public function date_wise_category($start,$end,$cat,$soc){
		$this->db->where(array('income_expense.society_id'=>$soc));
		$this->db->join('ie_category','ie_category.id=income_expense.category_id','inner')->select('name');
		return $this->db->order_by('income_expense.id DESC')->select('income_expense.id,amount,date_of_payment,giver_taker,payment_method,cheque_no')->where(array('income_expense.category_id'=>$cat,'date_of_payment>='=>$start,'date_of_payment<='=>$end))->get('income_expense');
	}
}