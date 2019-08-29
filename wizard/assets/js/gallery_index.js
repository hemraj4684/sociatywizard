$(d).ready(function(){
	$.contextMenu({
	    selector: '.r-able',
	    trigger: 'right',
	    items: {
	        download: {
	        	name:'Properties',
	        	callback: function(key, opt){
	                $('#prop_modal').openModal()
	                $('#folder_name').val($(this).data('name'))
	                $('#folder_name_old').val($(this).data('name'))
	                $('#folder_desc').val($(this).data('desc'))
	                $('#id').val($(this).data('id'))
	            }
	        },
	        delete: {
	        	name:'Delete',
	        	callback: function(key, opt){
	                if(confirm('Are you sure, you want to delete this folder? All the related files will be lost!')){
	        			page_loader()
		                id = $(this).data('id')
		                $.ajax({
		                	type:'get',
		                	url:URL+'galleryform/delete_folder/'+id,
		                	success:function(res){
		                		location.reload()
		                	}
		                })
		            }
	            }
	        }
	    }
	});
	$('#folder_prop').submit(function(e){
		PD(e)
		$('.folder-u-btn').prop('disabled',true)
		$('.folder-u-btn').html('<i class="fa fa-spinner fa-pulse"></i> Updating...')
		t= $(this)
		$.ajax({
			type:'post',
			url:URL+'galleryform/update_folder',
			data:t.serialize(),
			dataType:'json',
			complete:function(){
				$('.folder-u-btn').prop('disabled',false)
				$('.folder-u-btn').html('Update')
			},
			success:function(res){
				$('.res').html('')
				if(res.folder){
					$('.u-res').html('<p class="red-text bold-500 text-accent-3">'+res.folder+'</p>')
				}
				if(res.description){
					$('.u-res').html('<p class="red-text bold-500 text-accent-3">'+res.description+'</p>')
				}
				if(res.id){
					$('.u-res').html('<p class="red-text bold-500 text-accent-3">'+res.id+'</p>')
				}
				if(res.success){
					$('.u-res').html('<div class="card green"><div class="card-content white-text"><i class="fa fa-thumbs-o-up"></i> Folder Successfully Updated!</div></div>')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})
	})
	$('#new_folder_form').submit(function(e){
		PD(e)
		$('.folder-c-btn').prop('disabled',true)
		$('.folder-c-btn').html('<i class="fa fa-spinner fa-pulse"></i> Creating...')
		t= $(this)
		$.ajax({
			type:'post',
			url:URL+'galleryform/add_folder',
			data:t.serialize(),
			dataType:'json',
			complete:function(){
				$('.folder-c-btn').prop('disabled',false)
				$('.folder-c-btn').html('Create')
			},
			success:function(res){
				$('.res').html('')
				if(res.folder){
					$('.res').html('<p class="red-text bold-500 text-accent-3">'+res.folder+'</p>')
				}
				if(res.description){
					$('.res').html('<p class="red-text bold-500 text-accent-3">'+res.description+'</p>')
				}
				if(res.success){
					t[0].reset()
					$('.res').html('<div class="card green"><div class="card-content white-text">Folder Created Successfully!</div></div>')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})
	})
})