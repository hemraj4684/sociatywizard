<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m12">
<h5 class="breadcrumbs-title">New Member Request</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('registeredmembers')?>"><?=$this->p_var?></a> / </li>
<li class="active">New Member Request</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12">
<div class="card">
<div class="card-content"><div class="row">
<?php
if(empty($data)){

} else {
foreach ($data as $key => $value) {
	echo '<div class="col s12 m4 l3">';
	if(empty($value->picture)){
		echo '<div class="img-file" style="background-image:url('.base_url('assets/images/user_image.png').')"></div>';
	} else {
		echo '<div class="img-file" style="background-image:url('.base_url('assets/members_picture/'.h($value->picture)).')"></div>';
	}
	echo '<p class="ellipsis-tag"><b>'.h($value->firstname.' '.$value->lastname).'</b></p>';
	echo '<p class="ellipsis-tag font-12"><b>Contact : '.h($value->mobile_no).'</b></p>';
	echo '<p class="font-12 ellipsis-tag">Dated : '.date('h:ia, dS M',strtotime($value->date_requested)).'</p>';
	if($value->status==='2'){
		echo '<div class="red accent-1 center white-text font-12 mb5 pad5">Rejected</div>';
	}
	echo '<button data-id="'.$value->id.'" class="btn accept-request indigo btn-small accent-3"><i class="fa fa-check"></i> Accept</button> <button data-id="'.$value->id.'" class="btn reject-request btn-small red accent-3"><i class="fa fa-close"></i> Reject</button>';
	echo '</div>';
}
unset($value);
}
?>
</div>
</div>
</div>
</div>
</div>