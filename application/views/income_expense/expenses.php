<?php
$type = strtolower($this->input->get('type'));
?>
<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m6">
<?php if($type==='income'){ $is_lbl='incomes';?>
<h5 class="breadcrumbs-title"><i class="fa fa-money"></i> Incomes</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('incomeexpense')?>">Income & Expense</a> / </li>
<li class="active">Incomes</li>
</ol>
<?php } else { $is_lbl='expenses';?>
<h5 class="breadcrumbs-title"><i class="fa fa-money"></i> Expenses</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('incomeexpense')?>">Income & Expense</a> / </li>
<li class="active">Expenses</li>
</ol>
<?php } ?>
</div>
<div class="col s12 m6">
<input type="checkbox" class="filled-in check_all" id="user_select_all"><label for="user_select_all"></label>
<button class="btn btn-small red accent-3 delete-ie"><i class="fa fa-close"></i> Delete</button>
<?php if($type==='income'){ ?>
<a href="<?=base_url('incomeexpense/add_expense?type=income')?>" class="btn btn-small purple"><i class="fa fa-plus"></i> Add Income</a>
<?php } else { ?>
<a href="<?=base_url('incomeexpense/add_expense')?>" class="btn btn-small purple"><i class="fa fa-plus"></i> Add Expense</a>
<?php } ?>
</div>
</div>
</div>
<div class="row mt10 height-600">

<div class="card"><div class="card-content"><div class="row mb0">
<div class="col s12 center"><h3 class="font-16 mt0">Below is the list of <?=$is_lbl?> <span class="date_to_ch">since last 30 days</span> | <b>Total :</b> <i class="bold-500 fa fa-rupee"></i> <span class="total_calc_display bold-500"></span><span class="bold-500">/-</span></h3></div>
<div class="col s12 mt10">
<?=form_open('','id="search_expense"')?>
<?php
if($type==='income'){
echo '<input name="trans" type="hidden" value="1" class="trans">';
} else {
echo '<input name="trans" type="hidden" value="2" class="trans">';
}
?>
<div class="col s12 m4 offset-l2 l3 input-field"><input type="text" name="date_from" class="datepicker" id="ex_df"><label for="ex_df">Select Date From</label><p class="err-under from-err"></p></div>
<div class="col s12 m4 l3 input-field"><input type="text" class="datepicker" name="date_to" id="ex_dt"><label for="ex_dt">Select Date To</label><p class="err-under to-err"></p></div>
<div class="col s12 m4 l3 input-field"><button class="btn submit-btn indigo accent-3">Search</button></div>
<?=form_close()?>
</div>
</div></div></div>

<div class="col s12"><div class="result">
<div class="progress red lighten-3"><div class="indeterminate red"></div></div><div class="progress blue lighten-3"><div class="indeterminate blue"></div></div>
</div></div>
</div>