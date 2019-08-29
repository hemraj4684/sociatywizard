<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m8">
<h5 class="breadcrumbs-title"><i class="fa fa-file-text-o"></i> Maintainence Bill</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('me')?>">Home</a> / </li>
<li class="active">Maintainence Bill</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600"><div class="col s12">
<div class="card"><div class="card-content"><div class="row">
<table class="bordered user_data">
<thead><th>#</th><th>Bill Month</th><th>Status</th><th>Total Amount</th><th>Amount Paid</th><th>Due Date</th><th>Date Received</th><th width="150">Action</th></thead><tbody>
<?php
if(empty($bills)){
echo '<tr><td colspan="8" class="center"><i>No Data Available</i></td></tr>';
} else {
foreach ($bills as $key => $value) {
	echo '<tr><td>'.++$key.'</td><td>'.date('F, Y',strtotime($value->invoice_month));
	if(!empty($value->advance_month)){echo ' to '.date('F, Y',strtotime($value->advance_month));}
	echo '</td><td>';
	if($value->is_paid==='2'){
		echo '<span class="white-text accent-3 auto-cursor btn btn-small red"><i class="fa fa-warning"></i> Not Paid</span>';
	} else if($value->is_paid==='1'){
		echo '<span class="white-text accent-3 auto-cursor btn btn-small indigo"><i class="fa fa-check"></i> Paid</span>';
	} else if($value->cheque_status==='4') {
		echo '<span class="white-text accent-3 auto-cursor btn btn-small red accent-4"><i class="fa fa-warning"></i> Falied</span>';
	} else {
		echo '<span class="white-text accent-3 auto-cursor btn btn-small orange"><i class="fa fa-clock-o"></i> Pending</span>';
	}
	echo '</td><td><i class="fa fa-rupee"></i> '.$value->total_amount.'/-</td><td><i class="fa fa-rupee"></i> ';
	if($value->is_paid==='1' || $value->is_paid==='2'){
		echo h($value->amount_paid);
	} else {
		echo h($value->cheque_amount);
	}
	echo '/-</td><td>';
	echo date('dS M Y',strtotime($value->due_date));
	echo '</td><td>'.date('dS M Y',strtotime($value->date_created)).'</td><td><a class="btn green mb5 block btn-small" onclick="'.go_to_mybill($value->id).'" href="#"><i class="mdi-action-view-list"></i> view bill</a>';
	if($value->is_paid==='2' || $value->cheque_status==='4'){
		echo '<a href="'.base_url('me/paybill/'.$value->id).'" class="btn btn-small block" target="_blank">Pay Now</a>';
	}
	echo '</td></tr>';
}
unset($bill);
}
?>
</tbody></table></div></div></div></div></div>