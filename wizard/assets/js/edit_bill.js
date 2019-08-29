$(d).ready(function(){
$('.particular-btn').click(function(){
$('.particular-area tbody').append('<tr><td><input type="text" class="input-text" name="new_particulars[]" placeholder="Particulars"></td><td><input type="text" name="new_amount[]" class="invoice-p-amount input-text" placeholder="Amount"></td><td><button type="button" class="btn btn-small col s12 red accent-3 remove-particular"><i class="fa fa-close m0"></i></button></td></tr>')
})
$('.datepicker').pickadate({
selectMonths: true,
selectYears: 100,
format: 'dd-mm-yyyy'
});
$('#edit-bill').submit(function(e){
PD(e)
if(confirm('Are you sure, the changes will be notified to the flat members?')){
t=$(this)
$('.submit-btn').prop('disabled',true)
$('.submit-btn').html('<i class="fa fa-spinner fa-pulse"></i> Saving...')
$.ajax({
type:'post',
url:URL+'invoice/update',
data:t.serialize(),
error:function(err){
console.log(err)
},
complete:function(){
$('.submit-btn').prop('disabled',false)
$('.submit-btn').html('<i class="fa fa-edit"></i> Save Changes')
},
dataType:'json',
success:function(res){
$('.submit-btn').prop('disabled',true)
$('.submit-btn').html('<i class="fa fa-edit"></i> Save Changes')
if(res.success){
window.location.href=URL+'flats/view_bill/'+$('#bill_id').val()
window.opener.location.reload();
}
if(res.bill){
$('.send_to').text(res.send_to)
} else {
$('.send_to').text('')
}
if(res.due_date){
$('.due_date').text(res.due_date)
} else {
$('.due_date').text('')
}
if(res.fine){
$('.fine').text(res.fine)
} else {
$('.fine').text('')
}
}
})
}
})
$('.clean-interest-btn').click(function(){if(confirm('Are you sure ? You want to remove late interest amount ?')){t=$(this);t.prepend('<i class="fa fa-spin fa-spinner"></i>');t.prop('disabled',true);$.ajax({type:'get',url:URL+'invoice/remove_late_interest/'+$('#bill_id').val(),success:function(r){location.reload();window.opener.location.reload();}})}})
})
$(d).on('click','.remove-particular',function(){
$('#edit-bill').prepend('<input name="removed[]" type="hidden" value="'+$(this).data('id')+'">')
})