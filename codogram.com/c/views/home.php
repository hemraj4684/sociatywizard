<div class="red lighten-4 abs home-intro"><div id="home-layer1"><img alt="codogram big banner image" class="center-block responsive-img" src="/assets/img/codogram-big-logo.png"></div></div>
<div class="home-intro-2"><div class="valign-wrapper"><h1 id="home-layer2" class="valign center black-text">Create your own tutorials<br>Select your favourite programming language<br>Start with <span class="red-text text-darken-1">Java</span> <span class="orange-text text-darken-3">HTML5</span> <span class="indigo-text text-lighten-1">PHP</span> <span class="red-text text-darken-3">Ruby</span> <span class="amber-text text-accent-4">Python</span> or any other language...</h1></div></div>
<div class="row white">
<div class="col l8 m12 s12">
<div class="home-left-top">
<h3 class="top-left-head top-head-margin">Latest Tutorials</h3>
<?php
foreach($this->data[0] as $key => $val){echo '<div class="latest-list-item padding-b10"><h3 class="margin-b0"><a class="grey-text text-darken-3" href="/tutorials/'.$this->sanitize($val['lk']).'.tutorial">'.$this->sanitize($val['tt']).'</a></h3><a href="/tutorials/'.$this->sanitize($val['lk']).'.tutorial" class="home-tut-time blue-grey-text text-lighten-1">'.$this->nice_time($val['dc']).'</a><p>'.$this->sanitize($val['ds']).'</p><a href="/tutorials/'.$this->sanitize($val['lk']).'.tutorial" class="left-align read-btn grey-text text-darken-1">read more <i class="vert home-more-icon mdi-action-input"></i></a></div>';}unset($val);
?>
</div>
</div>
<div class="col l4 m12 s12">
<div class="home-right-top">
<div class="card-panel">
<p class="beta-home-text">Codogram is currently running in beta version.</p>
<p class="beta-home-text">Report Bugs / Give Suggestions / Feedbacks in the form below.</p>
<form id="home-report-form" action="" method="post">
<textarea class="materialize-textarea" name="report" placeholder="Describe Here..."></textarea>
<input type="hidden" name="token" value="<?=$this->unique_token('H')?>">
<button type="submit" class="btn red accent-3 waves-effect waves-light" id="home-report-btn">Submit</button>
</form>
<div><b class="report-err red-text text-accent-3"></b></div>
</div>
</div>
</div>
</div>