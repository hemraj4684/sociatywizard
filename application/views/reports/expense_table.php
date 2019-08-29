<table class="white report-table data_list table bordered"><thead><tr><th>#</th><th>Particular</th><th>Amount</th><th>Paid To</th><th>Date Of Payment</th><th>Paid By</th><th>Cheque No</th></tr></thead><tbody>
<?php
$total = 0;
foreach ($data as $key => $value) {
	echo '<tr><td>'.++$key.'</td><td>'.h($value->name).'</td><td><i class="fa fa-rupee"></i> '.h($value->amount).'/-</td><td>'.h($value->giver_taker).'</td><td>'.date('dS M Y',strtotime($value->date_of_payment)).'</td><td>';
	if($value->payment_method==='2'){
		echo 'Cheque';
	} else {
		echo 'Cash';
	}
	echo '</td><td>'.h($value->cheque_no).'</td></tr>';
	$total = $total+$value->amount;
}
unset($value);
?>
</tbody>
</table><input name="total" class="total_value" value="<?=number_format($total)?>" type="hidden">