<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m6 l7">
<h5 class="breadcrumbs-title"><i class="fa fa-odnoklassniki" aria-hidden="true"></i> Visitors Management</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Visitors Management</li>
</ol>
</div>
<div class="col s12 m6 l5">
<button class="btn btn-small green right modal-trigger" data-target="credentials_modal"><i class="fa fa-android"></i> App Credentials</button>
<input class="filled-in check_all" name="check_all" id="check_all" type="checkbox">
<label id="file_select_all_label" for="check_all"></label>
<button class="btn btn-small remove-btn waves-effect waves-light red accent-3 mb5"><i class="fa fa-remove"></i> Remove</button>
<button class="btn btn-small add-btn waves-effect modal-trigger waves-light purple mb5" data-target="add_modal"><i class="fa fa-plus"></i> Add A Visitor</button>
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12">

<div class="card"><div class="card-content"><div class="row mb0">
<h3 class="font-16 center mt0 bold-700">Search Visitors Datewise</h3>
<?=form_open('','class="visitor_search_form"')?>
<div class="col s12 m3 l3 offset-l1 input-field"><input type="text" name="date_from" class="datepicker" id="ex_df" value="<?=($this->session->vfrom) ? date('d-m-Y',strtotime($this->session->vfrom)) : ''?>"><label for="ex_df">Select Date Of Visit</label><p class="err-under from-err"></p></div>
<div class="col s12 m3 l3 input-field"><input type="text" name="date_upto" class="datepicker" id="ex_dt" value="<?=($this->session->vupto) ? date('d-m-Y',strtotime($this->session->vupto)) : ''?>"><label for="ex_dt">Select Date Upto</label><p class="err-under upto-err"></p></div>
<div class="col s12 m6 l5 input-field"><button type="submit" class="visitor-ds-btn btn indigo accent-3 waves-effect waves-light">Search</button> <a href="<?=base_url('visitors?reset=true')?>" class="visitor-ds-btn btn red accent-3 waves-effect waves-light">Reset</a></div>
<?=form_close()?>
</div></div></div></div>
<div class="col s12 table-cover">
<?php
$this->load->view('visitors/data_table',array('data'=>$data));
?>
</div>
</div>

<div id="credentials_modal" class="modal modal-fixed-footer">
<?=form_open('','id="visitors_app_credentials"')?>
<div class="modal-content">
<div class="row">
	<h5 class="col s12"><i class="fa fa-android"></i> App Credentials</h5>
</div>
<div class="row">
	<div class="input-field col s12">
		<input id="v_app_username" name="username" type="text" maxlength="20" value="<?=h($wname)?>" autocomplete="off" required>
		<label for="v_app_username">Username</label>
		<p class="err-under err-name"></p>
	</div>
	<div class="input-field col s12">
		<input id="v_app_password" name="password" type="password" autocomplete="off">
		<label for="v_app_password">Password</label>
		<p class="err-under err-password"></p>
	</div>
	<div class="input-field col s12">
		<input type="checkbox" class="filled-in" name="logout" id="w_logout"> <label for="w_logout">Logout of other devices</label>
	</div>
</div>
</div>
<div class="modal-footer">
	<button class="modal-action modal-close waves-effect waves-green btn-flat" type="button">Close</button>
	<button class="btn-flat w-submit-btn waves-effect waves-green" type="submit">Save</button>
</div>
<?=form_close()?>
</div>

<div id="add_modal" class="modal modal-fixed-footer">
<?=form_open_multipart('','id="new_visitor_form"')?>
<div class="modal-content">
<div class="row">
	<h5 class="col s12 center"><i class="fa fa-odnoklassniki"></i> Add A Visitor</h5>
	<div class="input-field col s12">
		<img class="responsive-img center-block new_v_img z-depth-1" width="125px" src="<?=base_url('assets/images/user_image.png')?>">
		<input name="userfile" type="file" accept=".jpg,.png,.jpeg" class="hide v_userfile">
	</div>
	<div class="input-field col s12 m4">
		<input id="v_name" name="v_name" type="text" maxlength="20" autocomplete="off" required>
		<label for="v_name">Visitor Name</label>
		<p class="err-under err-vname"></p>
	</div>
	<div class="input-field col s12 m4">
		<input id="v_contact" name="v_contact" type="text" maxlength="15" autocomplete="off">
		<label for="v_contact">Visitor Contact Number</label>
		<p class="err-under err-vno"></p>
	</div>
	<div class="input-field col s12 m4">
		<input id="v_purpose" name="v_purpose" type="text" maxlength="30" autocomplete="off">
		<label for="v_purpose">Visiting Purpose</label>
		<p class="err-under err-vpo"></p>
	</div>
	<div class="input-field col s12 m4">
		<input type="hidden" name="flat_id">
		<input id="v_flat" name="v_flat" type="text" autocomplete="off">
		<div></div>
		<label for="v_flat">Visiting Flat</label>
		<p class="err-under err-vflat"></p>
	</div>
	<div class="input-field col s12 m4">
		<input id="v_dov" name="v_dov" class="datepicker" value="<?=date('d-m-Y')?>" type="text" autocomplete="off" required>
		<label for="v_dov">Date Of Visiting</label>
		<p class="err-under err-dov"></p>
	</div>
	<div class="input-field col s12 m4">
		<input id="v_time" name="v_time" class="timepicker" value="<?=date('h:iA')?>" type="text" autocomplete="off">
		<label for="v_time">Time Of Visiting</label>
		<p class="err-under err-tov"></p>
	</div>
</div>
</div>
<div class="modal-footer">
	<button class="modal-action modal-close waves-effect waves-green btn-flat" type="button">Close</button>
	<button class="btn-flat wa-submit-btn waves-effect waves-green" type="submit">Save</button>
</div>
<?=form_close()?>
</div>