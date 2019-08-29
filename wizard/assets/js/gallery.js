$(d).ready(function(){
	lightbox.option({
      'resizeDuration': 100,
      'fadeDuration' : 100,
      'wrapAround' : true
    })
    $('.download-files').click(function(){
		t = $(this)
		file = $('.file_selected:checked')
		if(file.length===0){
			Materialize.toast('Please Select A File To Download',1000,'red accent-3 bold-500')
		} else {
			dataid = {}
			dataid.id = {}
			$.each(file,function(k,v){
				dataid.id[k] = $(this).val()
				$('#download_form').append('<input type="hidden" type="text" class="dw_f" name="id[]" value="'+$(this).val()+'">')
			})
			$('#download_form').submit()
			t.prop('disabled',false)
			$('.dw_f').remove()
		}
	})
	$('.remove-files').click(function(){
		t = $(this)
		file = $('.file_selected:checked')
		if(file.length===0){
			Materialize.toast('Please Select A File To Delete',1000,'red accent-3 bold-500')
		} else {
			if(confirm('Are you sure, you want to delete the selected file(s)?')){
				dataid = {}
				dataid.id = {}
				dataid.folder = $(this).data('id')
				dataid.user_token = token
				page_loader()
				$.each(file,function(k,v){
					dataid.id[k] = $(this).val()
				})
				t.prop('disabled',true)
				$.ajax({
					data:dataid,
					url:URL+'galleryform/delete',
					type:'post',
					complete:function(){
						t.prop('disabled',false)
					},
	            	dataType:'json',
	            	success:function(res){
	            		location.reload()
	            	}
				})
			}
		}
	})
    $.contextMenu({
	    selector: '.r-able',
	    trigger: 'right',
	    items: {
	        download: {
	        	name:'Download',
	        	callback: function(key, opt){
	                window.open($(this).parent().attr('href'), '_blank')
	            }
	        },
	        delete: {
	        	name:'Delete',
	        	callback: function(key, opt){
	                if(confirm('Are you sure, you want to delete this image?')){
	        			page_loader()
		                id = $(this).parent().data('id')
		                folder = $(this).parent().data('folder')
		                $.ajax({
		                	type:'post',
		                	url:URL+'galleryform/delete',
		                	data:'id[]='+id+'&user_token='+token+'&folder='+folder,
		                	error:function(err){
		                		console.log(err)
		                	},
		                	dataType:'json',
		                	success:function(res){
		                		location.reload()
		                	}
		                })
		            }
	            }
	        }
	    }
	});
	$('#new_img_form').submit(function(e){
		PD(e)
		if($("#select_file_input").val()){
		if($("#select_file_input").get(0).files.length<6){
		t=$(this)
		$('.submit-btn').prop('disabled',true)
		$('.res').html('')
		$.ajax({
			dataType:'json',
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				$('.file_upload_progress').show()
			    xhr.upload.addEventListener("progress", function(evt) {
			    if (evt.lengthComputable) {
			        var percentComplete = evt.loaded / evt.total;
			        percentComplete = parseInt(percentComplete * 100);
			        $('.determine-upload').css({'width':percentComplete+'%'})
			        $('.count-upload-progress').html(percentComplete+'%')
			    }
			    }, false);
				return xhr;
			},
			type:'post',
			url:URL+'galleryform/add_files',
			data:new FormData(this),
			processData:false,
			contentType:false,
			complete:function(){
				$('.folder-c-btn').prop('disabled',false)
			},
			success:function(res){
				if(res.success){
					location.reload()
				}
				if(res.error){
					$('.res').text(res.error)
				}
			}
		})
		} else {
			$('.res').text('You can upload only 5 files at a time.')
		}
		} else {
			$('.res').text('You have not selected any file to upload.')
		}
	})
})