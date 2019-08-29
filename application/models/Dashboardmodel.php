<?php
class Dashboardmodel extends MY_Model {
	public function dashboard_one($soc){
		$rm = $this->db->where(array('society'=>$soc))->count_all_results('user_society');
		$am = $this->db->where(array('assoc_member'=>1,'society'=>$soc))->count_all_results('user_society');
		$do = $this->db->where(array('society_id'=>$soc))->join('society_documents','document_folder.id=society_documents.folder_id','inner')->count_all_results('document_folder');
		$ad = $this->db->where(array('is_admin'=>1))->where(array('society'=>$soc))->count_all_results('user_society');
		$pb = $this->db->where(array('is_paid'=>2,'society_id'=>$soc))->count_all_results('flat_invoice');
		$mcq = $this->db->select_sum('amount_paid')->group_by('invoice_month')->order_by('invoice_month DESC')->where(array('invoice_month'=>date('Y-m-01'),'society_id'=>$soc))->get('flat_invoice')->row();
		$mc = 0;
		if(!empty($mcq)){
			$mc = $mcq->amount_paid;
		}
		$cm = $this->db->where(array('society_id'=>$soc,'conv_type'=>1,'message_type'=>2))->count_all_results('members_messages');
		$gm = $this->db->where(array('society_id'=>$soc,'conv_type'=>1,'message_type'=>1))->count_all_results('members_messages');
		$iei1 = $this->db->order_by('id DESC')->select('sum(amount) as total')->group_by('trans_type')->where(array('society_id'=>$soc,'trans_type'=>1,'date_of_payment>='=>date('Y-m-d', strtotime('-30 days'))))->get('income_expense')->row();
		$iee2 = $this->db->order_by('id DESC')->select('sum(amount) as total')->group_by('trans_type')->where(array('society_id'=>$soc,'trans_type'=>2,'date_of_payment>='=>date('Y-m-d', strtotime('-30 days'))))->get('income_expense')->row();
		$ie1 = 0;
		$ie2 = 0;
		if(!empty($iei1)){
			$ie1 = $iei1->total;
		}
		if(!empty($iee2)){
			$ie2 = $iee2->total;
		}
		return array($rm,$am,$do,$ad,$pb,$mc,$cm,$gm,$ie1,$ie2);
	}
	public function helpdesk_replies($soc){
		$this->db->join('users','users.id=members_messages.sender_id','inner');
		return $this->db->order_by('members_messages.id DESC')->select('members_messages.id,firstname,lastname,message_subject')->where(array('society_id'=>$soc,'read_by_reciever'=>2))->get('members_messages');
	}
}