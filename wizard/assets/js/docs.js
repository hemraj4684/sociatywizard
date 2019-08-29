$(d).ready(function(){
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
			if(confirm('Are you sure, you want to delete this file?')){
				dataid = {}
				dataid.id = {}
				dataid.user_token = token
				dataid.folder = $(this).data('folder')
				page_loader()
				$.each(file,function(k,v){
					dataid.id[k] = $(this).val()
				})
				t.prop('disabled',true)
				$.ajax({
					data:dataid,
					url:URL+'docsform/delete',
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
	    selector: '.file-link',
	    trigger: 'right',
	    items: {
	        download: {
	        	name:'Download',
	        	callback: function(key, opt){
	                $('.file_selected').prop('checked',false)
	                $(this).find('.file_selected').prop('checked',true)
	                $('.download-files').click()
	            }
	        },
	        delete: {
	        	name:'Delete',
	        	callback: function(key, opt){
	        		if(confirm('Are you sure, you want to delete this file?')){
	        			page_loader()
		                id = $(this).data('id')
		                folder = $(this).data('folder')
		                $.ajax({
		                	type:'post',
		                	url:URL+'docsform/delete',
		                	data:'id[]='+id+'&user_token='+token+'&folder='+folder,
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
	$('#new_docs_form').submit(function(e){
		PD(e)
		if($("#select_file_input").val()){
			if($("#select_file_input").get(0).files.length<6){
			t=$(this)
			$('.folder-c-btn').prop('disabled',true)
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
				url:URL+'docsform/add_files',
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