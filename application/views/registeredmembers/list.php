<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m8 l7">
<h5 class="breadcrumbs-title"><?=$this->p_var?> List</h5>
<?php
if(!empty($wing)){
echo '<p><b>Block : </b>'.h($wing->name).'</p>';
}
?>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('registeredmembers')?>"><?=$this->p_var?></a> / </li>
<li class="active"><?php if(!empty($wing)){echo $this->p_var.' In Block '.h($wing->name);}else{echo $this->p_var.' List';}?></li>
</ol>
</div>
<div class="col s12 m4 l5">
<input class="filled-in check_all" name="user_select_all" id="user_select_all" type="checkbox"><label id="user_select_all_label" for="user_select_all"></label>
<!-- <button data-target="sms_modal" class="btn-small modal-trigger white-text waves-effect waves-light purple btn"><i class="fa fa-envelope"></i> Send SMS</button> -->
<button class="btn-small white-text waves-effect user-delete waves-light red accent-3 btn"><i class="fa fa-close"></i> Remove Member</button>
</div>
</div>
</div>
<div class="row height-600 mt10">
	<div class="col s12 table-cover">
		<table class="striped bordered data_list users_listing">
			<thead><tr><th>#</th><th>Image</th><th>Name</th><th>Flat No</th><th>Phone/Mobile</th><th>Email ID</th><th>Assoc. Member</th><th width="50">Action</th></tr></thead>
			<tbody>
				<?php
					foreach ($data as $key => $value) {
						echo '<tr><td><input value="'.$value->id.'" data-number="'.$value->mobile_no.'" class="sms_ok filled-in" name="user_select[]" id="user_select_'.$key.'" type="checkbox"><label for="user_select_'.$key.'"></label></td><td>';
						if(empty($value->picture)){
							echo '<img class="z-depth-1 light-border easing img-thumb responsive-img" src="'.base_url('assets/images/user_image.png').'">';
						} else {
							echo '<img class="z-depth-1 light-border easing img-thumb responsive-img" src="'.base_url('assets/members_picture/'.h($value->picture)).'">';
						}
						echo '</td><td>'.h($value->firstname.' '.$value->lastname);
						echo '</td><td>';
						if(!empty($value->name)){
							echo h($value->name).' - ';
						}
						echo $value->flat_no.'</td><td>'.h($value->mobile_no).'</td>';
						echo '<td>'.h($value->email).'</td><td>'.h($value->designation).'</td>';
						echo '<td><a class="btn orange btn-small darken-2" href="'.base_url('registeredmembers/edit/'.$value->id).'"><i class="left mdi-editor-mode-edit"></i> edit</a></td></tr>';
					}
					unset($value);
				?>
			</tbody>
		</table>
	</div>
</div>