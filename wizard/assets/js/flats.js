var exist_data={}
$(document).ready(function(){
	$('#add-flat-form').submit(function(e){
		PD(e)
		t = $(this)
		$('.submit-btn').prop('disabled',true)
		$('.submit-btn').html('<i class="fa fa-spinner fa-pulse"></i> Adding...')
		$.ajax({
			type:'post',
			url:URL+'flatsform/add',
			data:t.serialize(),
			dataType:'json',
			success:function(res){
				$('.submit-btn').prop('disabled',false)
				$('.submit-btn').html('<i class="mdi-content-add left"></i> Add')
				if(res.success){
					t[0].reset()
					t.find('label').removeClass('active')
					Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Flat Added Successfully!',3000,'green success-area')
				} else {
                    $('.form-success').html('')
                }
				if(res.flat_no){
					$('.name-err').text(res.flat_no)
				} else {
					$('.name-err').text('')
				}
				if(res.flat_wing){
					$('.wing-err').text(res.flat_wing)
				} else {
					$('.wing-err').text('')
				}
				if(res.no_err){
					$('.no-err').text(res.no_err)
				} else {
					$('.no-err').text('')
				}
				if(res.status_err){
					$('.status-err').text(res.status_err)
				} else {
					$('.status-err').text('')
				}
				if(res.owner_ln_err){
					$('.owner_ln_err').text(res.owner_ln_err)
				} else {
					$('.owner_ln_err').text('')
				}
				if(res.owner_fn_err){
					$('.owner_fn_err').text(res.owner_fn_err)
				} else {
					$('.owner_fn_err').text('')
				}
				if(res.exist){
					exist_data=res.exist
					$('.existing-user-display').show()
					if(res.exist.pic){
						$('.exist_data').html('<img class="img-thumb valign-middle z-depth-1" src="'+URL+'assets/members_picture/'+res.exist.pic+'">');
					} else {
						$('.exist_data').html('<img class="img-thumb valign-middle z-depth-1" src="'+URL+'assets/images/user_image.png">');
					}
					$('.exist_data').append(' <input type="hidden" value="'+res.exist.id+'" name="exist_id"><span class="font-16">'+res.exist.fn+' '+res.exist.ln+'</span> <button type="button" data-id="'+res.exist.id+'" class="exist_btn btn indigo accent-3 btn-small waves-effect waves-light"><i class="fa m0 fa-plus"></i></button>');
				} else {
					exist_data={}
					$('.existing-user-display').hide()
					$('.exist_data').html('')
				}
			}
		})
	})
	$('#edit-flat-form').submit(function(e){
		PD(e)
		t = $(this)
		$('.form-success').html('')
		$('.submit-btn').prop('disabled',true)
		$('.submit-btn').html('<i class="fa fa-spinner fa-pulse"></i> Updating')
		$.ajax({
			type:'post',
			url:URL+'flatsform/edit',
			data:t.serialize(),
			dataType:'json',
			complete:function(){
				$('.submit-btn').prop('disabled',false)
				$('.submit-btn').html('<i class="mdi-navigation-check left"></i> Update')
			},
			success:function(res){
				if(res.success){
					Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Flat Updated Successfully!',3000,'green success-area')
				} else {
                    $('.form-success').html('')
                }
				if(res.flat_no){
					$('.name-err').text(res.flat_no)
				} else {
					$('.name-err').text('')
				}
				if(res.flat_wing){
					$('.wing-err').text(res.flat_wing)
				} else {
					$('.wing-err').text('')
				}
				
				if(res.status_err){
					$('.status-err').text(res.status_err)
				} else {
					$('.status-err').text('')
				}
				if(res.owner_err){
					$('.owner_err').text(res.owner_err)
				} else {
					$('.owner_err').text('')
				}
				if(res.contact){
					$('.contact-err').text(res.contact)
				} else {
					$('.contact-err').text('')
				}
			}
		})
	})
})
$(d).on('click','.exist_btn',function(){
	$('#owner_fn').val(exist_data.fn)
	$('#owner_ln').val(exist_data.ln)
	Materialize.updateTextFields();
})