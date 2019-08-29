$(d).ready(function(){
$('.subscription_payment_form')[0].reset()
$('.month_to_pay').change(function(){
dtv = $(this).val()
total = dtv*$(this).data('charges')
$('.total_money').html(total)
$('.total_months').html(dtv)
$('.payment_amount').val(total)
$('.subscription_payment_form').change()
})
})