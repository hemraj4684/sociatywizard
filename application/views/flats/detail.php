<div id="breadcrumbs-wrapper" class="animated fadeIn">
<div class="row mb0">
<div class="col s12 m12 l12">
<h5 class="breadcrumbs-title">Flat Details</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?php echo base_url('flats'); ?>">Flats Management</a> / </li>
<li class="active">Flat Details</li>
</ol>
</div>
</div>
</div>
<div class="row mt15 mb15 height-600">
<div class="col s12">
<table class="centered z-depth-1 data_list responsive-table">
<thead class="indigo accent-3 white-text"><tr><th>Flat No</th><th>Owner Name</th><th>Contact Number</th><th>Intercom</th><th>Square Feet</th></tr></thead>
<tbody>
<tr>
<td><?php if(!empty($data->wing_name)){echo $data->wing_name.'-';} echo h($data->flat_no); ?></td>
<td><?php echo h($data->owner_name); ?></td>
<td><?php if(!empty($data->owner_number)) { echo h($data->owner_number); } else { echo 'N/A'; } ?></td>
<td><?php echo h($data->intercom); ?></td>
<td><?php echo h($data->sq_foot); ?></td>
</tr>
</tbody>
</table>
<div class="card mt15 td-padding mb0"><div class="card-content padt0"><h4 class="left mb0 font-20 bold-500 mt0">Members</h4><div class="right"><input class="check_all filled-in" name="user_select_all" id="user_select_all" type="checkbox"><label id="user_select_all_label" for="user_select_all"></label> <a class="btn-small white-text waves-effect waves-light btn-flat purple btn modal-trigger" href="#sms_modal">Send SMS</a></div></div></div>
<table class="z-depth-1 bordered highlight centered data_list members-list"><thead class="indigo accent-3 white-text"><tr><th>#</th><th>Image</th><th>Name</th><th>Phone/Mobile</th><th>Action</th></tr></thead>
<tbody>
<?php
if(!empty($users)){
foreach ($users as $key => $value) {
	echo '<tr><td><input value="'.$value->id.'" data-number="'.$value->mobile_no.'" class="sms_ok filled-in" name="user_select[]" id="user_select_'.$value->id.'" type="checkbox"><label for="user_select_'.$value->id.'"></label></td><td>';
	if(empty($value->picture)){
		echo '<img class="z-depth-1 light-border img-thumb responsive-img" src="'.base_url('assets/images/user_image.png').'">';
	} else {
		echo '<img class="z-depth-1 light-border img-thumb responsive-img" src="'.base_url('assets/members_picture/'.h($value->picture)).'">';
	}
	echo '</td><td>'.h($value->firstname).' '.h($value->lastname).'</td><td>'.h($value->mobile_no).'</td><td><a class="btn btn-small black-text bold-500 orange darken-2 white-text" href="'.base_url('registeredmembers/edit/'.$value->id).'"><i class="left mdi-editor-mode-edit"></i> edit</a></td></tr>';
}
unset($value);
} else {
echo '<tr><td colspan="4"><b class="font-20"><i>No Members Registered!</i></b></td></tr>';
}
?>
</tbody>
</table>
</div>
</div>