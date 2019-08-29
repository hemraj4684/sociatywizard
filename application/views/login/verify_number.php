<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/materialize.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/animate.min.css'); ?>">
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
        <div class="row">
            <div class="col s12 m6 offset-m3">
                <a class="block" href="<?=base_url()?>"><img src="<?=base_url('assets/images/society-wizard-logo-main.png')?>" class="responsive-img"></a>
                    <div class="card-panel mt15 center">
                        <?=form_open('','id="verify_usercode" class="row"')?>
                        <div class="col s12 input-field"><input type="text" name="code" maxlength="6" id="code"><label for="code">Enter 6 Digit Verification Code</label></div>
                        <div class="col s12 input-field"><button class="indigo accent-3 submit-btn btn col s12">Verify</button></div>
                        <?=form_close()?>
                    </div>
            </div>
        </div>
        <script>var URL = "<?php echo base_url(); ?>"</script>
        <script src="<?=base_url('assets/js/jquery-1.11.3.min.js')?>"></script>
        <script src="<?=base_url('assets/js/materialize.min.js')?>"></script>
        <?=script_js()?>
        <script src="<?=base_url('assets/js/user_init_page.js')?>"></script>
    </body>
</html>