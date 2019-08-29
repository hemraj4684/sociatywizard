<form id="login" action="" method="post">
<div class="col s12 m6 offset-m3 login-box z-depth-1">
<h5 class="center"><b><?=$login_head?></b></h5>
<div class="row">
<div class="input-field col s12">
<input id="email" name="email" type="email" class="validate" maxlength="100" required autofocus>
<label for="email">Email</label>
</div>
<div class="input-field col s12">
<input id="password" name="password" type="password" class="validate" required>
<label for="password">Password</label>
</div>
<?=$err?>
<div class="input-field col s12">
<a class="btn white z-depth-0 grey lighten-4 indigo-text text-accent-3 margin-b10 fp-btn right" href="/somebody/forgot-password">Forgot Password <i class="right mdi-action-help"></i></a>
<a class="btn white z-depth-0 grey lighten-4 indigo-text text-accent-3 right" href="<?=SITE?>registration">Register Here <i class="right mdi-action-account-box"></i></a>
</div>
<div class="input-field col s12">
<button type="submit" class="btn waves-effect waves-light col s12 red accent-3">Login <i class="tiny mdi-navigation-arrow-forward right"></i></button>
</div>
<input type="hidden" name="token" value="<?=$this->unique_token('L')?>">
</div>
</div>
</form>