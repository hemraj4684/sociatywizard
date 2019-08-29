dt_obj["aoColumnDefs"]={"bSortable":false,'aTargets':[0,6,7,8]},{"bSearchable":false,'aTargets':[0,8]}
$(d).ready(function(){$('.data_list').dataTable(dt_obj);$('.dataTables_wrapper select').show();
$('.check-ok').click(function(){
file = $('.cheque_select:checked')
if(file.length>0){
if(confirm('Are You Sure ?')){
t=$(this)
t.prop('disabled',true)
dataid = {}
dataid.type = t.data('id')
dataid.user_token = token
dataid.id = {}
page_loader()
$.each(file,function(k,v){
dataid.id[k] = $(this).val()
})
$.ajax({
type:'post',
data:dataid,
url:URL+'flatsform/pending_cheque_update',
dataType:'json',
complete:function(){
page_loader_exit()
t.prop('disabled',false)
},
success:function(res){
console.log(res)
if(res.success){
location.reload()
}
}
})
}
} else {
Materialize.toast('Please Select A Checkbox First',1000,'red accent-3 bold-500')
}
})
})