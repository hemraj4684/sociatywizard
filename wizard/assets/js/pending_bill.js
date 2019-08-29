dt_obj["aoColumnDefs"]={"bSortable":false,'aTargets':[2,7]},{"bSearchable":false,'aTargets':[0,7]};$(d).ready(function(){$('.data_list').dataTable(dt_obj);$('.dataTables_wrapper select').show();
$('.mb-reminder-btn').click(function(){t=$(this);if(confirm('Are you sure, you want to send reminder for the pending bills ?')){t.prop('disabled',true);t.html('<i class="fa fa-spinner fa-spin"></i> Sending...');page_loader();
data={};data.user_token=token;data.data={};data.name={};chk=$('.data_list .sms_ok:checked');
$.each(chk,function(k,v){data.data[k]={number:$(this).data('number'),name:$(this).data('name'),amount:$(this).data('amount'),month:$(this).data('month'),duedate:$(this).data('duedate')}});
$.ajax({type:'post',data:data,url:URL+'smsgateway/send_mb_reminder',success:function(res){
if(res.success){Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Reminder has been sent successfully',5000,'success-area green')}
if(res.err){Materialize.toast('<i class="fa fa-warning"></i> &nbsp; '+res.err,5000,'success-area red accent-3')}
},complete:function(){t.prop('disabled',false);t.html('<i class="fa fa-envelope"></i> Send Reminder');page_loader_exit()},dataType:'json'
})}})
$('.payment-done-multi').click(function(){chk=$('.data_list .sms_ok:checked');if(chk.length>0){if(confirm('Are you sure ?')){t=$(this);t.prop('disabled',true);page_loader();data={};data.id={};data.user_token=token;
$.each(chk,function(k,v){data.id[k]=$(this).data('invoice')})
$.ajax({type:'post',dataType:'json',url:URL+'invoice/multiple_payments',data:data,complete:function(){t.prop('disabled',false);page_loader_exit();},success:function(res){
if(res.success){Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Done successfully',5000,'success-area green');
$.each(chk,function(k,v){$(".data_list").dataTable().fnDeleteRow($(this).parents('tr'));})
}
if(res.err){Materialize.toast('<i class="fa fa-warning"></i> &nbsp; '+res.err,5000,'bold-500 red accent-3')}
}})}}else{Materialize.toast('<i class="fa fa-warning"></i> &nbsp; Please select atleast one bill',5000,'bold-500 red accent-3')}})
})