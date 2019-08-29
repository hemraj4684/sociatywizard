<?php
class Invoicemodel extends MY_Model {
	public function __construct() {
		parent::__construct();
	}
	public function get_monthly_collection($soc){
		return $this->db->query("SELECT sum(total_amount) as generateds,sum(amount_paid) as collected,invoice_month FROM flat_invoice WHERE society_id = ".$this->db->escape($soc)." GROUP BY invoice_month ORDER BY invoice_month DESC");
	}
	public function get_month_wise($month,$soc){
		$this->db->join('flats','flat_invoice.flat_id=flats.id','inner')->select('flat_invoice.id as inv_id,flat_id,amount_paid,is_paid,flat_no,total_amount,name,cheque_amount,advance_month,due_date,cheque_status,date_created');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->by_flat_society($soc);
		return $this->db->order_by('flat_invoice.id DESC')->where(array('invoice_month'=>$month))->get('flat_invoice');
	}
	public function not_received_monthwise($month){
		$this->db->join('wings','wings.id=flats.flat_wing','left')->select('name,flat_no,flats.id as flat_id');
		$this->db->where_not_in('flats.id',"SELECT flat_id FROM flats INNER JOIN flat_invoice ON flats.id=flat_invoice.flat_id WHERE invoice_month = ".$this->db->escape($month)." OR advance_month >= ".$this->db->escape($month),false);
		return $this->db->get('flats');
	}
	public function insert_invoice($first,$second){
		$this->db->trans_start();
		foreach ($first as $value1) {
			$this->db->insert('flat_invoice',$value1);
			$last_id = $this->db->insert_id();
			if(!empty($second)){
				foreach ($second as $key => $value) {
					$second[$key]['invoice_id'] = $last_id;
				}
				unset($value);
				$this->db->insert_batch('invoice_particular',$second);
			}
		}
		unset($value1);
		$this->db->trans_complete();
	}
	public function update_invoice($id,$first,$old,$new,$soc){
		$check = $this->db->limit(1)->select('is_paid')->get_where('flat_invoice',array('society_id'=>$soc,'id'=>$id));
		if($check->num_rows() > 0){
			$is_paid = $check->row()->is_paid;
			if($is_paid!=='1'){
				$this->db->set('total_amount','(total_amount-'.$old.')+'.$new,FALSE);
				$this->db->where(array('id'=>$id))->limit(1)->update('flat_invoice',$first);
			}
		}
	}
	public function get_all_by_user($id,$soc){
		$this->by_society($soc);
		return $this->db->order_by('id DESC')->get_where('flat_invoice',array('flat_id'=>$id));
	}
	public function get_all_by_users($id,$soc){
		$this->by_society($soc);
		return $this->db->where_in('flat_id',$id)->order_by('id DESC')->get_where('flat_invoice');
	}
	public function get_invoice_particulars($id){
		return $this->db->get_where('invoice_particular',array('invoice_id'=>$id));
	}
	public function get_invoice_one($id,$soc){
		return $this->db->limit(1)->order_by('id DESC')->get_where('flat_invoice',array('society_id'=>$soc,'id'=>$id));
	}
	public function get_bill_group($soc){
		return $this->db->get_where('bill_group',array('society_id'=>$soc));
	}
	public function get_groupwise_particulars($id){
		return $this->db->get_where('default_particulars',array('group_id'=>$id));
	}
	public function pending_bill($id=0,$soc){
		if($id!=0){
			$this->db->where(array('wings.id'=>$id));
		}
		$this->db->join('flats','flat_invoice.flat_id=flats.id','inner')->select('flats.id as flatid,flat_invoice.id as inv_id,owner_name,owner_number,flat_id,flat_no,invoice_month,total_amount,name,advance_month,due_date');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->by_flat_society($soc);
		return $this->db->order_by('flats.id DESC')->get_where('flat_invoice',array('is_paid'=>2));
	}
	public function get_bill_details($id,$soc){
		$this->db->join('flats','flat_invoice.flat_id=flats.id','inner')->select('flat_invoice.id as inv_id,advance_month,invoice_month,cheque_amount,due_date,flat_no,name,amount_paid,payment_method,total_amount,note,is_paid,cheque_no,cheque_date,date_of_payment,cheque_status');
		$this->db->join('wings','wings.id=flats.flat_wing','left');
		$this->by_flat_society($soc);
		return $this->db->order_by('flat_invoice.id DESC')->limit(1)->get_where('flat_invoice',array('flat_invoice.id'=>$id));
	}
	public function update_details($data,$id,$paying,$soc,$ie_entry){
		$this->db->trans_start();
		$get_total = $this->db->where(array('society_id'=>$soc,'id'=>$id))->select('total_amount,cheque_status,flat_id')->limit(1)->get('flat_invoice')->row();
		if(!empty($get_total)){
			$arrear = 0;
			$adv = 0;
			if($get_total->total_amount > $paying){
				$arrear = $get_total->total_amount-$paying;
			}
			if($get_total->total_amount < $paying){
				$adv = $paying-$get_total->total_amount;
			}
			if($data['is_paid']!=2){
				$data['bill_arrears'] = $arrear;
				$data['bill_advance_amount'] = $adv;
			} else {
				$data['bill_arrears'] = '0.00';
				$data['bill_advance_amount'] = '0.00';
			}
			if(($get_total->cheque_status!=4 && $data['payment_method']==='2' && $data['is_paid']==1) || $data['payment_method']==='1'){
				$data['amount_paid'] = $data['cheque_amount'];
				$data['cheque_amount'] = '0.00';
				$data['is_paid']=1;
			}
			if($data['payment_method']==='2' && $get_total->cheque_status==4){
				$data['cheque_status']=4;
			}
			if($this->db->select('id')->limit(1)->order_by('id DESC')->get_where('income_expense',array('bill_id'=>$id))->num_rows()>0){
				if($data['is_paid']==1){
					$this->db->limit(1)->order_by('id DESC')->where(array('bill_id'=>$id))->update('income_expense',$ie_entry);
				} else {
					$this->db->limit(1)->order_by('id DESC')->where(array('bill_id'=>$id))->delete('income_expense');
				}
			} else {
				if(!empty($ie_entry)){
					$this->load->model('flatmodel');
					$flatd = $this->flatmodel->get_id_name_one($get_total->flat_id)->row();
					if(!empty($flatd->wing_name)){
						$ie_entry['giver_taker'] = 'Flat No : '.$flatd->wing_name.'-'.$flatd->flat_no;
					} else {
						$ie_entry['giver_taker'] = 'Flat No : '.$flatd->flat_no;
					}
					$this->db->insert('income_expense',$ie_entry);
				}
			}
			$this->db->where(array('id'=>$id))->order_by('id DESC')->limit(1)->update('flat_invoice',$data);
		}
		$this->db->trans_complete();
	}
	public function update_details_multiple($pay,$ie,$id){
		$this->db->trans_start();
		$this->db->set('amount_paid','total_amount',FALSE);
		$this->db->where_in('id',$id)->update('flat_invoice',$pay);
		$this->db->insert_batch('income_expense',$ie);
		$this->db->trans_complete();
	}
	public function flatwise_collection($flats = null,$detail=1,$full=0,$soc){
		if(!empty($flats)){
			$this->db->where_in('flat_id',$flats);
		}
		$this->db->select('flat_id,SUM(amount_paid) as total_paid,SUM(total_amount) as total_received,SUM(credit_arrear) as c_arrear,SUM(debit_arrear) as d_arrear');
		if($full===0){
			$this->db->where(array('is_paid'=>1));
		} else {
			$this->db->where(array('is_paid!='=>1));
		}
		$this->db->group_by('flat_id');
		if($detail===1){
			$this->db->join('flats','flat_invoice.flat_id = flats.id','inner');
			$this->db->select('name,flat_no');
			$this->db->join('wings','wings.id=flats.flat_wing','left');
			$this->by_flat_society($soc);
		} else {
			$this->by_society($soc);
		}
		return $this->db->get('flat_invoice');
	}
	public function pending_cheques($soc){
		$this->by_flat_society($soc);
		$this->db->join('flats','flat_invoice.flat_id=flats.id','inner')->select('flat_invoice.id as inv_id,advance_month,invoice_month,flat_no,name,amount_paid,payment_method,total_amount,note,is_paid,cheque_no,cheque_date,date_of_payment,cheque_status,cheque_amount');
		$this->db->join('wings','wings.id=flats.flat_wing','left')->where(array('cheque_status'=>2,'payment_method'=>2))->or_where('cheque_status',4);
		return $this->db->get('flat_invoice');
	}
	public function update_cheques($ids,$status,$soc,$admin){
		if(!empty($ids)){
			$this->db->trans_start();
			if($this->verify_bill_belongs_to_society($ids,$soc)->num_rows()===count($ids)){
				$this->db->where_in('id',$ids)->limit(count($ids));
				$this->db->where(array('payment_method'=>2));
				$this->db->set('cheque_status',$status);
				if($status==='1'){
					$this->db->set('amount_paid','cheque_amount',FALSE);
					$this->db->set('is_paid','1');
				}
				$this->by_society($soc);
				$this->db->update('flat_invoice');
				if($status==='1'){
					$flat_ids = $this->get_flat_id_from_invoice($ids,$soc)->result();
					$fid = array();
					foreach ($flat_ids as $key => $value) {
						array_push($fid,$value->flat_id);
					}
					unset($value);
					$this->load->model('flatmodel');
					$flatd = $this->flatmodel->get_id_name_by_flats($fid)->result();
					$invs = $this->get_invoice_by_ids($ids,'id,amount_paid,date_of_payment,cheque_no,flat_id')->result_array();
					$to_ins = array();
					foreach ($invs as $key => $value) {
						$push = array('payment_method'=>2,'amount'=>$value['amount_paid'],'society_id'=>$this->society,'added_by'=>$this->session->user,'date_of_payment'=>$value['date_of_payment'],'cheque_no'=>$value['cheque_no'],'trans_type'=>1,'bill_id'=>$value['id']);
						foreach ($flatd as $val) {
							if($value['flat_id']===$val->id){
								if(!empty($val->wing_name)){
									$push['giver_taker'] = 'Flat No : '.$val->wing_name.'-'.$val->flat_no;
								} else {
									$push['giver_taker'] = 'Flat No : '.$val->flat_no;
								}
								break;
							}
						}
						unset($val);
						array_push($to_ins,$push);
					}
					unset($value);
					$this->db->insert_batch('income_expense',$to_ins);
				} else {
					$this->db->order_by('id DESC')->where_in('bill_id',$ids)->delete('income_expense');
				}
			}
			$this->db->trans_complete();
		}
	}
	public function get_invoice_by_ids($ids,$select){
		return $this->db->where_in('id',$ids)->select($select)->get('flat_invoice');
	}
	public function get_flat_id_from_invoice($ids,$soc){
		$this->by_society($soc);
		return $this->db->select('flat_id')->where_in('id',$ids)->get('flat_invoice');
	}
	public function verify_bill_belongs_to_society($ids,$soc){
		$this->by_society($soc);
		return $this->db->select('id')->where_in('id',$ids)->get('flat_invoice');
	}
	public function delete_bills($ids = array()){
		$this->db->where_in('id',$ids)->delete('flat_invoice');
	}
	public function bill_sum_before_date($date,$id){
		return $this->db->where(array('flat_id'=>$id,'invoice_month<'=>$date,'is_paid'=>2))->order_by('id DESC')->limit(1)->select_sum('total_amount')->get('flat_invoice');
	}
	public function remove_late_interest($id,$soc){
		$this->by_society($soc);
		$this->db->set('interest_applied_times',0);
		$this->db->set('total_amount','total_amount-late_fee_amonut',FALSE);
		$this->db->set('late_fee_amonut',0);
		$this->db->where(array('id'=>$id,'is_paid'=>2))->limit(1)->update('flat_invoice');
	}
}