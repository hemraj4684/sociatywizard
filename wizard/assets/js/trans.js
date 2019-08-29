$(d).ready(function(){
$('.datepicker').pickadate({
selectMonths: true,
selectYears: 10,
max:true,
format: 'dd-mm-yyyy'
});
	$('#expense_form').submit(function(e){
		PD(e)
		$('.submit-btn').prop('disabled',true)
		t = $(this)
		$.ajax({
			type:'post',
			url:URL+'ie_form/add_expense',
			dataType:'json',
			data:t.serialize(),
			complete:function(){
				$('.submit-btn').prop('disabled',false)
			},
			success:function(res){
				if(res.success){
					t[0].reset()
					trns = 'Expense'
					if($('.trans').val()==='1'){
						trns = 'Income'
					}
					Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; '+trns+' Added Successfully',2000,'green success-area')
					Materialize.updateTextFields();
				}
				if(res.amt){
					$('.amt-err').text(res.amt)
				} else {
					$('.amt-err').text('')
				}
				if(res.pm){
					$('.pm-err').text(res.pm)
				} else {
					$('.pm-err').text('')
				}
				if(res.payee){
					$('.pay-err').text(res.payee)
				} else {
					$('.pay-err').text('')
				}
				if(res.date){
					$('.date-err').text(res.date)
				} else {
					$('.date-err').text('')
				}
				if(res.note){
					$('.note-err').text(res.note)
				} else {
					$('.note-err').text('')
				}
				if(res.cheque){
					$('.cheque-err').text(res.cheque)
				} else {
					$('.cheque-err').text('')
				}
			}
		})
	})
})