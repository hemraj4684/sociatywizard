$(d).ready(function(){
$('#new-help-msg-form').submit(function(e){PD(e);t=$(this);page_loader();$('.submit-btn').prop('disabled',true)
$.ajax({
type:'post',url:URL+'userpageforms/new_helpdesk',success:function(res){
if(res.subject){
$('.subject').text(res.subject)
} else {
$('.subject').text('')
}
if(res.msg){
$('.h-message').text(res.msg)
} else {
$('.h-message').text('')
}
if(res.success){
t[0].reset();location.reload()
}
},data:t.serialize(),
dataType:'json',complete:function(){
page_loader_exit();
$('.submit-btn').prop('disabled',false)
}
})
})
})