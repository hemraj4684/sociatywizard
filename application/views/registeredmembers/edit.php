<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m12 l12">
<h5 class="breadcrumbs-title">Edit Members Information</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<?php if($ref && $ref==='admins'){?>
<li><a href="<?php echo base_url('admins'); ?>">Admins</a> / </li>
<?php } else { ?>
<li><a href="<?php echo base_url('registeredmembers'); ?>"><?=$this->p_var?></a> / </li>
<?php } ?>
<li class="active">Edit Members Information</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600"><div class="col s12">
    <?php echo form_open('','id="edit-user-form"'); ?>
    <div class="card"><div class="card-content pad0">
		<div class="row mb0">
		    <div class="col s12">
		    	<div class="form-success white-text"><?php echo $this->session->flashdata('edit_user'); ?></div>
		    	<div class="form-errors">
			        <div class="flat-err"></div>
			        <div class="status"></div>
			        <div class="mem-type"></div>
			        <div class="user_id"></div>
		    	</div>
		    </div>
		</div>
        <div class="row reg-row-1">
        	<div class="clearfix col mb15 s12 indigo accent-1 white-text"><span class="card-title">Basic Information</span><button type="submit" class="btn right indigo accent-3 submit-btn mt10 btn-small"><i class="fa fa-save left"></i> Save Changes</button><span class="right"> &nbsp; </span><a class="right btn mb10 red accent-3 mt10 btn-small" href="<?php echo base_url('registeredmembers'); ?>"><i class="fa fa-close left"></i> Cancel</a></div>
            <div class="input-field col m6 s12">
                <input id="first_name" name="first_name" type="text" value="<?php echo $user->firstname; ?>" maxlength="50">
                <label for="first_name">First Name</label>
                <p class="err-under fn-err"></p>
            </div>
            <div class="input-field col m6 s12">
                <input id="last_name" name="last_name" type="text" value="<?php echo $user->lastname; ?>" maxlength="50">
                <label for="last_name">Last Name</label>
                <p class="err-under ln-err"></p>
            </div>
            <div class="input-field col m6 s12">
                <input id="email" name="email" type="text" value="<?php echo $user->email; ?>">
                <input name="email_old" type="hidden" value="<?php echo $user->email; ?>">
                <label for="email">Email ID</label>
                <p class="em-err err-under"></p>
            </div>
            <div class="input-field col m6 s12">
                <input id="mobile" name="mobile" type="text" value="<?php echo $user->mobile_no; ?>" maxlength="10">
                <input name="mobile_old" type="hidden" value="<?php echo $user->mobile_no; ?>">
                <label for="mobile">Mobile Number</label>
                <p class="err-under mo-err"></p>
            </div>
            <div class="input-field col m4 s12">
                <input type="checkbox" id="is_assoc" name="is_assoc" value="1" class="filled-in"<?php if($user->assoc_member==='1'){ echo ' checked'; } ?>><label for="is_assoc">Is Association Member</label>
            </div>
            <div class="input-field col m4 center s12">
                <input type="text" name="designation" id="desig" maxlength="25" value="<?=h($user->designation)?>"><label for="desig">Designation</label><p class="err-under de_err"></p>
            </div>
            <div class="input-field center col m4 s12">
            <input type="checkbox" id="is_admin" name="is_admin" class="filled-in"<?php if($user->is_admin==='1'){echo ' checked';} ?>><label for="is_admin">Make Admin</label>
            </div>
        </div>
        <input type="hidden" value="<?php echo $id; ?>" name="id">
        </div></div>
        <?php echo form_close(); ?></div>
        <div class="col s12">
        <div class="card"><div class="card-content pad0"><div class="row">
        <?=form_open('','id="flats_info_form"')?>
        <input type="hidden" value="<?php echo $id; ?>" name="id">
        <div class="col mb15 s12 purple white-text accent-1"><span class="card-title">Flats Information</span><button type="submit" class="btn right indigo accent-3 mt10 submit-btn btn-small"><i class="fa fa-save left"></i> Save Changes</button><span class="right"> &nbsp; </span><a class="right btn mb10 red mt10 accent-3 btn-small" href="<?php echo base_url('registeredmembers'); ?>"><i class="fa fa-close left"></i> Cancel</a></div>
        <div class="col s12"><div class="form-errors res"></div>
        <table class="table bordered highlight flat_of"><tbody>
            <?php foreach($u_flat as $key => $val){?><tr><td><input name="flat_id[]" type="hidden" value="<?php echo h($val->flat_id); ?>"><input type="text" class="flat_float" value="<?php if(!empty($val->name)){echo $val->name.' - ';} echo h($val->flat_no); ?>"><div class="drop_here_f_<?=$key?>"></div></td><td><input type="radio" name="ot[<?=$key?>]" id="owner_<?=$key?>" value="1"<?php if($val->owner_tenant==='1'){echo ' checked';} ?>><label for="owner_<?=$key?>">Owner</label> &nbsp;&nbsp; <input type="radio" name="ot[<?=$key?>]" id="tenant_<?=$key?>" value="2"<?php if($val->owner_tenant==='2'){echo ' checked';} ?>><label for="tenant_<?=$key?>">Tenant</label></td><td><button type="button" class="btn red accent-4 flat_remove btn-small"><i class="fa fa-close m0"></i></button></td></tr><?php }unset($val); ?>
        </tbody><tfoot><tr><td colspan="3"><button type="button" class="btn blue new_flat_btn btn-small"><i class="fa fa-plus"></i> Add Another Flat</button></td></tr></tfoot></table></div>
        <?=form_close()?>
        </div></div></div>
</div>
<div class="col s12 m4"><a href="#" data-id="<?=$id?>" class="center block pw-reset card-panel red-text bold-500"><i class="fa fa-lock"></i> Reset Password</a></div>
<div class="col s12 m4">
<?php if($user->phone_verified==='1'){ ?>
<div class="center card-panel green-text bold-500"><i class="fa fa-check-circle-o"></i> Phone Verified</div>
<?php } else { ?>
<div class="center card-panel red accent-3 white-text bold-500"><i class="fa fa-warning"></i> Phone Not Verified</div>
<?php } ?>
</div>
<div class="col s12 m4">
<?php if($user->email){ if($user->email_verified==='1'){ ?>
<div class="center card-panel green-text bold-500"><i class="fa fa-check-circle-o"></i> Email Verified</div>
<?php } else { ?>
<div class="center card-panel red accent-3 white-text bold-500"><i class="fa fa-warning"></i> Email Not Verified</div>
<?php }} else { ?>
<div class="center card-panel red accent-3 white-text bold-500"><i class="fa fa-warning"></i> Email Not Available</div>
<?php } ?>
</div>
</div>
<script>var last_key=<?=($u_flat)?$key:'-1'?></script>