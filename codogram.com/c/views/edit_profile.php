<?php
if(!empty($this->ll)){
$ll = explode(',',$this->ll);
echo '<script>var LO='.$ll[1].',LT='.$ll[0].'</script>';
}else{
echo '<script>var LT=10,LO=10</script>';
}
$skex = [];
if(!empty($this->sk)){
$skex = explode(',', $this->sk);
}
?>
<div class="container">
<div class="row">
<div class="col s12 center"><h6 class="top-head-margin"><u><b>Edit Your Profile</b></u></h6></div>
<div class="col m6 s12">
<div class="row"><div class="col s12">
<form autocomplete="off" method="post" class="col s12 form-border" id="e1">
<div class="input-field col s12">
<input id="fn" name="fn" type="text" class="validate" value="<?=$this->fn?>" maxlength="30" required>
<label for="fn">Firstname *</label>
</div>
<div class="input-field col s12">
<input id="ln" name="ln" type="text" class="validate" value="<?=$this->ln?>" maxlength="30" required>
<label for="ln">Lastname</label>
</div>
<div class="input-field col s12">
<button type="submit" id="eb1" class="btn waves-effect waves-light red accent-3" disabled>Update</button>
</div><input type="hidden" value="<?=$token?>" name="token">
<div class="input-field col s12"><div class="e1e edit-err z-depth-1 center-align red-text text-accent-4 indigo lighten-5"></div></div>
</form>
</div>
<div class="col s12">
<form autocomplete="off" class="col s12 form-border" method="post" id="e9"><h6><b><u>Add Your Skills</u></b></h6>
<div class="input-field col s12">
<select id="your-skills" name="skills[]" class="browser-default skill-select" multiple><?php
foreach($subj as $key => $val){
echo '<option value="'.$val['id'].'" ';
if(in_array($val['id'], $skex)){
echo 'selected';
}
echo '>'.$val['name'].'</option>';
}unset($val);
?></select>
<p class="margin-b0 grey-text text-darken-2 grey lighten-4 center">Select maximum 15 skills</p>
</div>
<div class="input-field col s12"><button type="submit" id="eb9" class="btn waves-effect waves-light red accent-3">Update</button> <button class="btn waves-effect waves-light indigo lighten-1" type="reset">Cancel</button>
<input type="hidden" value="<?=$token?>" name="skill-token">
</div>
<div class="input-field col s12"><div class="e9e edit-err z-depth-1 center-align red-text text-accent-4 indigo lighten-5"></div></div>
</form>
</div>
<div class="col s12">
<form autocomplete="off" method="post" class="col s12 form-border" enctype="multipart/form-data" id="e4"><h6><b><u>Profile picture</u></b></h6>
<div class="file-field input-field col s12">
<input placeholder="Select picture" class="file-path validate" type="text">
<div class="btn red accent-3">
<span>File</span>
<input type="hidden" name="MAX_FILE_SIZE" value="2097152">
<input type="file" name="photo">
</div>
</div>
<input type="hidden" value="<?=$token?>" name="token">
<div class="input-field col s12"><div class="e4e edit-err z-depth-1 center-align red-text text-accent-4 indigo lighten-5"></div></div>
<div id="wait_load"></div>
<div id="pic_img"><img class="responsive-img" src="<?=$this->dp?>"></div>
</form>
</div>
<div class="col s12">
<form autocomplete="off" method="post" class="col s12 form-border" enctype="multipart/form-data" id="e6"><h6><b><u>Banner</u></b></h6>
<div class="file-field input-field col s12">
<input placeholder="Select picture" class="file-path validate" type="text">
<div class="btn red accent-3">
<span>File</span>
<input type="hidden" name="MAX_FILE_SIZE" value="2097152">
<input type="file" name="banner">
</div>
</div>
<input type="hidden" value="<?=$token?>" name="token">
<div class="input-field col s12"><div class="e6e edit-err z-depth-1 center-align red-text text-accent-4 indigo lighten-5"></div></div>
<div id="wait_load2"></div>
<div id="banner_img"><img class="responsive-img" src="<?=$this->bn?>"></div>
</form>
</div></div></div>
<div class="col m6 s12">
<div class="row"><div class="col s12">
<form autocomplete="off" class="col s12 form-border" method="post" id="e2">
<div class="input-field col s12">
<textarea id="about" name="about" class="materialize-textarea" maxlength="250"><?=$this->uabt?></textarea>
<label for="about">About You</label>
</div>
<div class="input-field col s12">
<button type="submit" id="eb2" class="btn waves-effect waves-light red accent-3" disabled>Update</button>
</div><input type="hidden" value="<?=$token?>" name="token">
<div class="input-field col s12"><div class="e2e edit-err z-depth-1 center-align red-text text-accent-4 indigo lighten-5"></div></div>
</form>
</div>
<div class="col s12">
<div class="col s12 form-border"><h6><b><u>Manage Your Location</u></b></h6>
<input id="lat" type="hidden" name="lat"><input id="long" type="hidden" name="long"><input type="hidden" value="<?=$token?>" name="token" id="map-token">
<input id="pac-input" class="controls" type="text" placeholder="Search Box">
<div id="map"></div>
<div class="input-field col s12"><div class="e7e edit-err z-depth-1 center-align red-text text-accent-4 indigo lighten-5"></div></div>
<div class="margin-b10">
<button type="button" class="btn red accent-3" id="save-map"><i class="mdi-action-room right"></i> Save Location</button>
<button type="button" class="btn indigo lighten-1" id="get-geo"><i class="mdi-maps-my-location right"></i> Current Location</button>
</div>
</div>
</div>
<div class="col s12">
<form autocomplete="off" class="col s12 form-border" method="post" id="e3"><h6><b><u>Your social links</u></b></h6>
<div class="input-field col s12">
<input id="wb" name="wb" type="text" class="validate" value="<?=$this->myurl?>" maxlength="100">
<label for="wb">Website</label>
</div>
<div class="input-field col s12">
<input id="fb" name="fb" type="text" class="validate" value="<?=$this->fb?>" maxlength="50">
<label for="fb">Your facebook username</label>
</div>
<div class="input-field col s12">
<input id="tw" name="tw" type="text" class="validate" value="<?=$this->tw?>" maxlength="15">
<label for="tw">Your twitter username</label>
</div>
<div class="input-field col s12">
<button type="submit" id="eb3" class="btn waves-effect waves-light red accent-3" disabled>Update</button>
</div><input type="hidden" value="<?=$token?>" name="token">
<div class="input-field col s12"><div class="e3e edit-err z-depth-1 center-align red-text text-accent-4 indigo lighten-5"></div></div>
</form>
</div>
<div class="col s12">
<form class="col s12 form-border" method="post" id="e5">
<div class="input-field col s12">
<input id="username" name="username" value="<?=$this->un?>" type="text" class="validate" maxlength="30" autocomplete="off" required>
<label for="username">Create your username</label>
</div>
<div class="input-field col s12">
<button type="submit" id="eb5" class="btn waves-effect waves-light red accent-3" disabled>Submit</button>
</div><input type="hidden" value="<?=$token?>" name="token">
<div class="input-field col s12"><div class="e5e edit-err z-depth-1 center-align red-text text-accent-4 indigo lighten-5"></div></div>
</form>
</div>
<div class="col s12">
<form class="col s12 form-border" method="post" id="e8" autocomplete="off"><h6><b><u>Change your password</u></b></h6>
<div class="input-field col s12">
<input type="password" name="password" id="password" class="validate">
<label for="password">Enter Old Password</label>
</div>
<div class="input-field col s12">
<input type="password" name="password_n" id="password_n" class="validate">
<label for="password_n">Enter New Password</label>
</div>
<div class="input-field col s12">
<input type="password" name="password_r" id="password_r" class="validate">
<label for="password_r">Repeat New Password</label>
</div>
<div class="input-field col s12">
<button type="submit" id="eb8" class="btn waves-effect waves-light red accent-3">Submit</button>
</div><input type="hidden" value="<?=$token?>" name="token">
<div class="input-field col s12"><div class="e8e edit-err z-depth-1 center-align red-text text-accent-4 indigo lighten-5"></div></div>
</form>
</div>
</div></div>
</div>
</div>