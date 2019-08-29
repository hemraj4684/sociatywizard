<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m12">
<h5 class="breadcrumbs-title">Add A New Member</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('registeredmembers')?>"><?=$this->p_var?></a> / </li>
<li class="active">Add A New Member</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12">
<div class="card"><div class="card-content">
<?php echo form_open('','id="new-user-form" autocomplete="off"'); ?>
<div class="row">
<div class="input-field col s12">
<button class="submit-btn btn indigo accent-3 right"><i class="mdi-content-add left"></i> Add</button>
</div>
<div class="input-field col m4 s12">
<input id="first_name" name="first_name" type="text" maxlength="50">
<label for="first_name">Firstname</label>
<p class="fn_err err-under"></p>
</div>
<div class="input-field col m4 s12">
<input id="last_name" name="last_name" type="text" maxlength="50">
<label for="last_name">Lastname</label>
<p class="ln_err err-under"></p>
</div>
<div class="input-field col m4 s12 center">
<span class="custom-form-label">Gender : </span>
<input id="male" name="gender" value="1" type="radio" checked><label for="male">Male</label> &nbsp;&nbsp;
<input id="female" name="gender" value="2" type="radio"><label for="female">Female</label>
<p class="gn_err err-under"></p>
</div>
<div class="input-field col m6 s12">
<input id="email" name="email" type="email">
<label for="email">Email ID</label>
<p class="em_err err-under"></p>
</div>
<div class="input-field col m6 s12">
<input id="mobile" name="mobile" type="text" maxlength="10">
<label for="mobile">Mobile Number</label>
<p class="mo_err err-under"></p>
</div>
<div class="input-field col m4 s12 clearfix">
<input type="checkbox" id="is_assoc" name="is_assoc" value="1" class="filled-in"><label for="is_assoc">Is Association Member</label>
</div>
<div class="input-field col m4 s12">
<input type="text" name="designation" id="desig" maxlength="25"><label for="desig">Designation</label><p class="err-under de_err"></p>
</div>
<div class="clearfix input-field col m5 s12">
<input name="flat_id" type="hidden" class="flat_id" value="" autocomplete="off">
<input class="flat_float" id="flat_no" name="flat_no" type="text" autocomplete="off">
<div>
</div>
<label for="flat_no">Flat No</label>
</div>
<div class="input-field col m4 s12">
<input id="owner" name="ot" value="1" type="radio" checked><label for="owner">Owner</label>
<input id="tenant" name="ot" value="2" type="radio"><label for="tenant">Tenant</label>
<p class="ot_err err-under"></p>
</div>
<div class="input-field col m3 s12">
<input type="checkbox" id="is_admin" name="is_admin" class="filled-in"><label for="is_admin">Make Admin</label>
</div>
</div>
<?php echo form_close(); ?>
</div>
</div>
</div>
</div>