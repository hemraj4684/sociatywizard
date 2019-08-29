<div class="grey-text right-align search-time-taken grey lighten-5"></div>
<?php
if(isset($this->data[0]) && !empty($this->data[0])){
if($what==='1'){
foreach($this->data[0] as $key => $val){
echo '<div class="col s12 searched-item"><h3><a class="blue-text text-accent-4" href="/tutorials/'.$this->sanitize($val['lk']).'.tutorial">'.$this->sanitize($val['tt']).'</a></h3>';
echo '<p class="truncate grey-text text-darken-1">Author : '.$val['fn'].' '.$val['ln'].'</p>';
if(!empty($val['ds'])){echo '<p class="grey-text text-darken-1">'.$this->sanitize(substr($val['ds'],0,275)).'</p>';}else{echo '<p class="grey-text text-darken-1">no description</p>';}
echo '</div>';
}
unset($val);
} else if($what==='2'){
foreach($this->data[0] as $key => $val){
echo '<div class="col s12 searched-item">';
echo '<div class="card-panel"><div class="row"><div class="col s4 m3 l2"><a class="u-lk hoverable" href="/'.$this->sanitize($val['un']).'">';
if(!empty($val['pc'])){
echo '<img src="/dp/user-profile-pictures/'.$this->sanitize($val['pc']).'">';
}else{
echo '<img src="/assets/img/default-profile.jpg">';
}
echo '</a></div><div class="col s8 m9 l10"><h3><a class="blue-text text-accent-4" href="/'.$this->sanitize($val['un']).'">'.$this->sanitize($val['fn']).' '.$this->sanitize($val['ln']).'</a></h3><p class="grey-text text-darken-1">'.$this->sanitize($val['am']).'</p></div></div></div>';
echo '</div>';
}
unset($val);
}
} else {
echo '<div class="col s12"><h5 class="grey-text"><u>No result found</u></h5></div>';
}
?>