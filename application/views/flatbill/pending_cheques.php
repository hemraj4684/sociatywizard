<div id="breadcrumbs-wrapper" class="animated fadeIn">
<div class="row mb0">
<div class="col s12 m6 l6">
<h5 class="breadcrumbs-title">Pending Cheques</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('flatbill')?>">Maintenance Bill</a> / </li>
<li class="active">Pending Cheques</li>
</ol>
</div>
<div class="col s12 m6 l6">
<input class="filled-in check_all" name="user_select_all" id="user_select_all" type="checkbox">
<label id="user_select_all_label" for="user_select_all"></label>
<button data-id="1" class="waves-effect waves-light btn btn-small mb5 check-ok green"><i class="fa fa-check"></i> Cheque Cleared</button>
<button data-id="4" class="waves-effect waves-light btn btn-small mb5 check-ok red accent-3"><i class="fa fa-warning"></i> Cheque Not Cleared</button>
</div>
</div>
</div>
<div class="row height-600">
<div class="col s12">
<div class="card"><div class="auto-scroll card-content">
<table class="table data_list bordered"><thead><tr><th>#</th><th>Flat No</th><th>Bill Month</th><th>Date Of Payment</th><th>Cheque No</th><th>Cheque Date</th><th>Total Amount Due</th><th>Amount Recieved</th><th width="125">Action</th></tr></thead><tbody>
<?php
foreach ($data as $key => $value) {
	echo '<tr';
	if($value->cheque_status==='4'){
		echo ' class="red lighten-3"';
	}
	echo '><td><input value="'.$value->inv_id.'" class="filled-in cheque_select" id="inv_'.$key.'" type="checkbox"><label for="inv_'.$key.'"></label></td><td>';
	if(!empty($value->name)){
		echo h($value->name).' - ';
	}
	echo h($value->flat_no).'</td><td>'.date('F Y',strtotime($value->invoice_month));
	if(!empty($value->advance_month)){
		echo ' to '.date('F Y',strtotime($value->advance_month));
	}
	echo '</td><td>'.date('dS M Y',strtotime($value->date_of_payment)).'</td><td>'.h($value->cheque_no).'</td><td>';
	if(!empty($value->cheque_date)){
		echo date('dS M Y',strtotime($value->cheque_date));
	} else {
		echo 'N/A';
	}
	echo '</td><td><i class="fa fa-rupee"></i> '.h($value->total_amount).'/-</td><td><i class="fa fa-rupee"></i> ';
	if($value->is_paid==='1' || $value->is_paid==='2'){
		echo h($value->amount_paid);
	} else {
		echo h($value->cheque_amount);
	}
	echo '/-</td><td><a class="btn green block mb5 btn-small" onclick="'.invoice_popup($value->inv_id).'" href="#"><i class="mdi-action-view-list"></i> view bill</a><a class="btn green block btn-small" onclick="'.payment_details_popup($value->inv_id).'" href="#"><i class="mdi-action-view-list"></i> payment details</a></td>';
	echo '</tr>';
}
unset($value);
?>
</tbody></table>
</div>
</div>
</div>
</div>
<?php echo form_open(); form_close();?>