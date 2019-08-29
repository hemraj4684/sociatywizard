<?php
$year = date('Y');
?>
<div id="breadcrumbs-wrapper" class="animated fadeIn">
<div class="row mb0">
<div class="col s12 m12 l12">
<h5 class="breadcrumbs-title">Create Bill</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('flatbill')?>">Maintenance Bill</a> / </li>
<li class="active">Create Bill</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12"><?=$this->session->flashdata('invoice_created')?></div>
<div class="col s12">
<div class="card"><div class="card-content">
<?php echo form_open('','id="invoice-form" class="row"'); ?>
<div class="col s12">
<h5 class="left">Create Bill</h5>
<button type="submit" class="btn indigo accent-3 right submit-btn"><i class="mdi-content-send right"></i> Send</button>
</div>
<hr>
<div class="row mb0">
<div class="input-field col m6 l3 s12">
<label for="month" class="active">Bill Month</label>
<select name="month" id="month">
<option value="0">Select Month</option>
<option value="01">January</option><option value="02">February</option><option value="03">March</option>
<option value="04">April</option><option value="05">May</option><option value="06">June</option>
<option value="07">July</option><option value="08">August</option><option value="09">September</option>
<option value="10">October</option><option value="11">November</option><option value="12">December</option>
</select>
</div>
<div class="input-field col m6 l3 s12">
<label for="year" class="active">Bill Year</label>
<select name="year" id="year">
<option value="<?php echo $year-1; ?>"><?php echo $year-1; ?></option>
<option value="<?php echo $year; ?>" selected><?php echo $year; ?></option>
</select>
</div>
<div class="input-field col m6 l3 s12">
<label for="due_date">Due Date</label>
<input type="text" name="due_date" id="due_date" value="<?=date('d-m-Y')?>" class="datepicker">
</div>
<div class="input-field col m6 l3 s12">
<label for="fine">Fine</label>
<input id="fine" class="invoice-p-amount input-text" name="fine" type="text" value="0">
</div>
<div class="upto_field" style="display:none">
<div class="input-field col m6 l3 s12">
<label for="to_month" class="active">Bill Month Upto</label>
<select name="to_month" id="to_month">
<option value="0">Select Month</option>
<option value="01">January</option><option value="02">February</option><option value="03">March</option>
<option value="04">April</option><option value="05">May</option><option value="06">June</option>
<option value="07">July</option><option value="08">August</option><option value="09">September</option>
<option value="10">October</option><option value="11">November</option><option value="12">December</option>
</select>
</div>
<div class="input-field col m6 l3 s12">
<label for="to_year" class="active">Bill Year Upto</label>
<select name="to_year" id="to_year">
<option value="<?php echo $year-1; ?>"><?php echo $year-1; ?></option>
<option value="<?php echo $year; ?>" selected><?php echo $year; ?></option>
<option value="<?php echo $year+1; ?>"><?php echo $year+1; ?></option>
</select>
</div>
</div>
<div class="col mb15 s12 clearfix m9">
<!-- <button type="button" class="btn-flat grey lighten-1 adv-m-btn bold-500 btn-small"><i class="fa fa-calendar-plus-o"></i> Advance Month</button> -->
</div>
</div>
<div class="row">
<div class="col m6 s12">
<div class="input-field">Bill Amount : <i class="fa fa-rupee"></i> <span id="total">0.00</span>/-</div>
<div class="input-field">
<select name="group" id="group">
<option value="0">Select Bill Group</option><?php foreach ($bill_group as $key => $value) {
echo '<option value="'.$value->id.'">'.h($value->name).'</option>';
}unset($value);?>
</select>
</div>
<div class="input-field"><table class="bordered striped particular-area-init"><tbody></tbody></table><table class="particular-area"><tbody></tbody></table></div>
</div>
</div>
<?php echo form_close(); ?>
</div>
</div>
</div>
</div>