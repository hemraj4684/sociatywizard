$(d).ready(function(){
	$.ajax({
		type:'get',
		url:URL+'societysettingsform/get_bill_groups',
		success:function(res){
			$('.bill-data').html(res)
		}
	})
	$.ajax({
		type:'get',
		url:URL+'societysettingsform/flat_and_bill',
		success:function(res){
			$('.flat_and_bill').html(res)
		}
	})
	$('#society_form_1').submit(function(e){
		PD(e)
		t = $(this)
		$('.submit-btn').prop('disabled',true)
		$.ajax({
			type:'post',
			url:URL+'societysettingsform/update_form1',
			data:t.serialize(),
			dataType:'json',
			complete:function(){
				$('.submit-btn').prop('disabled',false)
			},
			success:function(res){
				if(res.name){
					$('.name-err').text(res.name)
				} else {
					$('.name-err').text('')
				}
				if(res.address){
					$('.address-err').text(res.address)
				} else {
					$('.address-err').text('')
				}
				if(res.reg_no){
					$('.reg_no-err').text(res.reg_no)
				} else {
					$('.reg_no-err').text('')
				}
				if(res.notes){
					$('.notes-err').text(res.notes)
				} else {
					$('.notes-err').text('')
				}
				if(res.intr){
					$('.interest_number-err').text(res.intr)
				} else {
					$('.interest_number-err').text('')
				}
				if(res.success){
					Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Changes Saved Successfully!',3000,'green success-area')
				}
			}
		})
	})
	$('#society_form_2').submit(function(e){
		PD(e)
		t = $(this)
		$('.new-group-btn').prop('disabled',true)
		$.ajax({
			type:'post',
			url:URL+'societysettingsform/update_form2',
			data:t.serialize(),
			dataType:'json',
			complete:function(){
				$('.new-group-btn').prop('disabled',false)
			},
			success:function(res){
				if(res.name){
					$('.b-name-err').text(res.name)
				} else {
					$('.b-name-err').text('')
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
				if(res.success){
					Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Bill Group Added Successfully!',2000,'green success-area')
					setTimeout(function(){
						location.reload()
					},2000)
				}
			}
		})
	})
	$('.add-partis').click(function(){
		$('.new_part_group tbody').append('<tr><td><input name="particular[]" type="text" placeholder="Particular"></td><td><input name="amount[]" type="text" placeholder="Amount"></td><td><button type="button" class="btn red accent-3 remove-parti btn-small"><i class="fa fa-close m0"></i></button></td></tr>')
	})
})
$(d).on('click','.remove-parti',function(){$(this).parents('tr').remove()})
$(d).on('click','.remove_bill_group',function(){if(confirm('Are you sure, you want to delete this bill group ?')){t=$(this);t.parents('tr').fadeOut(500,function(){$(this).remove();Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Bill Group Deleted Successfully!',4000,'green success-area')});$.ajax({type:'get',url:URL+'societysettingsform/remove_bill/'+t.data('id')})}})
$(d).on('click','input[name="all_gs"]',function(){
	$('.'+$(this).data('target')).prop('checked',true).change()
})
$(d).on('change','.flat_bill_form table tbody input[type="radio"]',function(){
	$('.flat_bill_form button[type="submit"]').removeClass('hide')
})
$(d).on('submit','.flat_bill_form',function(e){PD(e)
	t=$(this)
	page_loader()
	$('.flat_bill_form button[type="submit"]').html('<i class="left fa fa-spin fa-spinner"></i> Saving Changes...').prop('disabled',true)
	$.ajax({
		type:'post',
		url:URL+'societysettingsform/update_flat_bill',
		dataType:'json',
		data:t.serialize(),
		complete:function(){
			page_loader_exit()
			$('.flat_bill_form button[type="submit"]').html('Save Changes').prop('disabled',false)
		},
		success:function(s){
			if(s.success){
				Materialize.toast('Changes Saved Successfully!',4000,'green bold-400')
			} else if(s.error){
				Materialize.toast(s.error,4000,'red accent-3 bold-400')
			}
		}
	})
})