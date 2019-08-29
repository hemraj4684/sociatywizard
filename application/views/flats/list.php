<div id="breadcrumbs-wrapper" class="animated fadeIn">
<div class="row mb0">
<div class="col s12 m6">
<h5 class="breadcrumbs-title"><?=$top_title?></h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('flats')?>">Flats Management</a> / </li>
<li class="active"><?=$top_title?></li>
</ol>
</div>
<div class="col s12 m6">
<input class="filled-in check_all" name="user_select_all" id="user_select_all" type="checkbox">
<label id="user_select_all_label" for="user_select_all"></label>
<!-- <button data-target="sms_modal" class="btn-small white-text waves-effect waves-light purple btn modal-trigger"><i class="fa fa-envelope"></i> Send SMS</button> -->
<a class="btn-small white-text waves-effect waves-light blue btn" href="<?php echo base_url('flats/add'); ?>"><i class="fa fa-plus"></i> Add A Flat</a>
</div>
</div>
</div><input type="hidden" name="list_id" id="list_id" value="<?=$id?>">
<div class="row mt10 height-600">
<div class="col s12 table-cover">
<table class="striped data_list bordered flats_data">
<thead><tr><th width="10">#</th><th>Flat No</th><th>Owner Name</th><th width="100">Mobile Number</th><th width="75">Intercom</th><th width="90">Flat Area</th><th width="50">Bill</th><th width="50">Action</th></tr></thead><tbody>
<?php
foreach ($data as $key => $value) {
	echo '<tr><td><input data-number="'.h($value->owner_number).'" value="'.$value->flat_id.'" class="sms_ok filled-in" name="user_select[]" id="user_select_'.$value->flat_id.'" type="checkbox"><label for="user_select_'.$value->flat_id.'"></label></td><td><a class="brown-text darken-4 bold-700" href="'.base_url('flats/flat_details/'.$value->flat_id).'">';
	if(!empty($value->wing_name)){echo h($value->wing_name).'-';}
	echo h($value->flat_no);
	echo '</a></td><td>'.h($value->owner_name).'</td><td>'.h($value->owner_number).'</td><td>'.h($value->intercom).'</td><td>'.h($value->sq_foot).' sq feet</td><td><a class="btn indigo btn-small accent-3" href="'.base_url('flats/old_invoices/'.$value->flat_id).'"><i class="mdi-action-view-list left"></i> view</a></td><td><a class="btn orange btn-small darken-2" href="'.base_url('flats/edit/'.$value->flat_id).'"><i class="left mdi-editor-mode-edit"></i> edit</a></td></tr>';
}
?>
</tbody></table>


</div>
</div>