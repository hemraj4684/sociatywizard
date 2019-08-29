<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m12 l12">
<h5 class="breadcrumbs-title">Edit Flat</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?php echo base_url('flats'); ?>">Flats Management</a> / </li>
<li class="active">Edit Flat</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600">
<?php echo form_open('','autocomplete="off" id="edit-flat-form" class="col s12"'); ?><input type="hidden" value="<?php echo $id; ?>" name="id">
<div class="card"><div class="card-content"><div class="row">
<div class="clearfix input-field col mb15 s12"><h5 class="left mt0 mb15">Updating Flat No : <?php echo h($data->flat_no); ?></h5><a class="right btn mb10 accent-3 red" href="<?php echo base_url('flats'); ?>"><i class="mdi-navigation-close left"></i> Cancel</a><span class="right"> &nbsp; </span><button type="submit" class="btn right indigo accent-3 submit-btn"><i class="mdi-navigation-check left"></i> Update</button></div>
<div class="input-field col m6 s12">
<input id="flat_no" name="flat_no" type="text" value="<?php echo h($data->flat_no); ?>">
<label for="flat_no">Flat No</label>
<div class="err-under name-err"></div>
</div>
<div class="input-field col m6 s12">
<select name="flat_wing" id="flat_wing">
<option value="0">Flat Wing</option>
<?php
foreach ($wings as $key => $value) {
echo '<option value="'.$value->id.'"';
if($value->id===$data->flat_wing){
echo ' selected';
}
echo '>'.$value->name.'</option>';
}
unset($value);
?>
</select>
<div class="err-under wing-err"></div>
</div>
<div class="input-field col m6 s12">
<input id="owner" name="owner" type="text" maxlength="50" value="<?php echo h($data->owner_name); ?>">
<label for="owner">Owner Name</label>
<div class="err-under owner_err"></div>
</div>
<div class="input-field col m6 s12">
<input id="contact" name="contact" type="text" maxlength="10" value="<?php echo h($data->owner_number); ?>">
<label for="contact">Contact</label>
<div class="err-under contact-err"></div>
</div>
<div class="input-field col m4 s12">
<input id="sq_foot" name="sq_foot" type="text" maxlength="10" value="<?php echo h($data->sq_foot); ?>">
<label for="sq_foot">Flat Area (square feet)</label>
</div>
<div class="input-field col m4 s12">
<input id="intercom" name="intercom" type="text" value="<?php echo h($data->intercom); ?>">
<label for="intercom">Intercom</label>
</div>
<div class="input-field col m4 s12">
<input type="radio" name="status" value="1" id="owner_s"<?php if($data->status==='1'){echo ' checked';} ?>><label for="owner_s">Owner</label>
<input type="radio" name="status" value="2" id="rent_s"<?php if($data->status==='2'){echo ' checked';} ?>><label for="rent_s">Rented</label>
<div class="err-under status-err"></div>
</div>
</div>
</div></div>
<?php echo form_close(); ?>
</div>