<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m6 l7">
<h5 class="breadcrumbs-title">Vendors</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Vendors</li>
</ol>
</div>
<div class="col s12 m6 l5">
<button class="btn btn-small blue modal-trigger" data-target="add_modal"><i class="fa fa-plus"></i> Add Vendor</button>
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12 table-cover">
<table class="striped bordered data_list"><thead><tr><th>#</th><th>Name</th><th>Contact No.</th><th>Category</th><th>Address</th><th>Notes</th><th width="60px">Action</th></tr></thead><tbody>
<?php
foreach ($data as $key => $value) {
	echo '<tr><td>'.++$key.'</td><td>'.h($value->contact_name).'</td><td data-no1="'.h($value->contact_number_1).'" data-no2="'.h($value->contact_number_2).'">'.h($value->contact_number_1);
	if(!empty($value->contact_number_2)){
		echo '<br>'.h($value->contact_number_2);
	}
	echo '</td><td>'.h($value->category).'</td><td data-addr="'.h($value->address).'">'.para($value->address).'</td><td data-note="'.h($value->notes).'">'.para($value->notes).'</td><td><button title="Delete" data-id="'.h($value->id).'" class="red accent-4 btn remove_vendor btn-small"><i class="fa fa-close m0"></i></button> <button data-id="'.h($value->id).'" title="Edit" class="blue accent-3 btn btn-small modal-trigger edit-btn" data-target="edit_modal"><i class="fa fa-edit m0"></i></button></td></tr>';
}
unset($value);
?>
</tbody></table>
</div>
</div>
<div id="add_modal" class="modal modal-fixed-footer">
<?=form_open('','id="vendor_new"')?>
<div class="modal-content">
<div class="row">
<h5 class="col s12">Add Vendor</h5>
<div class="input-field col s12 m6">
<input id="name" name="name" type="text" maxlength="50">
<label for="name">Contact Person Name</label>
<p class="err-under name"></p>
</div>
<div class="input-field col s12 m6">
<input id="no1" name="no1" type="text" maxlength="16">
<label for="no1">Contact Number</label>
<p class="err-under no1"></p>
</div>
<div class="input-field col s12 m6">
<input id="no2" name="no2" type="text" maxlength="16">
<label for="no2">Alternate Contact Number</label>
<p class="err-under no2"></p>
</div>
<div class="input-field col s12 m6">
<input id="category" name="category" type="text" maxlength="50">
<label for="category">Category <small>(Ex: Plumber, Office Supplies, Electrician)</small></label>
<p class="err-under category"></p>
</div>
<div class="input-field col s12 m6">
<textarea id="address" class="materialize-textarea" name="address" maxlength="255"></textarea>
<label for="address">Address</label>
<p class="err-under address"></p>
</div>
<div class="input-field col s12 m6">
<textarea id="notes" class="materialize-textarea" name="notes" maxlength="1000"></textarea>
<label for="notes">Notes</label>
<p class="err-under notes"></p>
</div>
</div>
</div>
<div class="modal-footer">
<button type="submit" class="submit-btn btn-flat">Submit</button>
<button type="button" class="modal-action modal-close waves-effect waves-green btn-flat">Cancel</button>
</div>
<?=form_close()?>
</div>
<div id="edit_modal" class="modal modal-fixed-footer">
<?=form_open('','id="vendor_edit"')?><input type="hidden" name="id" id="id" value="0">
<div class="modal-content">
<div class="row">
<h5 class="col s12">Update Information</h5>
<div class="input-field col s12 m6">
<input id="name_e" name="name" type="text" maxlength="50">
<label for="name_e">Contact Person Name</label>
<p class="err-under name_e"></p>
</div>
<div class="input-field col s12 m6">
<input id="no1_e" name="no1" type="text" maxlength="16">
<label for="no1_e">Contact Number</label>
<p class="err-under no1_e"></p>
</div>
<div class="input-field col s12 m6">
<input id="no2_e" name="no2" type="text" maxlength="16">
<label for="no2_e">Alternate Contact Number</label>
<p class="err-under no2_e"></p>
</div>
<div class="input-field col s12 m6">
<input id="category_e" name="category" type="text" maxlength="50">
<label class="active" for="category_e">Category <small>(Ex: Plumber, Office Supplies, Electrician)</small></label>
<p class="err-under category_e"></p>
</div>
<div class="input-field col s12 m6">
<textarea id="address_e" class="materialize-textarea" name="address" maxlength="255"></textarea>
<label for="address_e">Address</label>
<p class="err-under address_e"></p>
</div>
<div class="input-field col s12 m6">
<textarea id="notes_e" class="materialize-textarea" name="notes" maxlength="1000"></textarea>
<label for="notes_e">Notes</label>
<p class="err-under notes_e"></p>
</div>
</div>
</div>
<div class="modal-footer">
<button type="submit" class="submit-btn btn-flat">Submit</button>
<button type="button" class="modal-action modal-close waves-effect waves-green btn-flat">Cancel</button>
</div>
<?=form_close()?>
</div>