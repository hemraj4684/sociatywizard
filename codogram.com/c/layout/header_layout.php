<!DOCTYPE html>
<?=$this->html?>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta property="og:site_name" content="Codogram">
<meta name="application-name" content="Codogram">
<?=$this->meta?>
<meta name="twitter:site" content="@__codogram">
<title><?=$this->title?></title>
<link href="/assets/css/materialize.min.css" rel="stylesheet">
<link href="/assets/css/style-1.1.css" rel="stylesheet">
<link rel="shortcut icon" href="<?=SITE?>assets/img/icon.png" />
<?=$this->css?>
</head>
<?=$this->body?>
<div class="navbar-fixed">
<nav>
<div class="nav-wrapper red accent-3">
<a href="<?=SITE?>" class="brand-logo"><img class="web-logo" src="/assets/img/codogram-logo.png"></a>
<a href="#" data-activates="mobile-menu" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
<ul class="right hide-on-med-and-down">
<li class="search-form">
<form action="/search/" method="get" class="search-web"><div class="input-field"><input id="search-2" name="search" class="search no-margin expand-search" type="search" autocomplete="off" maxlength="100" required>
<label for="search-2"><i class="mdi-action-search"></i></label><i class="mdi-navigation-close"></i></div></form>
</li>
<?php if($this->loggedin): ?>
<li><a class="waves-effect waves-ripple" href="<?=SITE.$this->lun?>"><?=$this->lfn?></a></li>
<li><a class="dropdown-button waves-effect waves-ripple" href="#!" data-activates="dropdown"><i class="mdi-navigation-arrow-drop-down"></i></a></li>
<?php else: ?>
<li class="waves-effect waves-ripple"><a href="/code/add-new-tutorial">Create Tutorials</a></li>
<li class="waves-effect waves-ripple"><a href="/login">Login</a></li>
<li class="waves-effect waves-ripple"><a href="/registration">Register</a></li>
<?php endif; ?>
</ul>
<?php if($this->loggedin): ?>
<ul id="dropdown" class="head-drop dropdown-content">
<li><a href="/<?=$this->lun?>"><span class="vert tiny mdi-social-person"></span> Profile</a></li>
<li><a href="/code/add-new-tutorial"><span class="vert tiny mdi-content-create"></span> Create A Tutorial</a></li>
<li><a href="/user/notifications"><span class="vert tiny mdi-social-notifications"></span> Notifications</a></li>
<li><a href="/user/edit_profile"><span class="vert tiny mdi-action-settings"></span> Profile Settings</a></li>
<li><a href="/logout.php"><span class="vert tiny mdi-action-lock"></span> Logout</a></li>
</ul>
<?php endif; ?>
<div class="side-nav" id="mobile-menu">
<form class="mobile-search-form search-web" autocomplete="off" method="get"><div class="input-field"><input id="search-mobile" name="search" class="search no-margin" type="search" maxlength="100" required>
<label for="search-mobile"><i class="mdi-action-search black-text"></i></label><i class="mdi-navigation-close"></i></div></form>
<ul>
<?php if($this->loggedin): ?>
<li><a href="<?=SITE?>"><span class="left mdi-action-home"></span> Home</a></li>
<li><a href="/<?=$this->lun?>"><span class="left mdi-social-person"></span> Profile</a></li>
<li><a href="/code/add-new-tutorial"><span class="left mdi-content-create"></span> Create A Tutorial</a></li>
<li><a href="/user/notifications"><span class="left mdi-social-notifications"></span> Notifications</a></li>
<li><a href="/user/edit_profile"><span class="left mdi-action-settings"></span> Profile Settings</a></li>
<li><a href="/logout.php"><span class="left mdi-action-lock"></span> Logout</a></li>
<?php else: ?>
<li><a href="<?=SITE?>"><span class="left mdi-action-home"></span> Home</a></li>
<li><a href="/code/add-new-tutorial"><span class="left vert mdi-content-create"></span> Create Tutorials</a></li>
<li><a href="/login"><span class="left vert mdi-action-lock-open"></span> Login</a></li>
<li><a href="/registration"><span class="left vert mdi-image-portrait"></span> Register</a></li>
<?php endif; ?>
</ul>
</div>
</div>
</nav>
</div>
<div id="page-loader" class="progress red lighten-5"><div class="indeterminate blue accent-4"></div></div>