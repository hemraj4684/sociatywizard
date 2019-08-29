$(d).ready(function(){
ff_v=0
$('.flat_float').keystop(function(){
if(ff_v===0){
ff_v=1
t=$(this)
t.prop('disabled',true);
page_loader()
$.ajax({
type:'post',
data:'var='+t.val()+'&user_token='+token,
url:URL+'flatsform/search_flat_keyword',
complete:function(){
ff_v=0
t.prop('disabled',false);
t.focus()
page_loader_exit()
},
success:function(res){
t.next().html(res)
}
})
}
if($(this).val().trim()===''){
$(this).prev().val('')
}
})
$('#new-user-form').submit(function(e){
PD(e)
t=$(this)
page_loader()
$('.submit-btn').prop('disabled',true)
$.ajax({
type:'post',
data:t.serialize(),
url:URL+'registeredmembersform/add_user',
dataType:'json',
complete:function(){
page_loader_exit()
$('.submit-btn').prop('disabled',false)
},
success:function(res){
if(res.success){
t[0].reset()
t.find('label').removeClass('active')
$('.flat_id').val('')
Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Member Added Successfully!',3000,'green success-area')
}
if(res.fn){
$('.fn_err').text(res.fn)
} else {
$('.fn_err').text('')
}
if(res.ln){
$('.ln_err').text(res.ln)
} else {
$('.ln_err').text('')
}
if(res.em){
$('.em_err').text(res.em)
} else {
$('.em_err').text('')
}
if(res.mo){
$('.mo_err').text(res.mo)
} else {
$('.mo_err').text('')
}
if(res.gn){
$('.gn_err').text(res.gn)
} else {
$('.gn_err').text('')
}
if(res.ot){
$('.ot_err').text(res.ot)
} else {
$('.ot_err').text('')
}
if(res.de){
$('.de_err').text(res.de)
} else {
$('.de_err').text('')
}
}
})
})
})
$(d).on('click','.drop-f-item',function(){
t=$(this)
inp = t.parents('.input-field').find('.flat_float')
inp.val(t.data('value'))
inp.prev().val(t.data('id'))
inp.next().html('')
})