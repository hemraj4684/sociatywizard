<?php
class Mobileappbill extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){header('HTTP/1.0 403 Forbidden');exit();}
		$this->load->model('mobilemodel');
	}
	public function index(){
		$flat = array();
		if($this->input->server('HTTP_SOCIETY')){
			$flat = $this->mobilemodel->get_user_flats($this->input->server('HTTP_USER_ID'),$this->input->server('HTTP_SOCIETY'))->result();
		}
		if(!empty($flat)){
			$flat_id = array();
			foreach ($flat as $key => $value) {
				array_push($flat_id, $value->flat_id);
			}
			unset($value);
			if(!empty($flat_id)){
				$invoice = $this->mobilemodel->get_invoice_for_flats($flat_id)->result();
				if(!empty($invoice)){
					$flat_db = $this->mobilemodel->flat_basic_by_ids($flat_id)->result();
					echo '<div class="row"><div class="col s12">';
					$lastflat = '';
					$lastkey = count($invoice)-1;
					foreach($flat_db as $val) {
						$dues = 0;
						echo '<table class="mb0 mt10"><tr class="indigo lighten-4"><th class="font-16">';
						if(!empty($val->name)){
							echo h($val->name).' - ';
						}
						echo h($val->flat_no).' : Pending Bills</th></tr></table>';
						$total_p = 0;
						foreach ($invoice as $key => $value) {
							if($value->flat_id===$val->id){
								if($key===0 || $lastflat!==$val->id){
									echo '<table class="table bordered"><thead><th>Bill Month</th><th>Due Date</th><th>Amount Due</th><th>Details</th></thead><tbody>';
								}
								if($value->is_paid!=='1'){
									$dues = $dues+$value->total_amount;
									echo '<tr><td>'.date('M Y',strtotime($value->invoice_month));
									if(!empty($value->advance_month)){
										echo '<br> to '.date('M Y',strtotime($value->advance_month));
									}
									echo '</td><td>'.date('dS M Y',strtotime($value->due_date)).'</td><td><i class="fa fa-rupee"></i> '.number_format($value->total_amount).'/-</td><td><a href="my_bill_view.html?id='.$value->id.'" class="btn btn-small blue white-text center-block block">view</a><!--<br><a href="#" data-url="'.base_url('paymentgateway/paybill/'.$value->id.'/'.$this->input->server('HTTP_SOCIETY').'/'.$this->input->server('HTTP_USER_ID')).'" class="btn btn-small center-block block white-text purple pg-btn white-space">Pay Now</a>--></td></tr>';
								}
								if($key===$lastkey){
									echo '</tbody></table>';
								}
								$lastflat = $value->flat_id;
							}
						}
						unset($value);
						echo '<table class="table bordered"><tbody><tr><th width="50%">Total Dues</th><td><i class="fa fa-rupee"></i> '.number_format($dues,2).'/-</td></tr></tbody></table>';
					}
					unset($val);
					$lastflat = '';
					$noD=0;
					foreach($flat_db as $val) {
						$dues = 0;
						echo '<table class="mb0 mt10"><tr class="pink lighten-4"><th class="font-16">';
						if(!empty($val->name)){
							echo h($val->name).' - ';
						}
						echo h($val->flat_no).' : Bill History</th></tr></table>';
						foreach ($invoice as $key => $value) {
							if($value->flat_id===$val->id){
								if($key===0 || $lastflat!==$val->id){
									echo '<table class="table bordered"><thead><th>Bill Month</th><th>Bill Amount</th><th>Amount Paid</th><th>Reciept</th></thead><tbody>';
								}
								if($value->is_paid==='1'){
									$noD = 1;
									$dues = $dues+$value->total_amount;
									echo '<tr><td>'.date('M Y',strtotime($value->invoice_month));
									if(!empty($value->advance_month)){
										echo '<br> to '.date('M Y',strtotime($value->advance_month));
									}
									echo '</td><td><i class="fa fa-rupee"></i> '.number_format($value->total_amount).'/-</td><td><i class="fa fa-rupee"></i> '.number_format($value->total_amount).'/-</td><td><a href="my_bill_view.html?id='.$value->id.'" class="btn btn-small blue white-text center-block block">view</a></td></tr>';
								}
								if($key===$lastkey){
									echo '</tbody></table>';
								}
								$lastflat = $value->flat_id;
							}
						}
						unset($value);
						if($noD==0){
							echo '<table class="table bordered"><tr><td class="center">No Data Available</td></tr></table>';
						}
						$noD=0;
					}
					unset($val);
					echo '</div></div>';
				} else {
					echo '<div class="na-data">No Data Available</div>';
				}
			}
		} else {
			echo '<div class="na-data">No Data Available</div>';
		}
	}
	public function view_bill($id){
		if(is_valid_number($id)){
			$inv = $this->mobilemodel->get_invoice_one($id,$this->input->server('HTTP_SOCIETY'))->row();
			$parts = array();
			if(!empty($inv)){
				$parts = $this->mobilemodel->get_invoice_particulars($id)->result();
				$info = $this->mobilemodel->society_info($this->input->server('HTTP_SOCIETY'))->row();
				echo '<div class="col s12"><div class="card"><div class="card-content">';
				echo '<div class="card-title center">'.h($info->society_name).'</div>';
				if(!empty($info->registration_number)){
					echo '<p class="center">'.h($info->society_address).'</p><p class="center mb15">Registration NO : '.h($info->registration_number).'</p>';
				} else {
					echo '<p class="center mb15">'.h($info->society_address).'</p>';
				}
				echo '<p class="mb5"><b>Month Of : </b>'.date('M Y',strtotime($inv->invoice_month)).'</p>';
				if($inv->is_paid==='2'){
					echo '<p><b>Due Date : </b>'.date('dS M Y',strtotime($inv->due_date)).'</p>';
				}
				echo '<table class="table bordered"><tbody>';
				foreach ($parts as $key => $part) {
					echo '<tr><td>'.++$key.'</td><td>'.h($part->particulars).'</td><td class="right-align"><i class="fa fa-rupee"></i> '.h($part->amount).'/-</td></tr>';
				}
				unset($part);
				if($inv->late_fee_amonut>0){
					echo '<tr><th colspan="2">Late Payment Interest</th><td class="right-align"><i class="fa fa-rupee"></i> '.number_format($inv->late_fee_amonut,2).'/-</td></tr>';
				}
				if($inv->debit_arrear>0){
					echo '<tr><th colspan="2">Arrears</th><td class="right-align"><i class="fa fa-rupee"></i> '.h($inv->debit_arrear).' CR</td></tr>';
				}
				if($inv->credit_arrear>0){
					echo '<tr><th colspan="2">Arrears</th><td class="right-align"><i class="fa fa-rupee"></i> '.number_format($inv->credit_arrear).' DB</td></tr>';
				}
				echo '<tr><th colspan="2">Total Amount</th><td class="right-align"><i class="fa fa-rupee"></i> '.h($inv->total_amount).'/-</td></tr>';
				if($inv->is_paid!=='2'){
					echo '<tr><th colspan="2">Total Amount Paid</th><td class="right-align"><i class="fa fa-rupee"></i> '.h($inv->amount_paid).'/-</td></tr>';
				}
				echo '</tbody></table>';
				if($inv->is_paid==='1'){
					echo '<img style="position:absolute;width:100px;top:0;left:0;" src="'.base_url('assets/images/paid.png').'">';
				}
				echo '</div></div></div>';
			}
		}
	}
}