<table class="data_list highlight"><thead><tr><th>#</th><th><?php if($type==1){echo 'Income';}else{echo 'Expense';}?> Particulars</th><th>Amount</th><th>Paid By</th><th><?php if($type==1){echo 'Received From';}else{echo 'Paid To';}?></th><th>Date Of Payment</th><th>Cheque No</th><th>Prepared By</th><th>Action</th></tr></thead><tbody>
<?php
$total = 0;
foreach ($data as $key => $value) {
	echo '<tr><td><input value="'.$value->id.'" class="file_selected filled-in" type="checkbox" id="chk_'.$key.'"><label for="chk_'.$key.'"></label></td><td>';
	if(empty($value->bill_id)){echo h($value->name);} else {echo 'Maintenance Bill';}
	echo '</td><td><i class="fa fa-rupee"></i> '.h($value->amount).'/-</td><td>';
	$total = $total + $value->amount;
	if($value->payment_method==='2'){
		echo 'Cheque';
	} else if($value->payment_method==='1'){
		echo 'Cash';
	} else {
		echo 'Other';
	}
	echo '</td><td>'.h($value->giver_taker).'</td><td>'.h($value->date_of_payment).'</td><td>'.h($value->cheque_no).'</td><td>'.h($value->firstname.' '.$value->lastname).'</td><td>';
	if(empty($value->bill_id)){
		echo '<div><button class="btn orange btn-small darken-2 block center-block" onclick="'.ie_edit_popup($value->id).'"><i class="fa fa-edit"></i> edit</button></div>';
		if($type==2){echo '<div><button class="btn btn-small green block center-block voucher-btn" onclick="'.js_popup('incomeexpense/voucher/'.$value->id).'"><i class="fa fa-file-text"></i> voucher</button></div>';}
	}
	echo '</td></tr>';
}
unset($value);
?>
<tfoot><tr><td></td><td></td><td></td><td>All</td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
</tbody></table>
<input type="hidden" class="total_calc" value="<?=number_format($total)?>">