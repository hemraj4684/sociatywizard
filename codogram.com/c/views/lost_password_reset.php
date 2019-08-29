<div class="container"><div class="row">
<?php if($valid): ?>

<form id="new-password" action="" method="post">
<div class="col s12 m6 offset-m3 login-box z-depth-1">
<h5 class="center"><b>Reset Password</b></h5>
<div class="row">
<div class="input-field col s12">
<input id="new-p" name="new-p" type="password" class="validate" required>
<label for="new-p">New Password</label>
</div>
<div class="input-field col s12">
<input id="new-cp" name="new-cp" type="password" class="validate" required>
<label for="new-cp">Confirm Password</label>
</div>
<div class="input-field col s12"><div class="err"><?=$err?></div></div>
<div class="input-field col s12">
<button type="submit" class="btn waves-effect waves-light col s12 red accent-3">Continue <i class="tiny mdi-navigation-arrow-forward right"></i></button>
<input type="hidden" name="token" value="<?=$this->unique_token('FP')?>">
</div>
</div>
</div>
</form>

<?php else: ?>
<h3 class="fp-err-h center big-bot-margin">The token you have entered is invalid or has been expired.</h3>
<?php endif; ?>
</div></div>