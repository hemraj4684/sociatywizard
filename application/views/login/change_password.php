<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/materialize.min.css'); ?>">
        <?=style_css()?>
        <style>
        body{
            background-image:url('<?=base_url('assets/images/home-bg.png')?>');
            background-size:cover;
            background-repeat:no-repeat;
        }
        </style>
    </head>
    <body>
        <div class="row" style="padding:10px;"></div>
        <div class="row mb10">
        	<div class="col s12 m6 offset-m3">
                <img src="<?=base_url('assets/images/society-wizard-logo-main.png')?>" class="responsive-img">
	        	<div class="card-panel before-success-submit mt15 center">
	        		<h5><b>Reset Password</b></h5>
	        		<?=form_open('','class="row" id="np_form"')?>
                    <div class="input-field col s12 m8 offset-m2">
                        <label for="password">Enter New Password</label>
                        <input type="password" id="password" name="password" required>
                        <p class="err-under"><?=form_error('password')?></p>
                    </div>
                    <div class="input-field col s12 m8 offset-m2">
                        <label for="r-password">Repeat New Password</label>
                        <input type="password" id="r-password" name="r-password" required>
                        <p class="err-under"><?=form_error('r-password')?></p>
                    </div>
                    <div class="input-field col s12 m8 offset-m2">
                        <button class="btn accent-3 submit-btn col s12 indigo">Submit</button>
                    </div>
                    <?=form_close()?>
	        	</div>
        	</div>
        </div>
        <script>var URL = "<?php echo base_url(); ?>"</script>
        <script type="text/javascript" src="<?=base_url('assets/js/jquery-1.11.3.min.js')?>"></script>
        <script type="text/javascript" src="<?=base_url('assets/js/materialize.min.js')?>"></script>
    </body>
</html>