<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m7">
<h5 class="breadcrumbs-title"><i class="fa fa-cab"></i> Parking Lot</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Parking Lot</li>
</ol>
</div>
<div class="col s12 m5">
<a class="btn-small white-text waves-effect waves-light indigo accent-3 btn modal-trigger" href="#new_parking"><i class="fa fa-plus"></i> Add Vehicle</a>
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12">
<div class="card">
<div class="card-content">
<div class="row">
<?php
if(empty($data)){
echo '<div class="center card-title">You have not added any vehicles information.</div><p class="center mt15">To add a new vehicle information <a class="btn grey darken-3 btn-small modal-trigger" href="#new_parking"><i class="fa fa-plus left"></i> Click Here</a></p>';
} else {
foreach ($data as $key => $value) {
	$f = '';
	if(!empty($value->name)){
		$f = h($value->name).'-';
	}
	$f .= h($value->flat_no);
	echo '<div class="col s6 m4 l3 center vitem_'.$value->id.'"><h1 data-target="edit_vehicle" data-id="'.$value->id.'" data-label="'.h($value->slot_label).'" data-plate="'.h($value->no_plate).'" data-model="'.h($value->vehicle_model).'" data-vtype="'.h($value->vehicle_type).'" data-flat="'.h($value->flat_id).'" data-flat_f="'.$f.'" class="mb0 v-item modal-trigger pointer-cursor">';
	if($value->vehicle_type==='1'){
		echo '<i class="fa fa-motorcycle green-text text-darken-1 pl-icon"></i>';
	} else {
		echo '<i class="fa fa-car red-text text-accent-3 pl-icon"></i>';
	}
	echo '</h1><p class="black-text bold-500">'.h($value->no_plate).'</p><p>Flat No : ';
	echo $f.'</p></div>';
}
unset($value);
}
?>
</div>
</div>
</div>
</div>
</div>
<div id="edit_vehicle" class="modal modal-fixed-footer">
<?=form_open('','id="edit-parking" autocomplete="off"')?>
<div class="modal-content">
<div class="v-info-list">
<div class="row">
<div class="col s12">
<table class="table bordered">
<thead><tr><th colspan="2" class="center font-16">Vehicle information</th></tr></thead><tbody>
<tr><th>Parking Slot Label</th><td class="eplabel"></td></tr>
<tr><th>Number Plate</th><td class="epplate"></td></tr>
<tr><th>Vehicle Model</th><td class="epmodel"></td></tr>
<tr><th>Vehicle Type</th><td class="eptype2"></td></tr>
<tr><th>Flat</th><td class="eflatf"></td></tr>
</tbody></table>
</div>
</div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn-flat edit-v-btn right"><i class="fa fa-edit left"></i> Edit</button>
<button type="button" class="modal-action modal-close btn-flat right"><i class="fa fa-close left"></i> Cancel</button>
<button type="button" class="delete-vehicle btn-flat right"><i class="fa fa-trash left"></i> Delete</button>
</div>
<div class="editable-parking" style="display:none;">
<div class="modal-content">
<div class="row">
<h4 class="col s12">Edit</h4>
<div class="input-field col s12 m6"><input type="text" name="plabel" maxlength="30" id="eplabel"><label for="eplabel">Parking Slot Label</label><div class="err-under erplabel"></div></div>
<div class="input-field col s12 m6"><input type="text" name="pplate" maxlength="30" id="epplate"><label for="epplate">Vehicle Number Plate</label><div class="err-under erpplate"></div></div>
<div class="input-field col s12 m6"><input type="text" name="pmodel" maxlength="25" id="epmodel"><label for="epmodel">Vehicle Model <small>(Ex : Maruti, Honda, Toyota...)</small></label><div class="err-under erpmodel"></div></div>
<div class="input-field col s12 m6">
<input name="ptype" type="radio" value="1" id="eptwo"><label for="eptwo">Two Wheeler</label> &nbsp;&nbsp;
<input name="ptype" type="radio" value="2" id="epfour" checked><label for="epfour">Four Wheeler</label>
<div class="err-under erptype"></div>
<div class="mb15"></div>
</div>
<div class="input-field clearfix col s12 m6"><input type="hidden" name="flat_id" class="e_flat_id"><input type="text" class="flat_float" name="epflat" id="epflat" placeholder="Belongs To Flat"><div></div><div class="erflat_id err-under"></div></div>
</div>
</div>
<div class="modal-footer">
<button type="submit" class="waves-effect waves-green edit-submit-btn btn-flat"><i class="fa fa-save left"></i> Save</button>
<button type="button" class="modal-action modal-close btn-flat"><i class="fa fa-close left"></i> Cancel</button>
<button type="button" class="back-edit-v waves-effect waves-green btn-flat"><i class="fa fa-chevron-left left"></i> Back</button>
</div>
<input type="hidden" name="id" id="eid">
</div>
<?=form_close()?>
</div>
<div id="new_parking" class="modal modal-fixed-footer">
<?=form_open('','id="add-parking" autocomplete="off"')?>
<div class="modal-content">
<div class="row">
<h4 class="col s12"><b>Add Vehicle</b></h4>
<div class="input-field col s12 m6"><input type="text" name="plabel" maxlength="30" id="plabel"><label for="plabel">Parking Slot Label</label><div class="err-under plabel"></div></div>
<div class="input-field col s12 m6"><input type="text" name="pplate" maxlength="30" id="pplate"><label for="pplate">Vehicle Number Plate</label><div class="err-under pplate"></div></div>
<div class="input-field col s12 m6"><input type="text" name="pmodel" maxlength="25" id="pmodel"><label for="pmodel">Vehicle Model <small>(Ex : Maruti, Honda, Toyota...)</small></label><div class="err-under pmodel"></div></div>
<div class="input-field col s12 m6">
<input name="ptype" type="radio" value="1" id="ptwo"><label for="ptwo">Two Wheeler</label> &nbsp;&nbsp;
<input name="ptype" type="radio" value="2" id="pfour" checked><label for="pfour">Four Wheeler</label>
<div class="err-under ptype"></div>
<div class="mb15"></div>
</div>
<div class="input-field clearfix col s12 m6"><input type="hidden" name="flat_id"><input type="text" class="flat_float" name="pflat" id="pflat" placeholder="Belongs To Flat"><div></div><div class="flat_id err-under"></div></div>
</div>
</div>
<div class="modal-footer">
<button type="submit" class="waves-effect waves-green add-submit-btn btn-flat">Submit</button>
<button type="button" class="modal-action modal-close waves-effect waves-green btn-flat">Cancel</button>
</div>
<?=form_close()?>
</div>