<div id="breadcrumbs-wrapper" class="animated fadeIn">
<div class="row mb0">
<div class="col s12 m8 l8">
<h5 class="breadcrumbs-title">Monthly Bill</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('flatbill')?>">Maintenance Bill</a> / </li>
<li class="active">Monthly Bill</li>
</ol>
</div>
<div class="col s12 m4 l4">
<input type="checkbox" class="filled-in check_all" id="user_select_all"><label for="user_select_all"></label>
<button class="btn btn-small red accent-3 remove_bill"><i class="fa fa-close"></i> Delete</button>
<!-- <a class="btn-small white-text waves-effect waves-light purple btn" href="<?php echo base_url('flatbill/not_received/'.$month.'/'.$year); ?>"><i class="fa fa-building-o"></i> Flats Not Recieved Bill For This Month</a> -->
</div>
</div>
</div>
<div class="row height-600">
<div class="col s12">
<div class="card"><div class="card-content auto-scroll">
<div class="card-title">Bill Month : <?=date('F Y',strtotime($month_of))?></div>
<table class="table data_list highlight bordered"><thead><tr><th>#</th><th>Flat No</th><th>Total Amount</th><th>Amount Paid</th><th>Status</th><th>Due Date</th><th>Date Sent</th><th width="125">Action</th></tr></thead><tbody>
<?php
foreach ($data as $key => $value) {
	$is_paid = $value->is_paid;
	echo '<tr><td><input class="filled-in file_selected" type="checkbox" value="'.h($value->inv_id).'" id="user_select_'.$key.'"><label for="user_select_'.$key.'"></label></td><td>';
	if(!empty($value->name)){
		echo h($value->name).' - ';
	}
	echo h($value->flat_no).'</td><td><i class="fa fa-rupee"></i> '.$value->total_amount.'/-</td><td><i class="fa fa-rupee"></i> ';
	if($is_paid==='1' || $is_paid==='2'){
		echo h($value->amount_paid);
	} else {
		echo h($value->cheque_amount);
	}
	echo '/-</td><td ';
	if($is_paid==='2'){
		echo 'data-filter="Not Paid"><span class="white-text accent-3 auto-cursor btn btn-small red"><i class="fa fa-warning"></i> Not Paid</span>';
	} else if($is_paid==='1'){
		echo 'data-filter="Paid"><span class="white-text accent-3 auto-cursor btn btn-small indigo"><i class="fa fa-check"></i> Paid</span>';
	} else if($value->cheque_status==='4') {
		echo 'data-filter="Not Paid"><span class="white-text accent-3 auto-cursor btn btn-small red accent-4"><i class="fa fa-warning"></i> Falied</span>';
	} else {
		echo 'data-filter="Not Paid"><span class="white-text accent-3 auto-cursor btn btn-small orange"><i class="fa fa-clock-o"></i> Pending</span>';
	}
	echo '</td><td>'.date('dS M Y',strtotime($value->due_date)).'</td><td>'.date('dS M Y',strtotime($value->date_created)).'</td><td><a class="btn block mb5 green btn-small" onclick="'.invoice_popup($value->inv_id).'" href="#"><i class="mdi-action-view-list"></i> view bill</a><a class="btn green block btn-small" onclick="'.payment_details_popup($value->inv_id).'" href="#"><i class="mdi-action-view-list"></i> payment details</a></td></tr>';
}
unset($value);
?>
<tfoot><tr><td></td><td></td><td></td><td></td><td>All</td><td></td><td></td><td></td></tr></tfoot>
</tbody></table>
</div>
</div>
</div>
</div>
<?php echo form_open();form_close(); ?>