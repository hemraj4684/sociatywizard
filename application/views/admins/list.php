<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m6 l7">
<h5 class="breadcrumbs-title">Admins</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Admins</li>
</ol>
</div>
<div class="col s12 m6 l5">
<input class="filled-in check_all" name="user_select_all" id="user_select_all" type="checkbox">
<label id="user_select_all_label" for="user_select_all"></label>
<!-- <button data-target="sms_modal" class="modal-trigger btn-small white-text waves-effect waves-light purple btn"><i class="fa fa-envelope"></i> Send SMS</button> -->
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12 table-cover">
<table class="striped bordered data_list">
<thead><tr><th width="175">#</th><th>Name</th><th>Flat No</th><th>Contact Number</th><th>Email ID</th><th width="75">Action</th></tr></thead><tbody>
<?php
foreach ($data as $key => $value) {
	echo '<tr><td><input class="sms_ok filled-in" data-number="'.h($value->mobile_no).'" name="user_select[]" id="user_select1" type="checkbox"><label for="user_select1"></label> ';
	if(!empty($value->picture)){
		echo '<img class="z-depth-1 light-border img-thumb responsive-img" src="'.base_url('assets/members_picture/'.h($value->picture)).'">';
	} else {
		echo '<img class="z-depth-1 light-border img-thumb responsive-img" src="'.base_url('assets/images/user_image.png').'">';
	}
	echo '</td><td>'.h($value->firstname).' '.h($value->lastname).'</td><td>';
	if(!empty($value->name)){
		echo h($value->name).' - ';
	}
	echo $value->flat_no.'</td><td>'.h($value->mobile_no).'</td><td>'.h($value->email).'</td><td><a class="btn orange btn-small darken-2" href="'.base_url('registeredmembers/edit/'.$value->id).'?ref=admins"><i class="left mdi-editor-mode-edit"></i> edit</a></td></tr>';
}
unset($value);
?>
</tbody></table>
</div>
</div>