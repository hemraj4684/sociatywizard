<div class="row mt10 height-600">
<div class="col s12"><div class="row">
<?php
if(!empty($flats)){

foreach ($flats as $key => $val) {
	echo '<div class="col s12"><div style="border-top:2px solid #3d5afe" class="white card-panel"><h5 class="mt0">Flat NO : ';
	if(!empty($val->wing_name)){
		echo h($val->wing_name).' - ';
	}
	echo $val->flat_no.'</h5><p>Owner Name : '.h($val->owner_name).'</p><p>Owner Number : '.h($val->owner_number).'*</p><p class="mb0 blue-grey-text text-lighten-1">*You will recieve your billing and other notifications on this number.</p></div></div>';
	
	if(isset($members[$val->id])){
		foreach ($members[$val->id] as $key => $value) {
			echo '<div class="col s12 m4 l3"><div class="card"><div class="card-image">';
			if(!empty($value->picture)){
				echo '<img class="admin-img-icon" src="'.base_url('assets/members_picture/'.h($value->picture)).'">';
			} else {
				echo '<img class="admin-img-icon" src="'.base_url('assets/images/user_image.png').'">';
			}
			echo '</div><div class="card-content">';
			echo '<span class="card-title">'.h($value->firstname.' '.$value->lastname).'</span>';
			echo '<p>Mobile No : '.h($value->mobile_no).'</p>';
			echo '</div></div></div>';
		}
		unset($value);
	}
}
unset($val);
}
?>
</div></div>
</div>