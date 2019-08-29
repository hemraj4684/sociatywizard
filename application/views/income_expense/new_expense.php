<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12">
<h5 class="breadcrumbs-title"><i class="fa fa-money"></i> Add <?=($get=='income') ? 'Income' : 'Expense'?></h5>
<ol class="breadcrumb">
<?php if($get=='income') { ?>
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('incomeexpense')?>">Income & Expense</a> / </li>
<li><a href="<?=base_url('incomeexpense/expense_list?type=income')?>">Incomes</a> / </li>
<li class="active">Add Income</li>
<?php } else { ?>
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('incomeexpense')?>">Income & Expense</a> / </li>
<li><a href="<?=base_url('incomeexpense/expense_list')?>">Expenses</a> / </li>
<li class="active">Add Expense</li>
<?php } ?>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600"><div class="col s12">
<div class="card"><div class="card-content"><div class="row">
<?=form_open('','id="expense_form"')?>
<div class="input-field col s12">
<button type="submit" class="submit-btn btn btn-small blue right"><i class="fa fa-save"></i> Save</button>
</div>
<div class="input-field col s12 m6 l4">

<select name="note">
<option value="0">Select Particular</option>
<?php
	foreach($drop as $key => $value){
	 	echo '<option value="'.$value->id.'">'.h($value->name).'</option>';
	}
	unset($value);
?>
</select>

</div>
<div class="input-field col s12 m6 l4">
	<input type="text" id="amount" name="amount"><label for="amount">Amount</label><p class="err-under amt-err"></p>
</div>
<div class="input-field col s12 m6 l4">
	<input type="text" id="date" class="datepicker" name="date" value="<?=date('d-m-Y')?>"><label for="date">Date of payment</label><p class="err-under date-err"></p>
</div>
<div class="input-field col s12 m6 l4">
	<input type="text" id="payee" name="payee" maxlength="50"><label for="payee"><?=($get=='income') ? 'Received From' : 'Paid To'?></label><p class="err-under pay-err"></p>
</div>
<div class="input-field col s12 m6 l4">
	<input type="radio" id="pm1" name="pm" value="1"><label for="pm1">Cash</label>
	<input type="radio" id="pm2" name="pm" value="2" checked><label for="pm2">Cheque</label>
	<input type="radio" id="pm3" name="pm" value="3"><label for="pm3">Other</label>
	<p class="err-under pm-err"></p>
</div>
<div class="input-field col s12 m6 l4">
	<input type="text" id="cheque_no" name="cheque_no" maxlength="50"><label for="cheque_no">Cheque Number</label><p class="err-under cheque-err"></p>
</div>
<?php if($get!='income'): ?>
<div class="input-field col s12">
	<b>Expense Authorised By</b><?php
		foreach ($assocs as $key => $value) {
			echo '<div class="mb5"><input name="authorised" type="radio" id="assoc-'.$value->id.'" value="'.$value->id.'"><label for="assoc-'.$value->id.'">'.h($value->firstname.' '.$value->lastname).'</label></div>';
		}
		unset($value);
	?>
</div>
<?php endif; ?>
<input type="hidden" name="trans" class="trans" value="<?=($get=='income') ? '1' : '2'?>">
<?=form_close()?>
</div>
</div>
</div>
</div>
</div>