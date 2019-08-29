<div class="container">
<div class="row">
<form id="register" action="" method="post">
<div class="col m6 s12 offset-m3 user-register white z-depth-1">
<h5 class="center"><b>REGISTER</b></h5>
<div class="row">
<div class="input-field col s12">
<input id="firstname" name="firstname" type="text" class="validate" maxlength="30" required>
<label for="firstname">Firstname</label>
</div>
<div class="input-field col s12">
<input id="lastname" name="lastname" type="text" class="validate" maxlength="30" required>
<label for="lastname">Lastname</label>
</div>
<div class="input-field col s12">
<input id="email" name="email" type="email" class="validate" maxlength="100" required>
<label for="email">Email</label>
</div>
<div class="input-field col s12">
<input id="password" name="password" type="password" class="validate" required>
<label for="password">Password</label>
</div>
<div class="input-field col s12">
<select name="country" id="country"><option value="">Select Country</option>
<?php
foreach($countries as $key => $val){
	echo '<option value="'.$val['id'].'">'.$val['name'].'</option>';
}
unset($val);
?>
</select>
</div>
<div class="center col s12"><div class="red-text text-accent-4 pad-sm hideme indigo lighten-5 err reg-err"></div></div>
<div class="input-field col s12">
<div class="margin-b10 grey-text"><i class="mdi-action-done"></i> by registering you agree all the terms and conditions</div>
<button type="submit" class="btn RB waves-effect waves-light col s12 red accent-3">Register <i class="tiny mdi-navigation-arrow-forward right"></i></button>
</div>
<input type="hidden" name="token" value="<?=$this->unique_token('R')?>">
</div>
</div>
</form>
</div>
</div>