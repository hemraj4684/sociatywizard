<div class="container">
<div class="row notif-row margin-b0">
<h3 class="col s12">Notifications</h3>
<?php
if(empty($this->data)){
 echo '<p class="col s12">You dont have any notifications.</p>';
} else {
foreach($this->data as $key => $val) {
if(!empty($val)){
echo '<div class="notif_item">';
if(isset($this->data[0])){
if($val[0]['nr']==='1'){
echo '<div class="col s12 m9">New comment recieved on <a class="blue-text text-accent-4" href="/tutorials/'.$this->sanitize($val[0]['lk']).'.tutorial#comment-row">'.$this->sanitize($val[0]['tt']).'</a></div>';
echo '<div class="col s12 m3"><div class="right-align"><a class="blue-grey-text text-lighten-1 notif_time" href="/tutorials/'.$this->sanitize($val[0]['lk']).'.tutorial#comment-row">'.$this->display_time($val[0]['nd'],'h:ia, M d Y').'</a></div></div>';
} else if($val[0]['nr']==='2'){
echo '<div class="col s12 m9"><a class="blue-text text-accent-4" href="/'.$this->sanitize($val[0]['un']).'">'.$this->sanitize($val[0]['fn']).' '.$this->sanitize($val[0]['ln']).'</a> started following you</div>';
echo '<div class="col s12 m3"><div class="right-align"><a class="blue-grey-text text-lighten-1 notif_time" href="/'.$this->sanitize($val[0]['un']).'">'.$this->display_time($val[0]['nd'],'h:ia, M d Y').'</a></div></div>';
} else if($val[0]['nr']==='3'){
echo '<div class="col s12 m9">Someone replied to your comment on <a class="blue-text text-accent-4" href="/tutorials/'.$this->sanitize($val[0]['lk']).'.tutorial#comment-row">'.$this->sanitize($val[0]['tt']).'</a></div>';
echo '<div class="col s12 m3"><div class="right-align"><a class="blue-grey-text text-lighten-1 notif_time" href="/tutorials/'.$this->sanitize($val[0]['lk']).'.tutorial#comment-row">'.$this->display_time($val[0]['nd'],'h:ia, M d Y').'</a></div></div>';
} else if($val[0]['nr']==='4'){
echo '<div class="col s12 m9">You recieved a like on your tutorial <a class="blue-text text-accent-4" href="/tutorials/'.$this->sanitize($val[0]['lk']).'.tutorial">'.$this->sanitize($val[0]['tt']).'</a></div>';
echo '<div class="col s12 m3"><div class="right-align"><a class="blue-grey-text text-lighten-1 notif_time" href="/tutorials/'.$this->sanitize($val[0]['lk']).'.tutorial#comment-row">'.$this->display_time($val[0]['nd'],'h:ia, M d Y').'</a></div></div>';
} 
}
echo '</div>';
}
}
unset($val);
}
?>
</div>
</div>