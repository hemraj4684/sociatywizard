<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m8">
<h5 class="breadcrumbs-title"><i class="fa fa-sticky-note-o"></i> Notice Board</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Notice Board</li>
</ol>
</div>
<div class="col s12 m4">
<a class="btn-small white-text waves-effect waves-light yellow darken-2 btn modal-trigger" href="#new_notice_model"><i class="fa fa-plus"></i> Add New Notice</a>
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
	echo '"><textarea class="hide" id="data_'.$notice->id.'">'.h($notice->notice_text).'</textarea><div class="card notice-card"><div class="card-content"><div class="card-title center">Notice <i data-id="'.$notice->id.'" class="fa fa-close right ele-blur pointer-cursor red-text delete-notice" title="Remove this notice"></i> <i data-id="'.$notice->id.'" class="fa fa-edit right ele-blur pointer-cursor blue-text modal-trigger edit-btn" data-target="edit_notice_model" title="Edit this notice"></i>';
	echo '</div><p class="right-align mb10 grey-text text-darken-1">Date : '.date('dS M Y',strtotime($notice->date_submited)).'</p>';
	echo strip_tags($notice->notice_text,'<hr><u><b><p><a><strong><i><em><span><div><h1><h2><h3><h4><h5><h6><address><pre><ol><li>');
	echo '</div></div></div>';
}
unset($notice);
?>
<div class="col s12 l6"><div class="card yellow lighten-3"><a href="#new_notice_model" class="modal-trigger block black-text card-content"><div class="card-title center"><i class="fa fa-plus"></i> Add New Notice</div>
</a></div></div></div>
<div id="new_notice_model" class="modal">
<?=form_open('noticeboard/add_new','id="new_notice_form"')?>
<div class="modal-content">
  <h5 class="bold-300 mt0">New Notice</h5>
  <div class="col s12"><div class="red-text bold-500 text-accent-3 res"></div></div>
  <div class="input-field col s12"><textarea id="add-editor"></textarea></div>
</div>
<div class="modal-footer">
	<button type="submit" class="waves-effect submit-btn btn-flat">Submit</button>
	<button type="button" class="modal-close waves-effect btn-flat">Cancel</button>
</div>
<?=form_close()?>
</div>
<div id="edit_notice_model" class="modal">
<?=form_open('noticeboard/edit_notice','id="edit_notice_form"')?>
<div class="modal-content">
  <h5 class="bold-300 mt0">New Notice</h5>
  <div class="col s12"><div class="red-text bold-500 text-accent-3 res_edit"></div></div>
  <div class="input-field col s12"><textarea id="edit-editor"></textarea></div>
</div>
<input type="hidden" value="0" id="n_id" name="id">
<div class="modal-footer">
	<button type="submit" class="waves-effect submit-btn btn-flat">Submit</button>
	<button type="button" class="modal-close waves-effect btn-flat">Cancel</button>
</div>
<?=form_close()?>
</div>