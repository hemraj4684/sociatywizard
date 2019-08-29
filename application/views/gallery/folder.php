<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m6">
<h5 class="breadcrumbs-title"><i class="fa fa-folder-o"></i> Gallery Folder</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('gallery')?>">Gallery</a> / </li>
<li class="active">Gallery Folder</li>
</ol>
</div>
<div class="col s12 m6">
<input class="filled-in file_select_all" name="file_select_all" id="file_select_all" type="checkbox">
<label id="file_select_all_label" for="file_select_all"></label>
<button type="button" class="mb5 btn-small download-files white-text waves-effect waves-light grey darken-3 btn" href="#"><i class="fa fa-download"></i> Download</button>
<button type="button" data-id="<?=$id?>" class="mb5 btn-small remove-files white-text waves-effect waves-light red accent-3 btn" href="#"><i class="fa fa-remove"></i> Remove Files</button>
<a class="btn-small white-text waves-effect waves-light purple btn modal-trigger" href="#files_model"><i class="fa fa-file-archive-o"></i> Upload Images</a>
</div>
</div>
</div>
<div class="row mt10 height-600"><div class="col s12">
<?=$this->session->flashdata('g_file_upload_error')?>
<?=$this->session->flashdata('g_file_upload_success')?>
<?=$this->session->flashdata('g_file_removed')?>
<?php
$society = $this->session->soc;
?>
<?=form_open('galleryform/download_files','id="download_form" target="_blank"')?><input type="hidden" value="<?=$id?>" name="folder"><?=form_close()?>
<?php
if(empty($files)){
echo '<div class="card center"><div class="card-content"><span class="card-title">You dont have any files in this folder</span><p class="mt15">To upload a new file <a class="btn grey darken-3 btn-small modal-trigger" href="#files_model"><i class="fa fa-plus left"></i> Click Here</a></p></div></div>';
} else {
echo '<div class="card"><div class="card-content"><div class="row mb0">';
echo '<div class="col s12"><div class="card-title mb5">'.h($folder->folder_name).'</div></div>';
echo '<div class="col s12 m6"><p class="mb15 grey-text text-darken-2">'.para($folder->description).'</p></div>';
echo '<div class="col s12 right-align m6"><p class="mb5 blue-grey-text text-darken-1 font-12 bold-500">Date Created : '.date('l, dS F Y',strtotime($folder->date_created)).'</p><p class="mb15 blue-grey-text text-darken-1 font-12 bold-500">Created By : '.h($folder->firstname).' '.h($folder->lastname).'</p></div>';
echo '<div class="clearfix"></div>';
echo '<div class="files-area">';
foreach ($files as $file) {
echo '<div class="col s6 m3 l2"><a href="'.base_url('assets/gallery/'.$society.'/'.$file->image_name).'" data-id="'.h($file->id).'" data-folder="'.$id.'" class="block" data-lightbox="folder"><div class="img-file r-able hoverable z-depth-1" style="background-image:url('.base_url('assets/gallery/'.$society.'/'.$file->image_name).')"></div></a><div class="mb15"><input class="filled-in file_selected" id="img_'.$file->id.'" value="'.$file->id.'" type="checkbox"><label for="img_'.$file->id.'"></label></div></div>';
}
unset($file);
echo '</div>';
echo '<div class="col s6 m3 l2"><a href="#files_model" class="block modal-trigger"><div class="img-file hoverable last-option-add z-depth-1"><div class="extra-add"><i class="fa fa-plus"></i> Add More Files</div></div></a></div>';
echo '</div></div></div>';
}
?>
</div></div>
<div id="files_model" class="modal">
<?=form_open_multipart('gallery/add_files','id="new_img_form"')?>
<div class="modal-content">
  <h5 class="bold-300 mb30 mt0">Upload Documents</h5>
  <div class="file-field input-field col s12 m6">
	<div class="btn indigo accent-3">
	<span>Files</span>
	<input type="file" id="select_file_input" name="userfile[]" accept=".jpg,.png,.gif,.jpeg" multiple>
	<input type="hidden" name="MAX_FILE_SIZE" value="10480000">
	</div>
	<div class="file-path-wrapper">
	<input class="file-path validate" placeholder="Select files to upload" type="text">
	<input type="hidden" value="<?=$id?>" name="folder">
	</div>
  </div>
  <div class="col s12"><div class="red-text bold-500 text-accent-3 res"></div></div>
<p class="mb10">The valid file types you can upload are : jpg, png, gif, jpeg</p>
<p class="mb15">You can upload 5 files at a time and maximum file size should be 2MB each.</p>
</div>
<div class="file_upload_progress">
<div class="progress">
  <div class="determinate determine-upload"></div>
</div>
<div class="center count-upload-progress">0%</div>
</div>
<div class="modal-footer">
	<button type="submit" class="waves-effect folder-c-btn btn-flat">Upload</button>
	<button type="button" class="modal-close waves-effect btn-flat">Cancel</button>
</div>
<?=form_close()?>
</div>