<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m12 l12">
<h5 class="breadcrumbs-title">Bill History</h5>
<ol class="breadcrumb">
<?php if($ref && $ref === 'arrears') { ?>
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?php echo base_url('flatbill'); ?>">Maintenance Bill</a> / </li>
<li><a href="<?php echo base_url('flatbill/arrears'); ?>">Arrears</a> / </li>
<li class="active">Bill History</li>
<?php } else { ?>
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?php echo base_url('flats'); ?>">Flats Management</a> / </li>
<li class="active">Bill History</li>
<?php } ?>
</ol>
</div>
</div>
</div>
<div class="row height-600">
<div class="col s12">
<div class="card">
<div class="card-content">
<div class="card-title">Bill History Of Flat <?php if(!empty($flat->flat_wing)) { echo $flat->wing_name.' - '; } echo $flat->flat_no; ?></div>
<table class="bordered user_data">
<thead><th>#</th><th>Bill Month</th><th>Status</th><th>Total Amount</th><th>Amount Paid</th><th>Due Date</th><th>Date Sent</th><th width="150">Action</th></thead><tbody>
<?php
if(empty($invoices)){
echo '<tbody><tr><th colspan="8" class="center">No Bill History Found!</th></tr></tbody>';
} else {
foreach ($invoices as $key => $value) {
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
	echo '</td><td>'.date('dS M Y',strtotime($value->date_created)).'</td><td><a class="btn green mb5 block btn-small" onclick="'.invoice_popup($value->id).'" href="#"><i class="mdi-action-view-list"></i> view bill</a><a class="btn green block btn-small" onclick="'.payment_details_popup($value->id).'" href="#"><i class="mdi-action-view-list"></i> payment details</a></td></tr>';
}
unset($value);	
}
?>
</tbody></table>
</div>
</div>
</div>
</div>