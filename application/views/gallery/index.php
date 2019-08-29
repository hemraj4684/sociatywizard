<div id="breadcrumbs-wrapper" class="animated fadeIn">
<div class="row mb0">
<div class="col s12 m8">
<h5 class="breadcrumbs-title"><i class="nav-icon mdi-image-photo-library"></i> Gallery</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Gallery</li>
</ol>
</div>
<div class="col s12 m4">
<a class="btn-small white-text waves-effect waves-light blue btn modal-trigger" href="#folder_model"><i class="fa fa-folder"></i> Create Folders</a>
</div>
</div>
</div>
<div class="row mt10 height-600"><div class="col s12">
<?=$this->session->flashdata('g_folder_removed')?>
<?php
if($query->num_rows()===0){
	echo '<div class="card center"><div class="card-content"><span class="card-title">You dont have any folder</span><p class="mt15">To start uploading images in your gallery you should first create a folder.</p><p class="mt15">To create a new folder <a class="btn grey darken-3 btn-small modal-trigger" href="#folder_model"><i class="fa fa-plus left"></i> Click Here</a></p></div></div>';
} else {
echo '<div class="card"><div class="card-content"><div class="row">';
$folders = $query->result();
foreach ($folders as $key => $value) {
	echo '<div class="col s6 m4 l3"><a class="r-able folder-link" data-id="'.h($value->id).'" data-name="'.h($value->folder_name).'" data-desc="'.h($value->description).'" href="'.base_url('gallery/folder/'.$value->id).'"><h1 class="mb0"><i class="folder-icon fa fa-folder-open"></i></h1><p class="black-text bold-500">'.h($value->folder_name).'</p></a></div>';
}
echo '<div class="col s6 m4 l3"><a class="folder-link modal-trigger" href="#folder_model"><h1 class="mb0"><i class="folder-icon red-text fa fa-plus-square"></i></h1><p class="black-text bold-500">Create New Folder</p></a></div>';
echo '</div></div></div>';
}
?>
</div>
</div>
<div id="folder_model" class="small-modal modal">
<?=form_open('','id="new_folder_form"')?>
<div class="modal-content">
  <h5 class="mt0 bold-700">Create New Folder</h5>
  <div class="input-field">
	<input name="folder" type="text" maxlength="100" autocomplete="off" placeholder="Folder Name">
  </div>
  <div class="input-field">
	<textarea class="materialize-textarea" placeholder="Description" name="description"></textarea>
  </div>
  <div class="col s12"><div class="res"></div></div>
</div>
<div class="modal-footer">
	<button class="waves-effect folder-c-btn btn-flat">Create</button>
	<button class="modal-close waves-effect btn-flat">Cancel</button>
</div>
<?=form_close()?>
</div>
<div id="prop_modal" class="small-modal modal">
<?=form_open('','id="folder_prop"')?>
<div class="modal-content">
  <div class="row">
  <h5 class="mt0 bold-500 mb15">Update Folder Properties</h5>
  	<div class="input-field col s12">
  		<label for="folder_name" class="active">Folder Name</label>
		<input name="folder" type="text" id="folder_name" maxlength="100" placeholder="Folder Name" autocomplete="off">
		<input name="old_folder" type="hidden" id="folder_name_old" maxlength="100" placeholder="Folder Name" autocomplete="off">
    </div>
	<div class="input-field col s12">
		<label for="folder_desc" class="active">Folder Description</label>
		<textarea class="materialize-textarea" placeholder="Folder Description" id="folder_desc" name="description"></textarea>
	</div>
	<input type="hidden" value="0" name="id" id="id">
	<div class="col s12"><div class="u-res"></div></div>
</div>
</div>
<div class="modal-footer">
	<button type="submit" class="waves-effect folder-u-btn btn-flat">Update</button>
	<button type="button" class="modal-close waves-effect btn-flat">Cancel</button>
</div>
<?=form_close()?>
</div>