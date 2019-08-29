$(d).ready(function(){
$.ajax({type:'get',url:URL+'memberrequests/admin_helpdesk_replies_on_dashboard',success:function(res){$('.help_reply_box').html(res)}})
$.ajax({type:'get',url:URL+'memberrequests/admin_notifications_dashboard',success:function(res){$('.new_user_notif').html(res)}})
})
$(d).on('click','.notif-dismiss',function(){t=$(this);t.parent().remove();Materialize.toast('Notification Deleted Successfully!',6000,'green');$.ajax({type:'get',url:URL+'memberrequests/remove_notif_admin/'+t.data('id'),})})