<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/materialize.min.css'); ?>">
        <?=style_css()?>
    </head>
    <body  style="background:url('<?php echo base_url('assets/images/bg.jpg'); ?>') top center no-repeat #c00d10;">
        <div class="row" style="padding:10px;"></div>
        <div class="row">
        	<div class="col s12 m6 offset-m3">
		        	<div class="card-panel before-success-submit mt15 center">
		        		<h5><b>Forgot Password?</b></h5>
		        		<?=form_open('','class="row" id="fp_form"')?>
                        <div class="input-field col s12 m8 offset-m2">
                            <label for="email">Enter Email ID</label>
                            <input type="email" id="email" name="email">
                        </div>
                        <div class="input-field col s12 m8 offset-m2">
                            <button class="btn submit-btn col s12 indigo">Submit</button>
                        </div>
                        <?=form_close()?>
		        	</div>
                    <div class="card-panel after-success-submit mt15" style="display:none">
                        <h5>Check your email!</h5>
                        <p>We've sent an email to you. Click the link in the email to reset your password.</p>
                        <p>If you don't see the email, check other places it might be, like your junk, spam, social, or other folders.</p>
                        <p class="pointer-cursor re-pw indigo-text">I didn't receive the email</p>
                    </div>
        	</div>
        </div>
        <script>var URL = "<?php echo base_url(); ?>"</script>
        <script type="text/javascript" src="<?=base_url('assets/js/jquery-1.11.3.min.js')?>"></script>
        <script type="text/javascript" src="<?=base_url('assets/js/materialize.min.js')?>"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.re-pw').click(function(){
                    $('.before-success-submit').show()
                    $('.after-success-submit').hide()
                })
                $('#fp_form').submit(function(e){
                    e.preventDefault()
                    $('.submit-btn').prop('disabled',true)
                    t=$(this)
                    $.ajax({
                        type:'post',
                        url:URL+'home/start_verify_email_fp',
                        data:t.serialize(),
                        dataType:'json',
                        complete:function(){
                            $('.submit-btn').prop('disabled',false)
                        },
                        success:function(res){
                            console.log(res)
                            if(res.success){
                                $('.before-success-submit').hide()
                                $('.after-success-submit').show()
                            }
                            if(res.err){
                                Materialize.toast(res.err, 4000,'white black-text bold-500')
                            }
                        }
                    })
                })
            })
        </script>
    </body>
</html>