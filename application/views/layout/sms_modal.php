<div id="sms_modal" class="modal">
<?php echo form_open('','id="sms_form"'); ?>
<div class="modal-content">
<h4>Send SMS</h4>
<textarea class="materialize-textarea" name="message" placeholder="Enter your message..."></textarea>
<input name="url" type="hidden" value="<?=uri_string()?>">
<div class="form-errors err_on_modal"><div class="msg_err"></div><div class="user_err"></div></div>
<div class="form-success white-text"></div>
</div>
<div class="modal-footer">
<button type="submit" class="modal-action btn-flat send-sms-btn">Send</button>
<button type="button" class="modal-action modal-close btn-flat">Cancel</button>
</div>
<?php echo form_close(); ?>
</div>