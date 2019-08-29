<?php $this->load->view('layout/head'); ?>
<header class="indigo accent-3">
  <button id="sidebar-btn" class="easing"><i class="mdi-navigation-menu"></i></button>
  <img src="<?=base_url('assets/images/society-wizard-logo.png')?>" height="50">
  <span id="version-number">1.3</span>
  <a href="#" class="white-text waves-effect easing header-action"><span><?=$this->fn?></span> <i class="mdi-navigation-arrow-drop-down"></i></a>
  <?php if($this->usertype==='1'): ?>
  <div class="notification-box">
    <div class="z-depth-2 collection">
      <a href="<?=base_url('alerts')?>" class="scroll_me collection-item notification-item"><i class="fa fa-credit-card"></i> New menu item added on sidebar.</a>
      <a href="<?=base_url('registeredmembers/member_list')?>" class="collection-item notification-item"><i class="fa fa-picture-o"></i> You can now enlarge society members thumbnails (picture) on click!</a>
      <a href="<?=base_url('incomeexpense/expense_list')?>" class="scroll_me collection-item notification-item"><i class="fa fa-file-text"></i> New feature addded. You can now create vouchers of your expenses.</a>
    </div>
  </div>
  <a href="#" class="waves-effect waves-light notification-icon"><i class="fa fa-bell"></i><span>3</span></a>
  <?php endif; ?>
  <div id="header-dropdown" class="white">
    <ul>
      <li><a class="blue-grey-text easing text-darken-3" href="<?=base_url('userpage/settings')?>">Personal Settings</a></li>
      <li><a class="blue-grey-text easing text-darken-3" href="<?=base_url('home/logout')?>">Logout</a></li>
    </ul>
  </div>
</header>
<div id="sidebar" class="white">
  <div class="login-user mb15">
  	<div class="row mb0">
  		<div class="col s4">
  			<img src="<?=$this->pic?>" class="circle responsive-img profile-image">
		</div>
		<div class="col s8">
			<p class="login_name"><?=$this->fn.' '.$this->ln?></p>
			<?php if($this->usertype==='1'): ?><p class="login_type">Administrator</p><?php endif; ?>
			<p class="login_type"><?=$this->s_name?></p>
			<input type="hidden" value="<?=$this->s_name?>" id="hidden_sname">
			<input type="hidden" value="<?=$this->s_address?>" id="hidden_saddr">
		</div>
	</div>
  </div>
  <ul class="menubar">
  <?php if($this->usertype==='1'): ?>
    <li><a href="<?php echo base_url('dashboard'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon mdi-action-dashboard"></i> Dashboard</a></li>
    <li><a href="<?php echo base_url('flats'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-building"></i> Flats Management</a></li>
    <li><a href="<?php echo base_url('registeredmembers'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-users"></i> Society Members</a></li>
    <li><a href="<?php echo base_url('flatbill'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-file-text-o"></i> Maintenance Bill</a></li>
    <li><a href="<?php echo base_url('incomeexpense'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-money"></i> Income & Expense</a></li>
    <li><a href="<?php echo base_url('subscriptionhistory'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-credit-card"></i> Subscription Payment</a></li>
    <li><a href="<?php echo base_url('documents'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-files-o"></i> Documents</a></li>
    <li><a href="<?php echo base_url('noticeboard'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-sticky-note-o"></i> Notice Board</a></li>
    <li><a href="<?php echo base_url('alerts'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-bell"></i> Alerts</a></li>
    <li><a href="<?php echo base_url('vendors'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-male"></i> Vendors</a></li>
    <li><a href="<?php echo base_url('visitors'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-odnoklassniki"></i> Visitors Management</a></li>
    <li><a href="<?php echo base_url('parkinglot'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-cab"></i> Parking Lot</a></li>
    <li><a href="<?php echo base_url('admins'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-user-secret"></i> Admins</a></li>
    <li><a href="<?php echo base_url('helpdesk/messages'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon mdi-hardware-headset-mic"></i> Helpdesk</a></li>
    <li><a href="<?php echo base_url('gallery'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon mdi-image-photo-library"></i> Gallery</a></li>
    <li><a href="<?php echo base_url('reports'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon mdi-action-assignment"></i> Reports</a></li>
    <li><a href="<?php echo base_url('societysettings'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-cog"></i> Settings</a></li>
  <?php else: ?>
    <li><a href="<?php echo base_url('me'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-home"></i> Home</a></li>
    <li><a href="<?php echo base_url('me/flatbill'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-file-text-o"></i> Maintenance Bill</a></li>
    <li><a href="<?php echo base_url('me/noticeboard'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-sticky-note-o"></i> Notice Board</a></li>
    <li><a href="<?php echo base_url('me/society_members'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-users"></i> Society Members</a></li>
    <li><a href="<?php echo base_url('me/helpdesk'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon mdi-hardware-headset-mic"></i> Helpdesk</a></li>
    <li><a href="<?php echo base_url('me/gallery'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon mdi-image-photo-library"></i> Gallery</a></li>
    <li><a href="<?php echo base_url('me/vendors'); ?>" class="waves-effect waves-purple easing menu-main"><i class="nav-icon fa fa-male"></i> Vendors</a></li>
  <?php endif; ?>
  </ul>
</div>
<div id="page-content">