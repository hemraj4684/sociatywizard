$(d).ready(function(){$('.trigger-cat-model').click(function(){$('#add-ecat').openModal();$('.cattype').val($(this).data('type'))})
var ctx = document.getElementById("ie_canvas").getContext("2d");
$.ajax({
type:'get',
url:URL+'ie_form/ie_graph',
dataType:'json',
success:function(res){
var data = {
labels:["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
datasets:[
{
label: "Total Income",
fillColor:"rgba(25,50,125,0.4)",
strokeColor:"rgba(25,52,125,0.75)",
highlightFill:"rgba(220,220,220,1)",
highlightStroke:"rgba(220,220,220,1)",
data:res[0]
},{
label:"Total Expense",
fillColor:"rgba(25,50,125,0.4)",
strokeColor:"rgba(25,52,125,0.75)",
highlightFill:"rgba(220,220,220,1)",
highlightStroke:"rgba(220,220,220,1)",
data:res[1]
}
]
};
new Chart(ctx).Radar(data,{
responsive:true
});
}
})

$('#expense-category').submit(function(e){PD(e);t=$(this);page_loader();$('.add-iec').prop('disabled',true);
$.ajax({type:'post',url:URL+'ie_form/new_ie_category',data:t.serialize(),dataType:'json',complete:function(){page_loader_exit();$('.add-iec').prop('disabled',false);
},success:function(res){if(res.err){Materialize.toast(res.err,4000,'red accent-3 bold-500')}if(res.success && res.id && res.name){
if($('.cattype').val()==2){
$('.expense_cat_list').prepend('<li class="mb15 e_list_'+res.id+'"><span>'+res.name+'</span> <button data-id="'+res.id+'" class="iecat-rem btn-flat"><i class="fa m0 fa-close"></i></button> <button data-id="'+res.id+'" data-name="'+res.name+'" class="ie-cat-edit-trigger btn-flat"><i class="fa m0 fa-edit"></i></button></li>');
}else{
$('.income_cat_list').prepend('<li class="mb15 e_list_'+res.id+'"><span>'+res.name+'</span> <button data-id="'+res.id+'" class="iecat-rem btn-flat"><i class="fa m0 fa-close"></i></button> <button data-id="'+res.id+'" data-name="'+res.name+'" class="ie-cat-edit-trigger btn-flat"><i class="fa m0 fa-edit"></i></button></li>');
}
Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Added Successfully',4000,'green success-area');$('#add-ecat').closeModal();t[0].reset()}
}})})
$('#expense-category-edit').submit(function(e){PD(e);t=$(this);$('.edit-iec').prop('disabled',true);page_loader();
$.ajax({type:'post',url:URL+'ie_form/edit_ie_category',data:t.serialize(),dataType:'json',complete:function(){
$('.edit-iec').prop('disabled',false);page_loader_exit();
},success:function(res){
if(res.err){Materialize.toast(res.err,4000,'red accent-3 bold-500')}
if(res.success && res.id && res.name){Materialize.toast(res.err,4000,'green success-area');$('#edit-ecat').closeModal();Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Saved Successfully',4000,'green success-area');
$('.e_list_'+res.id).find('.ie-cat-edit-trigger').data('name',res.name)
$('.e_list_'+res.id).find('span').html(res.name)}}})})
})
$(d).on('click','.iecat-rem',function(){if(confirm('Are you sure, you are deleting this category ?')){t=$(this);t.prop('disabled',true);page_loader();$.ajax({type:'get',url:URL+'ie_form/remove_ie_cat/'+t.data('id'),complete:function(){page_loader_exit()},success:function(){
Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Deleted Successfully',4000,'green success-area');t.parents('li').remove();
}})}
})
$(d).on('click','.ie-cat-edit-trigger',function(){$('#edit-ecat').openModal();t=$(this);$('#ee-name').val(t.data('name'));Materialize.updateTextFields();$('.ecid').val(t.data('id'))})