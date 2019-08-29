<div class="row mt10">
	<div class="col s12 m8 l8">
		<div class="col s12">
		<div class="card">
			<div class="card-content pad0">
				<?=form_open('','class="row" id="update_user_basic"')?>
					<div class="input-field red accent-2 white-text col mt0 mb10 s12"><span class="card-title">Basic Information</span><button class="btn right indigo accent-3 btn-small submit-btn mt10"><i class="fa fa-save"></i> Save Changes</button></div>
					<div class="input-field col s12 m6"><input type="text" name="fn" id="fn" value="<?=h($this->fn)?>"><label for="fn">Firstname</label><p class="err-under fn-err"></p></div>
					<div class="input-field col s12 m6"><input type="text" name="ln" id="ln" value="<?=h($this->ln)?>"><label for="ln">Lastname</label><p class="err-under fn-err"></p></div>
					<div class="input-field col s12 m6"><input type="text" name="dob" class="datepicker" id="dob" value="<?=(!empty($data->date_of_birth)) ? date('d-m-Y',strtotime($data->date_of_birth)) : ''?>"><label for="dob">Date Of Birth</label><p class="err-under dob-err"></p></div>
					<div class="input-field col s12 m6"><span class="custom-form-label">Gender :</span><input type="radio" name="gender" value="1" id="male"<?php if($data->gender==='1'){echo ' checked';} ?>><label for="male">Male</label> &nbsp;&nbsp;&nbsp; <input type="radio" name="gender" value="2" id="female"<?php if($data->gender==='2'){echo ' checked';} ?>><label for="female">Female</label><p class="err-under gn-err"></p></div>
					<div class="input-field col s12 mb0"><h6 class="font-16"><b>Mobile Number Privacy</b></h6></div>
					<div class="clearfix row"><div class="mt0 h50 input-field col s12"><div class="switch"><label>Private<input name="mo_privacy" type="checkbox"<?=($data->phone_privacy==='2' ? ' checked' : '')?>><span class="lever"></span>Public</label></div></div></div>
					<div class="input-field col s12"><p><b>Mobile Number :</b> <?=$data->mobile_no?></p></div>
					<div class="input-field col s12 mb15"><p><b>Email ID :</b> <?=($data->email) ? h($data->email) : 'N/A'?></p></div>
				<?=form_close()?>
			</div>
		</div>
		</div>
		<div class="col s12 l9">
		<div class="card">
			<div class="card-content pad0">
			<?=form_open('','class="row" id="update_user_password"')?>
				<div class="input-field teal lighten-1 white-text col mt0 mb10 s12"><span class="card-title">Change Your Password</span><button class="btn right indigo accent-3 btn-small submit-btn-pw mt10 mb10"><i class="fa fa-save"></i> Submit</button></div>
				<div class="input-field col s12"><input type="password" name="old_pw" id="old_pw"><label for="old_pw">Enter Your Old Password</label><p class="err-under oldpw-err"></p></div>
				<div class="input-field col s12"><input type="password" name="new_pw" id="new_pw"><label for="new_pw">Enter New Password</label><p class="err-under newpw-err"></p></div>
				<div class="input-field col s12"><input type="password" name="c_new_pw" id="c_new_pw"><label for="c_new_pw">Confirm New Password</label><p class="err-under cnewpw-err"></p></div>
			<?=form_close()?>
			</div>
		</div>
		</div>
	</div>
	<div class="col s12 m3 l3">
		<div class="card">
			<div class="card-content">
				<img src="<?=$this->pic?>" class="z-depth-1 profile_display materialboxed responsive-img">
				<?=form_open_multipart('','id="change_dp_form"')?>
			        <label for="u_dp" class="btn btn-small indigo accent-3">Change Image</label>
			        <input type="file" name="userfile" id="u_dp" class="hide" accept=".jpg,.png,.jpeg">
				<?=form_close()?>
			</div>
		</div>
		<?php if($this->usertype==='2'): ?>
		<div class="card-panel lime lighten-4">NOTE : To change your other information like Mobile Number, Email Id etc. contact your society admin through helpdesk.</div>
	<?php endif; ?>
	</div>
</div>