$(d).ready(function(){
var edit_new = CKEDITOR.replace('add-editor',{customConfig:'ckeditor_config.js',toolbarCanCollapse:true});
var edit_old = CKEDITOR.replace('edit-editor',{customConfig:'ckeditor_config.js',toolbarCanCollapse:true});
$('.edit-btn').click(function(){
$('#n_id').val($(this).data('id'))
edit_old.setData($('#data_'+$(this).data('id')).val())
})
$('.delete-notice').click(function(){
if(confirm('Are you sure you want to delete this notice?')){
id = $(this).data('id')
page_loader()
$.ajax({
type:'get',
url:URL+'nbform/delete/'+id,
complete:function(){
page_loader_exit()
},
success:function(){
$('.notice-'+id).addClass('animated flipOutX')
setTimeout(function(){
$('.notice-'+id).remove()
},1500)
}
})
}
})
$('#new_notice_form').submit(function(e){
PD(e)
page_loader()
$('.submit-btn').prop('disabled',true)
t=$(this)
$('.res').html('')
$.ajax({
type:'post',
url:URL+'nbform/adding',
data:t.serialize()+'&notice='+encodeURIComponent(edit_new.getData()),
dataType:'json',
complete:function(){
$('.submit-btn').prop('disabled',false)
page_loader_exit()
},
success:function(res){
if(res.error){
$('.res').html(res.error)
}
if(res.success){
$('<div class="card-panel success-area green white-text"><i class="fa fa-thumbs-o-up"></i> Notice Added Successfully</div>').insertAfter('.res')
setTimeout(function(){location.reload()},1500)
}
}
})
})
$('#edit_notice_form').submit(function(e){
PD(e)
$('.submit-btn').prop('disabled',true)
t=$(this)
$('.res_edit').html('')
$.ajax({
type:'post',
url:URL+'nbform/edit_notice',
data:t.serialize()+'&notice='+encodeURIComponent(edit_old.getData()),
dataType:'json',
complete:function(){
$('.submit-btn').prop('disabled',false)
},
success:function(res){
if(res.error){
$('.res_edit').html(res.error)
}
if(res.success){
$('<div class="card-panel success-area green white-text"><i class="fa fa-thumbs-o-up"></i> Notice Updated Successfully</div>').insertAfter('.res_edit')
setTimeout(function(){location.reload()},1500)
}
}
})
})
})