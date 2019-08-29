var dt_table;
$(d).ready(function(){
$('.datepicker').pickadate({
selectMonths: true,
selectYears: 10,
max:true,
format: 'dd-mm-yyyy'
});
$('.delete-ie').click(function(){
file = $('.file_selected:checked')
if(file.length===0){
Materialize.toast('Please Select An Item To Delete',2000,'red accent-3 bold-500')
} else {
if(confirm('Are you sure ?')){
dataid = {}
dataid.user_token = token
dataid.id = {}
$.each(file,function(k,v){
dataid.id[k] = $(this).val()
})
$.ajax({
type:'post',
url:URL+'ie_form/remove_expense',
data:dataid,
dataType:'json',
success:function(res){
if(res.err){
Materialize.toast(res.err,2000,'red accent-3 bold-500')
}
if(res.success){
Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Deleted Successfully',2000,'green success-area')
$.each(file,function(k,v){
$(".data_list").dataTable().fnDeleteRow($(this).parents('tr'));
})
}
}
})
}
}
})
if($('.trans').val()==='1'){
$.ajax({
type:'get',
url:URL+'ie_form/incomes_list',
success:function(res){
$('.result').html(res)
dt_table = DT_TRIGGER()
}
})
} else {
$.ajax({
type:'get',
url:URL+'ie_form/expenses_list',
success:function(res){
$('.result').html(res)
dt_table = DT_TRIGGER()
}
})
}
$('#search_expense').submit(function(e){
PD(e)
t=$(this)
$('.submit-btn').prop('disabled',true)
page_loader()
$.ajax({
type:'post',
data:t.serialize(),
dataType:'json',
url:URL+'ie_form/search_expense_datewise',
complete:function(){
$('.submit-btn').prop('disabled',false)
page_loader_exit()
},
success:function(res){
if(res.to_err){
$('.to-err').text(res.to_err)
} else {
$('.to-err').text('')
}
if(res.from_err){
$('.from-err').text(res.from_err)
} else {
$('.from-err').text('')
}
if(res.data){
dt_table.fnDestroy()
$('.data_list').empty()
$('.data_list').html(res.data)
dt_table = DT_TRIGGER()
$('.date_to_ch').html('<b>From :</b> '+$('#ex_df').val()+' <b>To :</b> '+$('#ex_dt').val())
}
}
})
})
})
function DT_TRIGGER(){dt_obj['aoColumnDefs']=[{"bSortable":false,'aTargets':[0,8]},{"bSearchable":false,'aTargets':[0,8]}];mydt=$('.data_list').dataTable(dt_obj).columnFilter({aoColumns:[null,null,null,{type:"select","bRegex":true,values:[{value:'^Cash',label:'Cash'},{value:'^Cheque',label:'Cheque'},{value:'^Other',label:'Other'}]},null,null,null,null,null]});$('.dataTables_wrapper select').show();$('.total_calc_display').text($('.total_calc').val());return mydt}