<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m8">
<h5 class="breadcrumbs-title"><i class="fa fa-sticky-note-o"></i> Notice Board</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('me')?>">Home</a> / </li>
<li class="active">Notice Board</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600">
<?php
foreach($notices as $key => $notice) {
	echo '<div class="notice-'.$notice->id.' col s12 l6 grid-break-2';
	if($key%2===0){
		// echo ' clearfix';
	}
	echo '"><div class="card notice-card"><div class="card-content"><div class="card-title center">Notice';
	echo '</div><p class="right-align mb10 grey-text text-darken-1">Date : '.date('dS M Y',strtotime($notice->date_submited)).'</p>';
	echo strip_tags($notice->notice_text,'<hr><u><b><p><a><strong><i><em><span><div><h1><h2><h3><h4><h5><h6><address><pre><ol><li>');
	echo '</div></div></div>';
}
unset($notice);
?>
</div>