$(d).ready(function(){
$('.reply-area').scrollTop($('.reply-area').prop("scrollHeight"));
$('#reply_helpdesk').submit(function(e){
PD(e)
if($('.reply-input').val().trim().length>0){
page_loader()
$('.submit-btn').prop('disabled',true)
t=$(this)
$.ajax({
type:'post',
url:URL+'helpdeskforms/insert_reply',
data:t.serialize(),
complete:function(){
page_loader_exit()
$('.submit-btn').prop('disabled',false)
},
success:function(res){
$('.reply-area').append(res)
t[0].reset()
$('.reply-area').scrollTop($('.reply-area').prop("scrollHeight"));
}
})
}
})
$('input[name="conv_status"]').change(function(){
if(confirm('Confirm?')){
$('#status_conv_form').submit()
} else {
$('#status_conv_form')[0].reset()
}
})
$('#status_conv_form').submit(function(e){
PD(e)
t=$(this)
page_loader()
$.ajax({
type:'post',
url:URL+'helpdeskforms/change_conv_status',
data:t.serialize(),
complete:function(){
location.reload()
}
})
})
})