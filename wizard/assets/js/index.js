(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create','UA-77429908-1','auto');ga('send','pageview');
var options = [
{selector:'.box-1',offset:205,callback:'$(".box-1").css("visibility","visible");$(".box-1").addClass("flipInY")'},
{selector:'.box-2',offset:205,callback:'$(".box-2").css("visibility","visible");$(".box-2").addClass("flipInY")'},
{selector:'.box-3',offset:205,callback:'$(".box-3").css("visibility","visible");$(".box-3").addClass("flipInY")'},
{selector:'.box-4',offset:205,callback:'$(".box-4").css("visibility","visible");$(".box-4").addClass("flipInY")'},
{selector:'.box-5',offset:205,callback:'$(".box-5").css("visibility","visible");$(".box-5").addClass("flipInY")'},
{selector:'.box-6',offset:205,callback:'$(".box-6").css("visibility","visible");$(".box-6").addClass("flipInY")'},
{selector:'.box-7',offset:205,callback:'$(".box-7").css("visibility","visible");$(".box-7").addClass("flipInY")'},
{selector:'.box-8',offset:205,callback:'$(".box-8").css("visibility","visible");$(".box-8").addClass("flipInY")'}
];
$(function(){
$('#new-enquiry').submit(function(e){e.preventDefault();t=$(this);$('.enq-btn').prop('disabled',true);$.ajax({type:'post',url:URL+'home/new_society_enquiry',data:t.serialize(),dataType:'json',success:function(res){
$('.enq-btn').prop('disabled',false);
if(res.err){
Materialize.toast(res.err,2000,'red accent-3 bold-500')
}
if(res.success){
t[0].reset()
Materialize.toast('You message has been delivered successfully.',10000,'green bold-500')
Materialize.toast('You will be contacted shortly.',10000,'green bold-500')
$('#register-society-modal').closeModal();
}
}
})})
$('.modal-trigger').leanModal()
$('#fp-form-verify').submit(function(e){
e.preventDefault()
if($('#mobile-fp').val().trim().length>0){
t=$(this)
$.ajax({
type:'post',
data:t.serialize(),
url:URL+'home/verify_code_fp',
dataType:'json',
success:function(res){
if(res.err){
Materialize.toast(res.err, 4000,'white-text red accent-3 bold-500')
}
if(res.success){
window.location.href=URL+'home/get_new_password_mobile'
}
}
})
} else {
Materialize.toast('Please Enter 6 Digit Verification Code', 4000,'white-text red accent-3 bold-500')
}
})
$('#fp-form').submit(function(e){
e.preventDefault()
if($('#mobile-fp').val().trim().length>0){
t=$(this)
$('.fp-btn').prop('disabled',true)
$.ajax({
type:'post',
data:t.serialize(),
url:URL+'home/get_phone_verification_fp',
dataType:'json',
success:function(res){
$('.fp-btn').prop('disabled',false)
if(res.err){
Materialize.toast(res.err, 4000,'white-text red accent-3 bold-500')
}
if(res.success){
$('#fp-form').hide()
$('#fp-form-verify').show()
}
}
})
} else {
Materialize.toast('Please Enter Your 10 Digit Mobile Number', 4000,'white-text red accent-3 bold-500')
}
})
$('#reg-form').submit(function(e){
e.preventDefault()
if($('#first_name').val().trim().length>0){
if($('#last_name').val().trim().length>0){
if($('#email').val().trim().length>0){
if($('#mobile').val().trim().length>0){
if($('#password-r').val().trim().length>0){
t = $(this)
$('.submit-btn').prop('disabled',true)
$('.submit-btn').html('<i class="fa fa-spinner fa-pulse"></i> Wait...')
$.ajax({
type:'post',
url:URL+'register',
data:t.serialize(),
dataType:'json',
success:function(r){
$('.submit-btn').prop('disabled',false)
$('.submit-btn').html('Submit & Verify')
if(r.success){
t[0].reset()
window.location.href = URL+'home/register_success'
} else {
$('.form-success').html('')
}
if(r.fn){
Materialize.toast(r.fn, 4000,'white-text red accent-3 bold-500')
}
aft = 150
if(r.ln){
setTimeout(function(){
Materialize.toast(r.ln, 4000,'white-text red accent-3 bold-500')
},aft)
aft = aft + 150
}
if(r.em){
setTimeout(function(){
Materialize.toast(r.em, 4000,'white-text red accent-3 bold-500')
},aft)
aft = aft + 150
}
if(r.mo){
setTimeout(function(){
Materialize.toast(r.mo, 4000,'white-text red accent-3 bold-500')
},aft)
aft = aft + 150
}
if(r.pw){
setTimeout(function(){
Materialize.toast(r.pw, 4000,'white-text red accent-3 bold-500')
},aft)
aft = aft + 150
}
if(r.gn){
setTimeout(function(){
Materialize.toast(r.gn, 4000,'white-text red accent-3 bold-500')
},aft)
}
}
})
} else {
Materialize.toast('Please Enter Your Password', 4000,'white-text red accent-3 bold-500')
}
} else {
Materialize.toast('Please Enter Your 10 Digit Mobile Number', 4000,'white-text red accent-3 bold-500')
}
} else {
Materialize.toast('Please Enter Email Address', 4000,'white-text red accent-3 bold-500')
}
} else {
Materialize.toast('Please Enter Lastname', 4000,'white-text red accent-3 bold-500')
}
} else {
Materialize.toast('Please Enter Firstname', 4000,'white-text red accent-3 bold-500')
}
})
Materialize.scrollFire(options);
$('.reg-link').click(function(e){
e.preventDefault()
$('#login-form').hide()
$('#reg-form').show()
})
$('.login-link').click(function(e){
e.preventDefault()
$('#reg-form').hide()
$('#login-form').show()
$('#fp-form').hide()
})
$('.fp-link').click(function(e){
e.preventDefault()
$('#login-form').hide()
$('#fp-form').show()
})
if($(window).width()<601){
$('.parallax-container').css('height','1250px')
} else {
$('.parallax-container').css('height',$(window).height()+50)
}
$('.parallax').parallax();
$(window).resize(function() {
if($(window).width()<601){
$('.parallax-container').css('height','1250px')
} else {
$('.parallax-container').css('height',$(window).height()+50)
}
})
})
function get_phone_verification (u,t) {
	t.prop('disabled',true)
	$.ajax({
		type:'post',
		url:URL+'home/get_phone_verification',
		data:'user_token='+$('input[name="user_token"]').val()+'&phone='+u,
		error:function(err){
			console.log(err)
		},
		success:function(res){
			window.location.href = URL+'home/verify_number'
		}
	})
}