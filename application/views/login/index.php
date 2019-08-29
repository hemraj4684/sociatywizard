<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?=base_url('assets/images/society-wizard-favicon.png')?>">
<title>A Housing Society Management Platform</title>
<meta name="description" content="Society Wizard provides a platform to manage your apartment / housing society online.">
<link rel="stylesheet" href="<?php echo base_url('assets/css/materialize.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/animate.min.css'); ?>">
<?=style_css()?>
<link rel="canonical" href="http://www.societywizard.com/">
<meta property="og:site_name" content="Society Wizard">
<meta name="application-name" content="Society Wizard">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<style>
.form-card{margin-top:5%;}
.mb0{margin-bottom:0;}
.bg-users{overflow:hidden;text-align:center;margin-top:50px;position:absolute;top:0;left:-25px;right:10px;bottom:0;z-index:-100;}
.bg-users-img{font-size:0;border:1px solid #cccccc;
width:150px;
height:150px;
display:inline-block;
/*float:left;*/
background-position:center;
background-size:cover;
}
header{border:0;box-shadow:none;}
.l-h3{text-shadow:0 1px 3px #333;margin-top:8%;font-family:'Raleway', sans-serif;color:#000000;}
.raleway{font-family:'Raleway', sans-serif;}
body{background-color: #ffffff;}
.features-section i{font-size:50px;line-height:50px;}
.features-section h4{margin-bottom:0;}
.features-section .col .card-panel{visibility:hidden;}
@media(max-width:992px){.l-h3 h3{font-size:1.92rem;}}
@media(max-width:992px) AND (min-width:601px){.features-section .col:nth-child(3n+1){clear:both;}}
@media(max-width:600px){.l-h3 h3{font-size:1.92rem;}.features-section .col:nth-child(odd){clear:both;}}
@media(min-width:993px){.features-section .col:nth-child(5){clear:both;}}
#login-form,#reg-form{background-color:rgba(255,255,255,0.8);}
.input-field label{color:#222222}
#reg-form,#fp-form,#fp-form-verify{display:none;}
footer.page-footer{margin-top:0;padding-top:15px;padding-bottom:15px;}
.pad15{padding:15px}.bot-img-list{max-height:450px}
.fa.social-btn{vertical-align:middle;border-radius:100%;height:35px;width:35px;background-color:#ffffff;border:1px solid #dddddd;line-height:35px;color:#ffffff;}
.fa-facebook.fa{background-color:#3b5998;}
.fa-linkedin.fa{background-color:#0077b5;}
section.col.last-section{padding-top:20px;padding-bottom:35px;}
.s-wizard-link-social{display:inline-block;}
.rys-btn,.rn-btn{padding:10px;cursor:pointer;}
.rn-btn{width:150px;margin-left:auto;margin-right:auto;}
</style>
</head>
<body>
<div class="row mb0">
<div class="parallax-container">
<div class="parallax"><img src="<?=base_url('assets/images/home-bg.png')?>"></div>
<div class="col s12 m6 l4 offset-l1 offset-m3"><img src="<?=base_url('assets/images/society-wizard-logo-main.png')?>" class="mt15 responsive-img"></div>
<div class="col l6 m6 s12 offset-l1 center l-h3 clearfix">
<h3 class="bold-700 grey-text text-darken-4" data-in-effect="bounceInRight">Management platform for commercial complexes & housing societies</h3>
<div class="col s12 m8 offset-m2 l4 offset-l4"><a href="https://play.google.com/store/apps/details?id=io.societywizard&hl=en" target="_blank" class="block"><img src="<?=base_url('assets/images/google-play-icon.png')?>" class="responsive-img animated rubberBand"></a></div>
<div class="col s12"><img class="responsive-img" style="margin-top:5px;" src="<?=base_url('assets/images/ccavenue.png')?>"></div>
</div>
<div class="col l4 m6 s12">
<?php echo form_open('','id="login-form" class="animated swing form-card card col s12"'); ?>
<div class="card-content">
<?=$this->session->flashdata('login_flash')?>
<div class="center card-title"><b>Sign In</b></div>
<div class="row mb0">
<div class="input-field col s12">
<input id="username" name="username" type="text" value="<?=set_value('username')?>"  autocomplete="off" required>
<label for="username">Email address or phone number</label>
</div>
<div class="input-field col s12">
<input id="password" name="password" type="password" required>
<label for="password">Password</label>
</div>
<div class="input-field col s12">
<button class="btn col indigo accent-3 s12">sign in</button>
</div>
<div class="input-field col s12 red-text text-accent-4 center mb0">
<b><?=$err?></b>
</div>
<hr class="mb0">
<div class="input-field col s12">
<p class="center black-text bold-500">Dont Have An Account? <a class="indigo-text text-accent-3 reg-link" href="#">Register</a></p>
</div>
<div class="input-field col s12">
<p class="center"><a class="indigo-text text-accent-3 bold-500 fp-link" href="#">Forgot password?</a></p>
<div class="card-panel rys-btn modal-trigger mt10 green white-text center" data-target="register-society-modal">Register Your Society</div>
</div>
</div>
</div>
<?php echo form_close(); ?>
<?php echo form_open('','id="reg-form" class="animated bounceIn card col s12 form-card" autocomplete="off"'); ?>
<div class="row reg-row-1">
<div class="input-field col m12 s12">
<h4 class="card-title center"><b>Register</b></h4>
</div>
<div class="input-field col l6 m6 s12">
<input id="first_name" name="first_name" type="text" maxlength="50">
<label for="first_name">First Name</label>
</div>
<div class="input-field col l6 m6 s12">
<input id="last_name" name="last_name" type="text" maxlength="50">
<label for="last_name">Last Name</label>
</div>
<div class="input-field col l6 m6 s12">
<input id="email" name="email" type="email" maxlength="100">
<label for="email">Email ID</label>
</div>
<div class="input-field col l6 m6 s12">
<input id="mobile" name="mobile" type="text" maxlength="10">
<label for="mobile">Mobile Number</label>
</div>
<div class="input-field col l6 m6 s12">
<input id="password-r" name="password" type="password">
<label for="password-r">Password</label>
</div>
<div class="input-field col l6 m6 s12">
<input id="male" name="gender" type="radio" value="1" checked>
<label for="male">Male</label>
<input id="female" name="gender" type="radio" value="2">
<label for="female">Female</label>
</div>
<div class="input-field col m12 text-center s12"><button type="submit" class="btn col s12 indigo accent-3 submit-btn">Submit & Verify</button></div>
<div class="input-field col m12 s12"><p class="bold-500 red white-text accent-3 center font-12"><i class="fa fa-warning"></i> Please ensure you have your mobile phone with you for verification</p><p class="grey-text text-darken-4 font-12 text-center">By signing up, you agree to the Terms of Service and Privacy Policy, including Cookie Use</p></div>
<div class="col s12"><div class="form-success"></div>
<div class="form-errors"></div>
<p class="center black-text bold-500">Already Have An Account? <a class="indigo-text text-accent-3 login-link" href="#">Login</a></p>
</div>
</div>
<?php echo form_close(); ?>
<?php echo form_open('','id="fp-form" class="animated fadeIn card col s12 form-card" autocomplete="off"'); ?>
<div class="card-content">
<div class="center card-title"><b>Forgot Password</b></div>
<div class="row">
<div class="input-field col s12">
<input id="mobile-fp" name="mobile" type="text" maxlength="10">
<label for="mobile-fp">Enter Your Mobile Number</label>
</div>
<div class="input-field col m12 text-center s12"><button type="submit" class="btn col s12 indigo accent-3 fp-btn">Submit</button></div>
<div class="col s12 center input-field"><a class="login-link btn btn-small white-text green" href="#">Login Here</a></div>
</div>
</div>
<?php echo form_close(); ?>
<?php echo form_open('','id="fp-form-verify" class="animated fadeIn card col s12 form-card" autocomplete="off"'); ?>
<div class="card-content">
<div class="center card-title"><b>Enter Verification Code</b></div>
<div class="row">
<div class="input-field col s12">
<div class="col s12 input-field"><input type="text" name="code" maxlength="6" id="code"><label for="code">Enter 6 Digit Verification Code</label></div>
<div class="col s12 input-field"><button class="indigo accent-3 btn col s12">Verify</button></div>
</div>
<div class="col s12 center input-field"><a class="login-link btn btn-small white-text green" href="#">Login Here</a></div>
</div>
</div>
<?php echo form_close(); ?>
</div>
</div>
<section class="mt10 features-section white-text center">
<div class="col s6 m4 l3">
<div class="card-panel animated indigo accent-3 box-1">
<i class="fa fa-users"></i>
<h4 class="font-16 bold-500">Society Members Management</h4>
</div>
</div>
<div class="col s6 m4 l3">
<div class="card-panel animated green box-2">
<i class="mdi-hardware-headset-mic"></i>
<h4 class="font-16 bold-500">Society Helpdesk</h4>
</div>
</div>
<div class="col s6 m4 l3">
<div class="card-panel animated amber box-3">
<i class="fa fa-building"></i>
<h4 class="font-16 bold-500">Flats Management</h4>
</div>
</div>
<div class="col s6 m4 l3">
<div class="card-panel animated red accent-3 box-4">
<i class="fa fa-file-text-o"></i>
<h4 class="font-16 bold-500">Society Maintenance Bill</h4>
</div>
</div>
<div class="col s6 m4 l3">
<div class="card-panel animated purple box-5">
<i class="fa fa-files-o"></i>
<h4 class="font-16 bold-500">Documents Management</h4>
</div>
</div>
<div class="col s6 m4 l3">
<div class="card-panel animated blue box-6">
<i class="fa fa-sticky-note-o"></i>
<h4 class="font-16 bold-500">Online Notice Board</h4>
</div>
</div>
<div class="col s6 m4 l3">
<div class="card-panel animated lime darken-2 box-7">
<i class="mdi-image-photo-library"></i>
<h4 class="font-16 bold-500">Photo Gallery</h4>
</div>
</div>
<div class="col s6 m4 l3">
<div class="card-panel animated purple box-8">
<i class="fa fa-male"></i>
<h4 class="font-16 bold-500">Vendors Directory</h4>
</div>
</div>
</section>
<section class="col s12 app-features pb15">
<h3 class="center white-text font-20 bold-500 raleway">Mobile App Highlights</h3>
<div class="col s12 m6 l3"><img src="<?=base_url('assets/images/sw-bill-history.png')?>" class="center-block mb10 z-depth-2 responsive-img bot-img-list"></div>
<div class="col s12 m6 l3"><img src="<?=base_url('assets/images/sw-billdetails.png')?>" class="center-block mb10 z-depth-2 responsive-img bot-img-list"></div>
<div class="col s12 m6 l3"><img src="<?=base_url('assets/images/sw-notice-board.png')?>" class="center-block mb10 z-depth-2 responsive-img bot-img-list"></div>
<div class="col s12 m6 l3"><img src="<?=base_url('assets/images/sw-gallery.jpg')?>" class="center-block mb10 z-depth-2 responsive-img bot-img-list"></div>
</section>
<section class="col s12 last-section">
<div class="col s12 m6"><h3 class="font-20 center bold-500">Don't have your society / apartment registered on Society Wizard ?</h3><div data-target="register-society-modal" class="card-panel modal-trigger center-block block center white-text rn-btn mb15 red accent-3">Request Now!</div><p class="center bold-500 font-16">or</p><p class="center">Call Us : <a class="bold-500 grey-text text-darken-4" href="tel:+919833215446">+91-9833215446</a></p><p class="center bold-500 font-16">or</p><p class="center">Email Us : <a class="bold-500 grey-text text-darken-4" target="_blank" href="mailto:info@societywizard.com">info@societywizard.com</a></p></div>
<div class="col s12 center m6"><h3 class="font-20 bold-500">Follow us on social networks!</h3>
<a class="s-wizard-link-social" href="https://www.facebook.com/societywizard" target="_blank"><i class="hoverable fa social-icon-top fa-facebook social-btn"></i></a> &nbsp;
<a class="s-wizard-link-social" href="https://www.linkedin.com/company/society-wizard" target="_blank"><i class="hoverable fa social-icon-top fa-linkedin social-btn"></i></a>
</div>
</section>
</div>
<div id="register-society-modal" class="modal bottom-sheet">
<?=form_open('','id="new-enquiry"')?>
<div class="modal-content">
<div class="row">
<h3 class="col s12 bold-500 font-20 raleway">Register Your Society</h3>
<div class="input-field col s12 l4"><input name="s_name" id="s_name" type="text"><label for="s_name">Your Society Name</label></div>
<div class="input-field col s12 l4"><input name="cp_name" id="cp_name" type="text"><label for="cp_name">Contact Person Name</label></div>
<div class="input-field col s12 l4"><input name="cp_no" id="cp_no" type="text"><label for="cp_no">Contact Person Mobile Number</label></div>
</div>
</div>
<div class="modal-footer">
<button type="submit" class="waves-effect waves-green enq-btn btn-flat">Submit</button>
</div>
<?=form_close()?>
</div>
<footer class="grey darken-4 page-footer">
<div class="container"><div class="row mb0"><div class="col s12 white-text center"><p class="mb0 mt0">Copyright &copy; 2016 SocietyWizard - All Rights Reserved</p></div>
</div></div>
</footer>
<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
<script>var URL = "<?php echo base_url(); ?>"</script>
<script type="text/javascript" src="<?=base_url('assets/js/jquery-1.11.3.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/materialize.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/index.js')?>"></script>
<!-- <div class="bg-users"><div class="bg-users-img" style="background-image:url(assets/members_picture/04_2016/0bae6bbcb7e0abf6bad8fceb5b2fd313.jpg)"></div></div> -->
</body>
</html>