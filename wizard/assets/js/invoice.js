$(d).ready(function(){
// $('.adv-m-btn').click(function(){
// 	$('.upto_field').toggle()
// 	$('#to_month').prop('selectedIndex',0)
// 	$('#to_month').material_select();
// })
$('.datepicker').pickadate({
    selectMonths: true,
    selectYears: 100,
    format: 'dd-mm-yyyy'
});
$('#group').change(function(){
	id = $(this).val()
	page_loader()
	$.ajax({
		type:'get',
		url:URL+'invoice/get_group_ajax/'+id,
		complete:function(){
			page_loader_exit()
		},
		success:function(res){
			$('.particular-area-init').html(res)
			count_total()
		}
	})
})
$('#invoice-form').submit(function(e){
e.preventDefault()
if(confirm('Are you sure you want to generate bill ?')){
t = $(this)
$('.submit-btn').prop('disabled',true)
$('.submit-btn').html('<i class="fa fa-spinner fa-pulse"></i> Sending...')
$('.success-msg').hide()
$.ajax({
	type:'post',
	url:URL+'invoice/create',
	data:t.serialize(),
	dataType:'json',
	complete:function(){
		$('.submit-btn').prop('disabled',false)
        $('.submit-btn').html('<i class="mdi-content-send right"></i> Send')
	},
	success:function(res){
		if(res.success){
			location.reload()
		}
		if(res.month){
			Materialize.toast(res.month,4000,'red accent-3 bold-400')
		}
		if(res.error){
			Materialize.toast(res.error,4000,'red accent-3 bold-400')
		}
		if(res.to_month){
			Materialize.toast(res.to_month,4000,'red accent-3 bold-400')
		}
		if(res.year){
			Materialize.toast(res.year,4000,'red accent-3 bold-400')
		}
		if(res.due_date){
			Materialize.toast(res.due_date,4000,'red accent-3 bold-400')
		}
		if(res.fine){
			Materialize.toast(res.fine,4000,'red accent-3 bold-400')
		}
		if(res.group){
			Materialize.toast(res.group,4000,'red accent-3 bold-400')
		}
	}
})
}
})
})