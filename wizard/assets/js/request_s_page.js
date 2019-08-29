function init_req(t,e){
t.prop('disabled',true);page_loader();
$.ajax({type:'get',url:URL+'registeredmembersform/request_admin_init/'+t.data('id')+'/'+e,dataType:'json',complete:function(){page_loader_exit();t.prop('disabled',false)},
success:function(res){if(res.err){Materialize.toast(res.err,3000,'red accent-3 bold-500')}
if(res.success){
if(e==2){Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; User Rejected Successfully',3000,'green success-area bold-500');}
if(e==1){t.parent().remove();Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; User Accepted Successfully',3000,'green success-area bold-500');}
}}})
}
$(d).ready(function(){$('.accept-request').click(function(){
if(confirm('Are you sure ? You are accepting the request ?')){
init_req($(this),1)
}
})
$('.reject-request').click(function(){
if(confirm('Are you sure ? You are rejecting the request ?')){
init_req($(this),2)
}
})})