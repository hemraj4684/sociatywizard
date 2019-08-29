<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12">
<h5 class="breadcrumbs-title"><i class="fa fa-users"></i> Society Members</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('me')?>">Home</a> / </li>
<li class="active">Society Members</li>
</ol>
</div>
</div>
</div>
<div class="row height-600 mt10">
<div class="col s12">
<table class="striped bordered data_list users_listing">
<thead><tr><th>Image</th><th>Name</th><th>Phone/Mobile</th><th>Association Member</th></tr></thead>
<tbody>
<?php
foreach ($data as $key => $value) {
	echo '<tr><td>';
	if(empty($value->picture)){
		echo '<img class="z-depth-1 light-border easing img-thumb responsive-img" src="'.base_url('assets/images/user_image.png').'">';
	} else {
		echo '<img class="z-depth-1 light-border easing img-thumb responsive-img" src="'.base_url('assets/members_picture/'.h($value->picture)).'">';
	}
	echo '</td><td>'.h($value->firstname.' '.$value->lastname).'</td><td>';
	if($value->phone_privacy==='2'){
		echo $value->mobile_no;
	} else {
		echo '<i class="fa fa-lock"></i>';
	}
	echo '</td><td>'.h($value->designation).'</td></tr>';
}
unset($value);
?>
</tbody></table>
</div></div>