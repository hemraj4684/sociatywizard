<div class="container">
<?php
$dimen = 'wide-img';
$getimg = getimagesize('../abu'.$this->dp);
if(isset($getimg[0],$getimg[1]) && $getimg[1]>$getimg[0]){
$dimen = 'long-img';
}
$fw = [];
$fo = [];
if(!empty($this->fw)){$fw = explode(',',$this->fw);}
if(!empty($this->fo)){$fo = explode(',',$this->fo);}
?>
<div class="row">
<div class="col m12 s12">
<div id="user-banner">
<div data-src="<?=$this->bn?>" class="banner-holder hit-pic z-depth-1"></div>
<div class="abs over-banner z-depth-1">
<img src="<?=$this->dp?>" data-src="<?=$this->dp?>" class="profile-pic hit-pic <?=$dimen?>">
</div>
<div class="banner-user-option">
<strong><a href="/<?=$this->un?>" class="banner-username truncate z-depth-1 center"><?=$this->fn.' '.$this->ln?></a></strong>
<?php if($this->user_id!==$this->u_id): ?>
<div class="f-btn-holder">
<?php if(in_array($this->user_id,$fw)): ?>
<button type="button" class="follow-btn indigo lighten-1 btn following"><i class="mdi-action-done left"></i> Following</button>
<?php else: ?>
<button type="button" class="follow-btn indigo lighten-1 btn new-follow">Follow</button>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
</div>
</div><div class="col s12">
<div class="row">
<?php if($this->loggedin): ?>
<div class="col s12"><ul class="tabs" id="profile-tab">
<li class="tab"><a href="#about"><i class="mdi-social-person"></i> About</a></li>
<li class="tab"><a href="#tutorials"><i class="mdi-action-view-list"></i> Tutorials</a></li>
<li class="tab"><a href="#follows"><i class="mdi-social-people"></i> Follows</a></li>
</ul></div>
<div id="about">
<div class="col m6 s12">
<?php if(!empty($this->uabt)): ?><div class="card-panel"><h3>About</h3><p><?=$this->uabt?></p></div><?php endif; ?>
<div class="card-panel">
<h3>Location</h3>
Country : <b><?=$this->cn?></b>
<div id="map"></div>
</div>
</div>
<div class="col m6 s12">
<div class="card-panel social-card">
<h3>Skills</h3>
<?php
if(!empty($this->sk)){
$this->sk = explode(',',$this->sk);
foreach($subj as $val){
if(in_array($val['id'],$this->sk)){
echo '<span class="indigo z-depth-1 lighten-1 margin-b5 users-skill white-text">'.$val['name'].'</span>';
}
}
}
?>
</div>
</div>
<div class="col m6 s12">
<div class="card-panel social-card">
<h3>Social Links</h3>
<div class="row"><div class="col s12"><div class="web-links">
<div class="ic-m5"><i class="small mdi-social-share"></i></div>
<?php if(!empty($this->fb)): ?>
<a target="_blank" class="grey-text text-darken-3" href="https://www.facebook.com/<?=$this->fb?>"><?=$this->fb?> (Facebook)</a>
<?php endif; ?>
<?php if(!empty($this->tw)): ?>
<a target="_blank" class="grey-text text-darken-3" href="https://twitter.com/<?=$this->tw?>"><?=$this->tw?> (Twitter)</a>
<?php endif; ?>
<a href="/<?=$this->un?>" class="grey-text text-darken-3"><?=$this->un?> (Codogram)</a>
</div></div></div>
<?php if(!empty($this->myurl)): ?>
<div class="row"><div class="col s12"><div class="web-links">
<div class="ic-m5"><i class="small mdi-social-public"></i></div>
<a href="<?=$this->myurl?>" target="_blank" class="grey-text text-darken-3">
<?=$this->myurl?>
</a></div></div></div>
<?php endif; ?>
</div>
</div>
</div>
<div id="tutorials">
<?php
if(!empty($this->data)){
foreach($this->data as $key => $val){
$NV='';
$cl='lighten-1';
if($val['vs']==='0'){
$cl='grey lighten-4';
$NV='<i data-position="bottom" data-tooltip="This tutorial is not visible to others. Only you can view this on the website." class="red-text text-darken-1 mdi-notification-dnd-forwardslash tooltipped"></i>';
}
echo '<div class="col s12 m6"><div class="card-panel '.$cl.'">';
if($this->user_id===$this->uwise['id']){
echo '<a class="dropdown-button black-text right" href="#"" data-activates="dropdown'.$val['id'].'"><i class="mdi-hardware-keyboard-arrow-down"></i></a><ul id="dropdown'.$val['id'].'" class="dropdown-content">
<li><a class="black-text" href="/tutorials/'.$this->sanitize($val['lk']).'.tutorial">View</a></li>
<li class="divider"></li>
<li><a class="black-text" href="/user/edit-tutorial/'.$this->valid_number($val['id']).'">Edit</a></li>
</ul>';
}
echo '<h5><a class="black-text" href="/tutorials/'.$this->sanitize($val['lk']).'.tutorial">'.$this->sanitize($val['tt']).' '.$NV.'</a></h5>
<p class="cut-words grey-text text-darken-1">';
if(strlen($val['ds']) < 401){
echo $this->sanitize($val['ds']);
} else {
echo $this->sanitize(substr($val['ds'], 0,400)).'...';
}
echo '</p>
<p class="black-text small-btn">Votes '.$this->valid_number($val['vt']).' <i class="mdi-action-grade"></i></p>
</div>
</div>';
}
} else {
echo '<div class="col s12 no-tuts-profile"><h6 class="red-text text-lighten-1"><b><u>No Tutorials Uploaded Yet!</u></b></h6>';
if($this->user_id===$this->uwise['id']){echo '<a class="btn indigo lighten-3" href="/code/add-new-tutorial">Create your first tutorial <i class="right mdi-content-forward"></i></a>';}
echo '</div>';
}
?>
</div>
<div id="follows">
<div class="col s12">
<ul class="tabs" id="profile-follow-tab">
<li class="tab"><a class="active" href="#following">Following (<?=count($fo)?>)</a></li>
<li class="tab"><a href="#followers">Followers (<?=count($fw)?>)</a></li>
</ul>
</div>
<div id="following"><div class="progress red lighten-5"><div class="indeterminate red accent-3"></div></div><div class="progress yellow lighten-5"><div class="indeterminate yellow"></div></div><div class="progress blue lighten-5"><div class="indeterminate blue accent-4"></div></div></div><div id="followers"><div class="progress red lighten-5"><div class="indeterminate red accent-3"></div></div><div class="progress yellow lighten-5"><div class="indeterminate yellow"></div></div><div class="progress blue lighten-5"><div class="indeterminate blue accent-4"></div></div></div>
</div>
<?php
if(isset($ll[1])){
$this->js = '<script>var MLL={lat:'.$ll[0].',lng:'.$ll[1].'};</script><script src="/assets/js/profile-map-v3.js"></script><script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3EnBd7EzI_mjOWWb-wQZOFglUVXSThqg&callback=initMap"></script>';
} else {
$this->js = '<script>var MLL="'.$this->cn.'";</script><script src="/assets/js/map_frame.js"></script>';
}
?>
<?php else: ?>
<?php $login_head = 'Login to view full profile';?>
<?php require 'partial_login_form.php';?>
<?php endif; ?>
</div>
</div>
</div>
</div>
<style>.banner-holder{background-image:url('<?=$this->bn?>');}</style>
<input type="hidden" value="<?=$token?>" name="token" id="token">
<div id="img-overlayed"></div>
<img class="img-big-overlay z-depth-4" id="overlayed-pic">
<?php
$this->js .='<script>var ID='.$this->u_id.',ft=0;</script>';
if($this->loggedin){$this->js .='<script src="/assets/js/profile.js"></script>';}else{$this->js .='<script src="/assets/js/profile_l.js"></script>';}