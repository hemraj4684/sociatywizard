<div class="row height-600">
<div class="col s12">
<div class="card">
<div class="card-content">
<div class="row">
<div class="col s12 m4 l3">
<div class="collection">
<a class="collection-item grey-text text-darken-3" href="<?=base_url('helpdesk/messages')?>">General Message (<?=$counts[0]?>)</a>
<a class="collection-item grey-text text-darken-3" href="<?=base_url('helpdesk/messages/complaints')?>">Complaints (<?=$counts[1]?>)</a>
<a class="collection-item grey-text text-darken-3" href="<?=base_url('helpdesk/messages/closed')?>">Closed (<?=$counts[2]?>)</a>
</div>
</div>
<div class="col s12 m8 l9">
<div class="card-title"><?=h($item->message_subject)?></div>
<div class="left">
<?php
if($item->picture){?>
<img class="admin-img-icon img-thumb responsive-img" src="<?=base_url('assets/members_picture/'.$item->picture)?>">
<?php } else {?>
<img class="admin-img-icon img-thumb responsive-img" src="<?=base_url('assets/images/user_image.png')?>">
<?php } ?>
</div>
<div class="ml10 left"><p class="bold-500"><?=h($item->firstname.' '.$item->lastname)?></p>
<?php
if(!empty($user)){
foreach ($user as $key => $value) {
	echo '<p class="grey-text text-darken-1 font-12"><b>Flat No : ';
	if($value->wing_name){
		echo h($value->wing_name).' - ';
	}
	echo $value->flat_no;
	echo '</b></p>';
}
unset($value);	
} else {
echo '<p class="grey-text text-darken-1 font-12"><b>Flat No : <i>N/A</i></b></p>';
}
?>
<p class="grey-text text-darken-1 font-12"><?=date('h:ia, M dS Y',strtotime($item->date_sent))?></p>
<p class="grey-text text-darken-1 font-12"><?php if($item->sent_from==='1'){echo '<i class="mdi-action-android"></i> Sent from android app'; } else { echo '<i class="mdi-device-dvr"></i> Sent from desktop';} ?></p>
</div>
<div class="right">
<?=form_open('','id="status_conv_form"')?>
<p class="left-align grey lighten-3 pad5 z-depth-1"><b class="font-16">Status</b><br><input type="radio" value="1" name="conv_status" id="open_conv"<?php if($item->conv_type==='1'){echo ' checked';} ?>><label for="open_conv">Open</label> <input type="radio" value="2" name="conv_status" id="close_conv"<?php if($item->conv_type==='2'){echo ' checked';} ?>><label for="close_conv">Close</label></p>
<input type="hidden" name="id" value="<?=$id?>">
<?=form_close()?>
</div>
<div class="clearfix"></div>
<p><?=para($item->message)?></p>
<div class="reply-area">
<?php
foreach ($replies as $key => $value) {
	if(empty($value->adminid)){
		reply_view($value->user_fn,$value->user_ln,$value->userpic,$value->reply_date,$value->reply_from,$value->reply_text,'left');
	} else {
		reply_view($value->admin_fn,$value->admin_ln,$value->adminpic,$value->reply_date,$value->reply_from,$value->reply_text,'right');
	}
}
unset($value);
?>
</div>
<div class="reply-box">
<hr>
<?php if($item->conv_type!=='2'){?>
<?=form_open('','id="reply_helpdesk"')?>
<textarea name="reply" class="materialize-textarea reply-input" placeholder="Write your reply..."></textarea>
<input type="hidden" name="id" value="<?=$id?>">
<button type="submit" class="submit-btn btn red accent-3 right"><i class="mdi-content-reply left"></i> Reply</button>
<?=form_close()?>
<?php } ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>