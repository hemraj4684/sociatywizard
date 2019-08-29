<?php
function reply_view($fn,$ln,$pic,$date_sent,$sent_from,$msg,$position="left"){
	if($position==='left'){
		echo '<div class="left-align"><hr><div class="left"><img class="admin-img-icon img-thumb responsive-img" src="'.base_url('assets/members_picture/'.$pic).'"></div><div class="ml10 left"><p class="bold-500">'.h($fn.' '.$ln).'</p><p class="grey-text text-darken-1 font-12">'.date('h:ia, M dS Y',strtotime($date_sent)).'</p><p class="grey-text text-darken-1 font-12">';
		if($sent_from==='1'){echo '<i class="mdi-action-android"></i> Sent from android app'; } else { echo '<i class="mdi-device-dvr"></i> Sent from web';}
		echo '</p></div><div class="clearfix"></div><p class="reply-msg-txt">'.para($msg).'</p></div>';
	} else {
		echo '<div class="right-align"><hr><div class="right"><img class="admin-img-icon img-thumb responsive-img" src="'.base_url('assets/members_picture/'.$pic).'"></div><div class="mr10 right"><p class="bold-500">'.h($fn.' '.$ln).'</p><p class="grey-text text-darken-1 font-12">'.date('h:ia, M dS Y',strtotime($date_sent)).'</p><p class="grey-text text-darken-1 font-12">';
		if($sent_from==='1'){echo '<i class="mdi-action-android"></i> Sent from android app'; } else { echo '<i class="mdi-device-dvr"></i> Sent from web';}
		echo '</p></div><div class="clearfix"></div><p class="reply-msg-txt">'.para($msg).'</p></div>';
	}
}