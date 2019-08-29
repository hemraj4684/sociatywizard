<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m8">
<h5 class="breadcrumbs-title"><i class="mdi-image-photo-library"></i> Gallery</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('me')?>">Home</a> / </li>
<li class="active">Gallery</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600"><div class="col s12">
<?php
echo '<div class="card"><div class="card-content"><div class="row">';
foreach($folders as $key => $value) {
	echo '<div class="col s6 m4 l3"><a class="folder-link" href="'.base_url('me/gallery_folder/'.$value->id).'"><h1 class="mb0"><i class="folder-icon fa fa-folder-open"></i></h1><p class="black-text bold-500">'.h($value->folder_name).'</p></a></div>';
}
unset($notice);
echo '</div></div></div>';
?>
</div></div>