<div id="breadcrumbs-wrapper" class="animated fadeIn">
<div class="row mb0">
<div class="col s12 m6">
<h5 class="breadcrumbs-title">Pending Bills</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('flatbill')?>">Maintenance Bill</a> / </li>
<li class="active">Pending Bills</li>
</ol>
</div>
<div class="col s12 m6">
<input class="filled-in check_all" name="user_select_all" id="user_select_all" type="checkbox"><label id="user_select_all_label" for="user_select_all"></label>
<button class="waves-effect waves-light btn red accent-3 btn-small mb-reminder-btn"><i class="fa fa-envelope"></i> Send Reminder</button>
<button class="waves-effect waves-light btn green btn-small payment-done-multi"><i class="fa fa-check"></i> Clear Payment</button>
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12">
<div class="card"><div class="card-content table-cover">
<table class="table data_list bordered"><thead><tr><th>#</th><th>Flat No</th><th>Owner</th><th>Contact Number</th><th>Total Amount Due</th><th>Bill Month</th><th>Due Date</th><th width="150px">Action</th></tr></thead><tbody>
<?php
foreach ($data as $key => $value) {
	echo '<tr><td><input data-invoice="'.$value->inv_id.'" data-number="'.h($value->owner_number).'" data-duedate="'.date('d M Y',strtotime($value->due_date)).'" data-amount="'.number_format($value->total_amount).'" data-month="'.date('F Y',strtotime($value->invoice_month)).'" data-name="'.h($value->owner_name).'" value="'.$value->flatid.'" class="sms_ok filled-in" id="chk_'.$key.'" type="checkbox"><label for="chk_'.$key.'"></label> '.++$key.'</td><td>';
	if(!empty($value->name)){echo h($value->name).' - ';}
	echo h($value->flat_no).'</td><td>'.h($value->owner_name).'</td><td>'.h($value->owner_number).'</td><td><i class="fa fa-rupee"></i> '.number_format($value->total_amount).'/-</td><td>'.date('F Y',strtotime($value->invoice_month));
	if(!empty($value->advance_month)){
		echo ' to '.date('F Y',strtotime($value->advance_month));
	}
	echo '</td><td>'.date('d M Y',strtotime($value->due_date)).'</td><td><a class="mb5 btn block green btn-small" onclick="'.invoice_popup($value->inv_id).'" href="#"><i class="mdi-action-view-list"></i> view bill</a><a class="btn green block btn-small" onclick="'.payment_details_popup($value->inv_id).'" href="#"><i class="fa fa-plus"></i> add payment details</a> </td></tr>';
}
unset($value);
?>
</tbody>
</table>
</div>
</div>
</div>
</div>
<?=form_open().form_close()?>