<div class="container"><div class="row">
<form id="forgot-password" action="" method="post">
<div class="col s12 m6 offset-m3 fp-box white z-depth-1">
<h5 class="center"><b>Forgot Password</b></h5>
<div class="row">
<div class="input-field col s12">
<input id="email" name="email" type="email" class="validate" maxlength="100" required>
<label for="email">Enter Your Email</label>
</div>
<div class="input-field col s12">
<div class="f-err red-text text-accent-4 indigo lighten-5 pad-sm center"></div>
<b><div class="f-success center"></div></b>
</div>
<div class="input-field col s12">
<button type="submit" class="btn waves-effect waves-light col s12 red accent-3" id="fp-btn">Continue <i class="tiny mdi-navigation-arrow-forward right"></i></button>
</div>
<input type="hidden" name="token" value="<?=$this->unique_token('FP')?>">
</div>
</div>
</form>
</div>
</div>