<!-- <div id="breadcrumbs-wrapper" class="animated fadeIn">
<div class="row mb0">
<div class="col s12 m12 l12">
<h5 class="breadcrumbs-title">Add Flats</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?php echo base_url('flats'); ?>">Flats Management</a> / </li>
<li class="active">Add Flats</li>
</ol>
</div>
</div>
</div> -->
<div class="row mt10 height-600">
<?php echo form_open('','autocomplete="off" id="add-flat-form" class="col s12"'); ?>
<div class="card animated fadeIn"><div class="card-content"><div class="row mb0">
<div class="col s12">
</div>
</div><div class="row">
<div class="clearfix input-field col mb15 s12"><a class="right btn mb10 red accent-3" href="<?php echo base_url('flats'); ?>"><i class="mdi-navigation-close left"></i> Cancel</a><span class="right"> &nbsp; </span><button type="submit" class="btn right indigo accent-3 submit-btn"><i class="mdi-content-add left"></i> Add</button></div>
<div class="input-field col m6 s12">
<input id="flat_no" name="flat_no" type="text">
<label for="flat_no">Flat No</label>
<div class="err-under name-err"></div>
</div>
<div class="input-field col m6 s12">
<select name="flat_wing" id="flat_wing">
<option value="0">Flat Wing</option>
<?php
foreach ($wings as $key => $value) {
echo '<option value="'.$value->id.'">'.$value->name.'</option>';
}
unset($value);
?>
</select>
<div class="err-under wing-err"></div>
</div>
<div class="input-field col m4 s12">
<input id="sq_foot" name="sq_foot" type="text">
<label for="sq_foot">Square Foot</label>
</div>
<div class="input-field col m4 s12">
<input id="intercom" name="intercom" type="text">
<label for="intercom">Intercom</label>
</div>
<div class="input-field col m4 s12">
<input type="radio" name="status" value="1" class="with-gap" id="owner_s" checked><label for="owner_s">Owner</label>
<input type="radio" name="status" value="2" class="with-gap" id="rent_s"><label for="rent_s">Rented</label>
<div class="err-under status-err"></div>
</div>
<div class="col s12 clearfix"><hr>
<h6 class="font-16 bold-500">Owner Account</h6><hr class="mb0">
</div>
<div class="input-field existing-user-display col s12 l6 offset-l3">
<div class="card-panel">
<p class="red-text bold-500 center text-accent-3 font-16">An account with same mobile number already exists on Society Wizard.</p>
<p class="mb5">Click <i class="fa fa-plus"></i> button if this is the correct person.</p>
<div class="exist_data"></div>
</div>
</div>
<div class="input-field col m4 s12 clearfix">
<input id="owner_fn" name="owner_fn" type="text" maxlength="50">
<label for="owner_fn">Owner Firstname</label>
<div class="err-under owner_fn_err"></div>
</div>
<div class="input-field col m4 s12">
<input id="owner_ln" name="owner_ln" type="text" maxlength="25">
<label for="owner_ln">Owner Lastname</label>
<div class="err-under owner_ln_err"></div>
</div>
<div class="input-field col m4 s12">
<input id="contact" name="contact" type="text" maxlength="10">
<label for="contact">Mobile Number</label>
<div class="err-under no-err"></div>
</div>
</div></div></div>
<?php echo form_close(); ?>
</div>