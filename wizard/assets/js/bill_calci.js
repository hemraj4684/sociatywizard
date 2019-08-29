function count_total(){
fnd = $('.invoice-p-amount')
tot = 0
fnd.each(function(){
tv = $(this).val()
if(!isNaN(tv) && tv.toString().trim().length>0){
tot = tot + parseFloat(tv)
}
})
$('#total').text(tot)}