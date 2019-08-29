<?php $this->load->view('layout/head'); ?>
<div class="row">
<div class="col s12">
<?php
echo form_open('','class="row" id="group_edits"');
echo '<div class="input-field col s12"><p class="font-12 red-text bold-500 text-accent-3 b-amount-err"></p><p class="font-12 red-text bold-500 text-accent-3 b-parti-err"></p><p class="font-12 red-text bold-500 text-accent-3 b-amount-err-new"></p><p class="font-12 red-text bold-500 text-accent-3 b-parti-err-new"></p>
<div class="right"><button type="submit" class="btn submit-btn btn-small indigo accent-3"><i class="fa fa-save"></i> Save Changes</button> <button type="button" onclick="window.close()" class="btn submit-btn btn-small red accent-3"><i class="fa fa-close"></i> Close</button></div></div>';
echo '<div class="input-field col s12"><input type="text" name="name" id="bg_name" value="'.h($bill->name).'" maxlength="50"><label for="bg_name">Bill Group Name</label><p class="err-under name_err"></p></div>';
echo '<div class="col s12"><table class="bordered highlight table old_items"><thead><tr><th>Particulars</th><th>Amount</th><th>Action</th></tr></thead><tbody>';
if(!empty($data)){
foreach ($data as $key => $value) {
	echo '<tr><td><input name="particular['.h($value->id).']" type="text" value="'.h($value->name).'"></td><td><input name="amount['.h($value->id).']" type="text" value="'.h($value->amount).'"></td><td><button type="button" data-id="'.h($value->id).'" class="btn red accent-3 remove-parti btn-small"><i class="fa fa-close m0"></i></button></td></tr>';
}
unset($value);
} else {
echo '<tr><td colspan="3">No Data Available</td></tr>';
}
echo '</tbody></table></div><input type="hidden" name="id" value="'.$id.'">';
?>
<div class="col s12"><table class="bordered highlight table new_items"><tbody></tbody></table></div>
<div class="input-field col s12">
<button type="button" class="btn btn-small add-partis green"><i class="fa fa-plus"></i> Add A Particular</button>
</div>
<?php
echo form_close();
?>
</div>
</div>
<script>var URL = "<?php echo base_url(); ?>"</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/materialize.min.js'); ?>"></script>
<?=script_js()?>
<script type="text/javascript">
$(d).ready(function(){
	$('body').addClass('white')
	$('#group_edits').submit(function(e){
		PD(e)
		t=$(this)
		$('.submit-btn').prop('disabled',true)
		$.ajax({
			type:'post',
			data:t.serialize()+'&rem_id='+rem_id,
			dataType:'json',
			url:URL+'societysettingsform/edit_bill_group',
			complete:function(){
				$('.submit-btn').prop('disabled',false)
			},
			success:function(res){
				console.log(res)
				if(res.success){
					Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Bill Group Updated Successfully!',2000,'green success-area')
					setTimeout(function(){
						if(window.opener){
							window.opener.location.reload();
						}
						location.reload()
					},2000)
				}
				if(res.name){
					$('.name_err').text(res.name)
				} else {
					$('.name_err').text('')
				}
				if(res.particular){
					$('.b-parti-err').text(res.particular)
				} else {
					$('.b-parti-err').text('')
				}
				if(res.amount){
					$('.b-amount-err').text(res.amount)
				} else {
					$('.b-amount-err').text('')
				}
				if(res.new_particular){
					$('.b-parti-err-new').text(res.new_particular)
				} else {
					$('.b-parti-err-new').text('')
				}
				if(res.new_amount){
					$('.b-amount-err-new').text(res.new_amount)
				} else {
					$('.b-amount-err-new').text('')
				}
			}
		})
	})
	rem_id=[]
	$(d).on('click','.remove-parti',function(){if($(this).data('id')){rem_id.push($(this).data('id'));console.log(rem_id);}$(this).parents('tr').remove()})
})
$(d).on('click','.add-partis',function(){
	$('.new_items tbody').append('<tr><td><input name="new_particular[]" type="text" placeholder="Particular"></td><td><input name="new_amount[]" type="text" placeholder="Amount"></td><td><button type="button" class="btn red accent-3 remove-parti btn-small"><i class="fa fa-close m0"></i></button></td></tr>')
})
</script>
</body>
</html>