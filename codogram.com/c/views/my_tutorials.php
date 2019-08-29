<div class="container">
<div class="row">
<?php
require 'c/layout/sidebar.php';
?>
<div class="col s12 m9">
<div class="row">
<?php
foreach ($this->data[0] as $key => $val){
$NV='';
$cl='lighten-1';
if($val[4]==='0'){
$cl='lighten-3';
$NV='<i data-position="bottom" data-tooltip="This tutorial is not visible to others" class="red-text text-darken-3 mdi-notification-dnd-forwardslash tooltipped"></i>';
}
echo '<div class="col s12 m6">
<div class="card-panel white-text indigo '.$cl.'">


<a class="dropdown-button white-text right" href="#"" data-activates="dropdown'.$val[0].'"><i class="mdi-hardware-keyboard-arrow-down"></i></a>
<ul id="dropdown'.$val[0].'" class="dropdown-content">
<li><a class="black-text" href="#">View</a></li>
<li class="divider"></li>
<li><a class="black-text" href="/user/edit-tutorial/'.$this->valid_number($val[0]).'">Edit</a></li>
<li class="divider"></li>
<li><a class="black-text" href="#">Delete</a></li>
</ul>


<h5>'.$this->sanitize($val[1]).$NV.'</h5>
<p>'.$this->sanitize($val[2]).'</p>
<p class="black-text small-btn">Votes '.$this->valid_number($val[5]).' <i class="mdi-action-grade"></i></p>
</div>
</div>';
}
?>
</div>
</div>
</div>
</div>