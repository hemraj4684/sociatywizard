<?php $this->load->view('layout/head'); ?>
<div class="row">
<div class="col s12">
<?=form_open('','id="edit_ie"')?><input type="hidden" value="<?=$data->trans_type?>" name="trans">
<div class="input-field col s12 right-align">
<button type="submit" class="submit-btn btn btn-small submit-btn blue"><i class="fa fa-save"></i> Save Changes</button>
<button onclick="window.close()" class="btn btn-small red accent-3"><i class="fa fa-close"></i> Cancel</button>
</div>
<div class="input-field col s12 m6 l4">
<select name="note">
<option value="0">Select Particular</option>
<?php
$c_id = $data->category_id;
foreach($drop as $key => $value){
echo '<option value="'.$value->id.'"';
if($c_id===$value->id){
echo ' selected';
}
echo '>'.h($value->name).'</option>';
}
unset($value);
?>
</select>
</div>
<div class="input-field col s12 m6 l4">
	<input type="text" id="amount" name="amount" value="<?=h($data->amount)?>"><label for="amount">Amount</label><p class="err-under amt-err"></p>
</div>
<div class="input-field col s12 m6 l4">
	<input type="text" id="date" class="datepicker" name="date" value="<?=date('d-m-Y',strtotime($data->date_of_payment))?>"><label for="date">Date of payment</label><p class="err-under date-err"></p>
</div>
<div class="input-field col s12 m6 l4">
	<input type="text" id="payee" name="payee" maxlength="50" value="<?=h($data->giver_taker)?>"><label for="payee"><?=($data->trans_type==='1') ? 'Received From' : 'Paid To'?></label><p class="err-under pay-err"></p>
</div>
<div class="input-field col s12 m6 l4">
	<input type="radio" id="pm1" name="pm" value="1"<?php if($data->payment_method==='1') { echo ' checked';}?>><label for="pm1">Cash</label>
	<input type="radio" id="pm2" name="pm" value="2"<?php if($data->payment_method==='2') { echo ' checked';}?>><label for="pm2">Cheque</label>
	<input type="radio" id="pm3" name="pm" value="3"<?php if($data->payment_method==='3') { echo ' checked';}?>><label for="pm3">Other</label>
	<p class="err-under pm-err"></p>
</div>
<div class="input-field col s12 m6 l4">
	<input type="text" id="cheque_no" name="cheque_no" maxlength="50" value="<?=h($data->cheque_no)?>"><label for="cheque_no">Cheque Number</label><p class="err-under cheque-err"></p>
</div>
<?php if($data->trans_type==='2'){ ?>
<div class="input-field col s12">
	<b>Expense Authorised By</b><?php
		foreach ($assocs as $key => $value) {
			echo '<div class="mb5"><input name="authorised" type="radio" id="assoc-'.$value->id.'" value="'.$value->id.'"';
			if($data->authorised_by===$value->id){
				echo ' checked';
			}
			echo '><label for="assoc-'.$value->id.'">'.h($value->firstname.' '.$value->lastname).'</label></div>';
		}
		unset($value);
	?>
</div>
<?php } ?>
<input type="hidden" value="<?=h($id)?>" name="id">
<?=form_close()?>
</div>
</div>
<script>var URL = "<?php echo base_url(); ?>"</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/materialize.min.js'); ?>"></script>
<?=script_js()?>
<script type="text/javascript">
$(d).ready(function(){
	$('body').addClass('white')
	$('.datepicker').pickadate({selectMonths:true,selectYears:100,format:'dd-mm-yyyy'});
	$('#edit_ie').submit(function(e){
		PD(e)
		t=$(this)
		$('.submit-btn').prop('disabled',true)
		page_loader()
		$.ajax({
			type:'post',
			url:URL+'ie_form/edit_trans',
			dataType:'json',
			data:t.serialize(),
			complete:function(){
				page_loader_exit()
				$('.submit-btn').prop('disabled',false)
			},
			success:function(res){
				if(res.success){
					Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Successfully Updated',2000,'green success-area')
					setTimeout(function(){
						if(window.opener){
							window.opener.location.reload();
						}
						location.reload()
					},2000)
				}
				if(res.amt){
					$('.amt-err').text(res.amt)
				} else {
					$('.amt-err').text('')
				}
				if(res.pm){
					$('.pm-err').text(res.pm)
				} else {
					$('.pm-err').text('')
				}
				if(res.payee){
					$('.pay-err').text(res.payee)
				} else {
					$('.pay-err').text('')
				}
				if(res.date){
					$('.date-err').text(res.date)
				} else {
					$('.date-err').text('')
				}
				if(res.note){
					$('.note-err').text(res.note)
				} else {
					$('.note-err').text('')
				}
				if(res.cheque){
					$('.cheque-err').text(res.cheque)
				} else {
					$('.cheque-err').text('')
				}
			}
		})
	})
})
</script>
</body>
</html>