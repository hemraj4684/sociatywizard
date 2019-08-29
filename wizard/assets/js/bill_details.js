$(d).ready(function(){
$('.datepicker').pickadate({
selectMonths: true,
selectYears: 100,
format: 'dd-mm-yyyy'
});
$('input[name="p_method"]').change(function(){
if($(this).val()==2){
$('.on_pm').fadeIn()
}else{
$('.on_pm').fadeOut()
}
})
$('input[name="paid"]').change(function(){
if($(this).val()==2){
$('.p_details').fadeOut()
}else{
$('.p_details').fadeIn()
}
})
$('#edit-bill-details').submit(function(e){
PD(e)
t=$(this)
$('.submit-btn').prop('disabled',true)
$.ajax({
	type:'post',
	url:URL+'invoice/update_bill_details',
	data:t.serialize(),
	dataType:'json',
	complete:function(){
		$('.submit-btn').prop('disabled',false)
	},
	success:function(res){
		if(res.success){
			$('.success-area').show()
			setTimeout(function(){
				window.opener.location.reload();
				location.reload()
			},1000)
		}
		if(res.paid){
			$('.paid').text(res.paid)
		} else {
			$('.paid').text('')
		}
		if(res.p_method){
			$('.p_method').text(res.p_method)
		} else {
			$('.p_method').text('')
		}
		if(res.amt_paid){
			$('.amt_paid').text(res.amt_paid)
		} else {
			$('.amt_paid').text('')
		}
		if(res.dop_err){
			$('.dop_err').text(res.dop_err)
		} else {
			$('.dop_err').text('')
		}
	}
})
})
})