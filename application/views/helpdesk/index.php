<?php
$uri = strtolower($this->uri->segment(3));
?>
<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m12 l12">
<h5 class="breadcrumbs-title"><i class="mdi-hardware-headset-mic"></i> Helpdesk</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Helpdesk</li>
</ol>
</div>
</div>
</div>
<div class="row height-600">
<div class="col s12">
<div class="card">
<div class="card-content">
<div class="row">
<div class="col s12 m4 l3">
<div class="collection mt0">
<a class="grey-text text-darken-3 collection-item<?php if($uri==='' || $uri==='general'){ echo ' grey lighten-2 bold-700';} ?>" href="<?=base_url('helpdesk/messages/general')?>">General Message (<?=$counts[0]?>)</a>
<a class="grey-text text-darken-3 collection-item<?php if($uri==='complaints'){ echo ' grey lighten-2 bold-700';} ?>" href="<?=base_url('helpdesk/messages/complaints')?>">Complaints (<?=$counts[1]?>)</a>
<a class="grey-text text-darken-3 collection-item<?php if($uri==='closed'){ echo ' grey lighten-2 bold-700';} ?>" href="<?=base_url('helpdesk/messages/closed')?>">Closed (<?=$counts[2]?>)</a>
</div>
</div>
<div class="col s12 m8 l9">
<table class="table bordered helpdesk_list"><thead><tr><td></td></tr></thead><tbody>
<?php
$count = count($data);
foreach ($data as $key => $value) {
	echo '<tr><td class="pad0"><div class="m0 collection">';
	echo '<a class="collection-item grey-text text-darken-3 avatar';
	if($value->read_by_reciever==='2'){echo ' blue-grey lighten-4';}
	echo '" href="'.base_url('helpdesk/message_item/'.$value->id).'">';
	if($value->picture){
		echo '<img class="circle" src="'.base_url('assets/members_picture/'.$value->picture).'">';
	} else {
		echo '<img class="circle" src="'.base_url('assets/images/user_image.png').'">';
	}
	echo '<p class="indigo-text text-accent-4"><b>'.h($value->firstname).' '.h($value->lastname).'</b></p>';
	echo '<p>'.h($value->message_subject).'</p>';
	echo h($value->date_sent);
	echo '</a>';
	echo '</div></td></tr>';
}
unset($value);
?>
</div>
</tbody></table>
</div>
</div>
</div>
</div>
</div>
</div>