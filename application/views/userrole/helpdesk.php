<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m8">
<h5 class="breadcrumbs-title"><i class="mdi-hardware-headset-mic"></i> Helpdesk</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('me')?>">Home</a> / </li>
<li class="active">Helpdesk</li>
</ol>
</div>
<div class="col s12 m4">
<button data-target="new-help-msg" class="btn indigo accent-3 modal-trigger btn-small"><i class="mdi-editor-mode-edit"></i> New Message</button>
</div>
</div>
</div>
<div class="row mt10 height-600"><div class="col s12">
<div class="card"><div class="card-content">
<table class="table bordered striped"><thead><th>#</th><th>Subject</th><th>Message Type</th><th>Date Sent</th><th width="125">Action</th></thead><tbody>
<?php
	foreach ($data as $key => $value) {
		echo '<tr><td>'.++$key.'</td><td>'.h($value->message_subject).'</td><td>';
		if($value->conv_type==='2'){
			echo 'General';
		} else {
			echo 'Complaint';
		}
		echo '</td><td>'.date('h:ia, dS M Y',strtotime($value->date_sent)).'</td><td><a href="'.base_url('me/helpdesk_item/'.$value->id).'" class="btn btn-small"><i class="mdi-action-view-list"></i> view</a></td></tr>';
	}
	unset($value);
?>
</tbody></table>
</div></div>
</div>
</div>
<div class="modal modal-fixed-footer" id="new-help-msg">
<?=form_open('','id="new-help-msg-form"')?>
<div class="modal-content">
<div class="row">
<h5 class="col s12">New Message</h5>
<div class="input-field col s12">
<input id="subject" name="subject" type="text" maxlength="255">
<label for="subject">Subject</label>
<p class="err-under subject"></p>
</div>
<div class="input-field col s12">
<textarea id="h-message" class="materialize-textarea" name="h-message"></textarea>
<label for="h-message">Message</label>
<p class="err-under h-message"></p>
</div>
<div class="input-field col s12"><p class="mt0"><b>Message Type</b></p>
<input type="radio" id="uh-type1" name="type" value="2"><label for="uh-type1">General</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" id="uh-type2" name="type" value="1" checked><label for="uh-type2">Complaint</label>
</div>
</div>
</div>
<div class="modal-footer">
<button type="submit" class="btn-flat submit-btn">Submit</button>
<button type="button" class="modal-close btn-flat">Cancel</button>
</div>
<?=form_close()?>
</div>