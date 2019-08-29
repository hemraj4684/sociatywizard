<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m8">
<h5 class="breadcrumbs-title"><i class="mdi-image-photo-library"></i> Gallery</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('me')?>">Home</a> / </li>
<li class="active"><a href="<?=base_url('me/gallery')?>">Gallery</a> / </li>
<li class="active">Gallery Files</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600"><div class="col s12">
<?php
echo '<div class="card"><div class="card-content"><div class="row">';
echo '<div class="col s12"><div class="card-title mb5">'.h($folder->folder_name).'</div></div>';
echo '<div class="col s12 m6"><p class="mb15 grey-text text-darken-2">'.para($folder->description).'</p></div>';
echo '<div class="col s12 right-align m6"><p class="mb5 blue-grey-text text-darken-1 font-12 bold-500">Date Created : '.date('l, dS F Y',strtotime($folder->date_created)).'</p><p class="mb15 blue-grey-text text-darken-1 font-12 bold-500">Created By : '.h($folder->firstname).' '.h($folder->lastname).'</p></div>';
echo '<div class="clearfix"></div>';
echo '<div class="files-area">';
foreach ($files as $file) {
echo '<div class="col s6 m3 l2"><a href="'.base_url('assets/gallery/'.$this->society.'/'.$file->image_name).'" data-id="'.h($file->id).'" data-folder="'.$id.'" class="block" data-lightbox="folder"><div class="img-file hoverable z-depth-1" style="background-image:url('.base_url('assets/gallery/'.$this->society.'/'.$file->image_name).')"></div></a></div>';
}
unset($file);
unset($notice);
echo '</div></div></div></div>';
?>
</div></div>