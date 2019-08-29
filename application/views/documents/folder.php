<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m5">
<h5 class="breadcrumbs-title"><i class="fa fa-folder-o"></i> Documents Folder</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('documents')?>">Documents</a> / </li>
<li class="active">Documents Folder</li>
</ol>
</div>
<?php
$society = $this->session->soc;
?>
<div class="col s12 m7">
<input class="filled-in file_select_all" name="file_select_all" id="file_select_all" type="checkbox">
<label id="file_select_all_label" for="file_select_all"></label>
<button data-folder="<?=$id?>" type="button" class="btn-small download-files white-text waves-effect waves-light grey darken-3 btn" href="#"><i class="fa fa-download"></i> Download</button>
<button data-folder="<?=$id?>"  type="button" class="btn-small remove-files white-text waves-effect waves-light red accent-3 btn" href="#"><i class="fa fa-remove"></i> Remove Files</button>
<a class="btn-small white-text waves-effect waves-light purple btn modal-trigger" href="#files_model"><i class="fa fa-file-archive-o"></i> Upload Documents</a>
</div>
</div>
</div>
<?=form_open('docsform/download_files','id="download_form" target="_blank"')?><input type="hidden" name="folder" value="<?=$id?>"><?=form_close()?>
<div class="row mt10 height-600">
<div class="col s12">
<?=$this->session->flashdata('file_upload_error')?>
<?=$this->session->flashdata('file_upload_success')?>
<?=$this->session->flashdata('file_removed')?>
<?php
if($files->num_rows()===0){
	echo '<div class="card center"><div class="card-content"><span class="card-title">You dont have any files in this folder</span><p class="mt15">Documents are visible only to admins, but you share documents with other society members too...</p><p class="mt15">To upload a new file <a class="btn grey darken-3 btn-small modal-trigger" href="#files_model"><i class="fa fa-plus left"></i> Click Here</a></p></div></div>';
}else{
echo '<div class="files-area card"><div class="card-content"><div class="row">';
echo '<div class="col s12"><div class="card-title mb5">'.h($folder->folder_name).'</div></div>';
echo '<div class="col s12 m6"><p class="mb15 grey-text text-darken-2">'.para($folder->description).'</p></div>';
echo '<div class="col s12 right-align m6"><p class="mb5 blue-grey-text text-darken-1 font-12 bold-500">Date Created : '.date('l, dS F Y',strtotime($folder->date_created)).'</p><p class="mb15 blue-grey-text text-darken-1 font-12 bold-500">Created By : '.h($folder->firstname).' '.h($folder->lastname).'</p></div>';
echo '<div class="clearfix"></div>';
$res = $files->result();
foreach ($res as $key => $value) {
	echo '<div class="col s6 m4 l3">';
	if(file_exists('assets/documents/'.$society.'/'.$value->file_name)){
		$mime = mime_content_type(('assets/documents/'.$society.'/'.$value->file_name));
		echo '<div data-id="'.h($value->id).'" data-folder="'.$id.'" data-href="'.base_url('assets/documents/'.$society.'/'.h($value->file_name)).'" class="file-link block"><h1 class="mb0">';
		$s_mime = substr($mime,0,4);
		if($s_mime=='imag') {
			echo '<i class="fa blue-text fa-file-image-o"></i>';
		} else if('application/pdf'==$mime) {
			echo '<i class="red-text text-accent-3 fa fa-file-pdf-o"></i>';
		} else if('application/zip'==$mime){
			echo '<i class="fa yellow-text text-darken-3 fa-file-zip-o"></i>';
		} else if('application/vnd.openxmlformats-officedocument.wordprocessingml.document'==$mime || $mime=='application/msword') {
			echo '<i class="fa indigo-text fa-file-word-o"></i>';
		} else if('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'==$mime || $mime=='application/vnd.ms-excel' || 'application/vnd.ms-office'==$mime) {
			echo '<i class="fa green-text text-darken-1 fa-file-excel-o"></i>';
		} else if('application/vnd.openxmlformats-officedocument.presentationml.presentation'==$mime){
			echo '<i class="fa pink-text text-accent-2 fa-file-excel-o"></i>';
		} else {
			echo '<i class="fa black-text fa-file-o"></i>';
		}
		echo '</h1><input type="checkbox" class="filled-in file_selected" value="'.h($value->id).'" id="'.h($value->id).'"><label class="black-text bold-500 mt0 ellipsis-tag" for="'.h($value->id).'">'.h($value->file_name).'</label></div>';
	} else {
		echo '<a data-position="left" data-tooltip="File Does Not Exist. Please Delete This File" data-folder="'.$id.'" data-id="'.h($value->id).'" href="#" class="tooltipped file-link block"><h1 class="mb0"><i class="red-text text-accent-3 fa-chain-broken fa"></i></h1><p class="black-text bold-500 mt0 ellipsis-tag">'.h($value->file_name).'</p></a>';
	}
	echo '</div>';
}
unset($value);
echo '</div></div></div>';
}
?>
</div>
</div>
<div id="files_model" class="modal">
<?=form_open_multipart('documents/add_files','id="new_docs_form"')?>
<div class="modal-content">
  <h5 class="bold-300 mb30 mt0">Upload Documents</h5>
  <div class="file-field input-field col s12 m6">
	<div class="btn indigo accent-3">
	<span>Files</span>
	<input type="file" id="select_file_input" name="userfile[]" accept=".doc,.docx,.jpg,.png,.pdf,.xls,.xlsx,.zip,.rar,.xml,.txt,.csv,.gif,.jpeg,.pptx,.ppt" multiple>
	<input type="hidden" name="MAX_FILE_SIZE" value="10480000">
	</div>
	<div class="file-path-wrapper">
	<input class="file-path validate" placeholder="Select files to upload" type="text">
	<input type="hidden" value="<?=$id?>" name="folder">
	</div>
  </div>
  <div class="col s12"><div class="red-text bold-500 text-accent-3 res"></div></div>
<p class="mb10">The valid file types you can upload are : doc, docx, jpg, png, pdf, xls, xlsx, zip, rar, xml, txt, csv, gif, jpeg, pptx, ppt</p>
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