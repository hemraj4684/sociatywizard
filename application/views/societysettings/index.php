<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12">
<h5 class="breadcrumbs-title"><i class="fa fa-cog"></i> Settings</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Settings</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600">

<div class="col s12">
<div class="card">
<div class="card-content pad0">
<?=form_open('','id="society_form_1"')?>
<table class="table highlight bordered"><tr class="purple white-text"><td><span class="card-title">Society Information</span></td><td><button type="submit" class="btn submit-btn right indigo accent-3 btn-small"><i class="fa fa-save"></i> Save Changes</button></td></tr></table>
<table class="table highlight bordered">
	<tbody>
		<tr><th width="30%">Society Name</th><td><input name="name" type="text" placeholder="Society Name" maxlength="255" value="<?=h($data->society_name)?>"><p class="font-12 red-text bold-500 text-accent-3 name-err"></p></td></tr>
		<tr><th>Society Address</th><td><textarea name="address" class="materialize-textarea" placeholder="Society Address" maxlength="255"><?=h($data->society_address)?></textarea><p class="font-12 red-text bold-500 text-accent-3 address-err"></p></td></tr>
		<tr><th>Society Registration Number</th><td><input name="reg_no" type="text" placeholder="Society Registration Number" maxlength="100" value="<?=h($data->registration_number)?>"><p class="font-12 red-text bold-500 text-accent-3 reg_no-err"></p></td></tr>
		<tr><th>Invoice Notes</th><td><textarea name="notes" class="materialize-textarea" placeholder="Invoice Notes" maxlength="1000"><?=h($data->invoice_note)?></textarea><p class="font-12 red-text bold-500 text-accent-3 notes-err"></p></td></tr>
		<tr><th>Late Payment Interest Percentage</th><td><input name="interest_number" type="text" maxlength="4" value="<?=h($data->late_payment_interest)?>"><p class="font-12 red-text bold-500 text-accent-3 interest_number-err"></p></td></tr>
	</tbody>
</table>
<?=form_close()?>
</div>
</div>
<hr>
<div class="card">
<div class="card-content pad0">
<table class="table highlight bordered"><tr><td><span class="card-title">Bill Groups</span></td><td><button type="button" data-target="new_group" class="btn submit-btn right indigo accent-3 btn-small modal-trigger"><i class="fa fa-plus"></i> Create New Group</button></td></tr></table>
<div class="bill-data auto-scroll"></div>
</div>
</div>


<div class="card">
<div class="card-content">
<div class="card-title">Assign Bill To Flats</div>
<div class="flat_and_bill"></div>
</div>
</div>




</div>
</div>
<div id="new_group" class="modal"><div class="modal-content">
<?=form_open('','class="row" id="society_form_2"')?>
<div class="input-field mt0 col s12">
	<h5 class="left mt0"><b>Create New Bill Group</b></h5>
	<button type="button" class="btn btn-small new-group-btn right red accent-3 modal-close"><i class="fa fa-close"></i> Cancel</button>
	<span class="right">&nbsp;</span>
	<button type="submit" class="btn btn-small new-group-btn right blue"><i class="fa fa-plus"></i> Create</button>
</div>
<p class="font-12 red-text bold-500 text-accent-3 b-parti-err"></p>
<p class="font-12 red-text bold-500 text-accent-3 b-amount-err"></p>
<div class="input-field col s12">
	<input type="text" name="name" id="bg_name">
	<label for="bg_name">Bill Group Name</label>
	<p class="err-under b-name-err"></p>
</div>
<table class="table new_part_group"><tbody>
	<tr><td><input name="particular[]" type="text" placeholder="Particular"></td><td><input name="amount[]" type="text" placeholder="Amount"></td><td><button type="button" class="btn red accent-3 remove-parti btn-small"><i class="fa fa-close m0"></i></button></td></tr>
</tbody></table>
<div class="input-field col s12">
	<button type="button" class="btn btn-small add-partis green"><i class="fa fa-plus"></i> Add A Particular</button>
</div>
<?=form_close()?>
</div></div>