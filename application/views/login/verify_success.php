<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/materialize.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/animate.min.css'); ?>">
        <?=style_css()?>
    </head>
    <body  style="background:url('<?php echo base_url('assets/images/bg.jpg'); ?>') top center no-repeat #c00d10;">
        <div class="row" style="padding:10px;"></div>
        <div class="row">
        	<div class="col s12 m6 offset-m3">
		        	<div class="card-panel mt15 animated tada center">
		        		<h5><b>Congratulations!</b></h5>
		        		<p>You have successfully verified your email account!</p>
		        		<p><a href="<?=base_url()?>" class="bold-700 indigo-text">Click here</a> to login now.</p>
		        	</div>
        	</div>
        </div>
    </body>
</html>