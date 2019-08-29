<div class="row">
<div class="col s12 m9 tutorial-body" itemprop="articleBody">
<h1 class="page-header"><?=$seoTitle?></h1>
<div class="margin-b10 page-time">
<div class="left half">Date Created : <?=$this->nice_time($this->data[0][0]['dc'])?></div>
<div class="left half">Date Updated : <?=$this->nice_time($this->data[0][0]['du'])?></div>
</div>
<div class="margin-b10">
<div class="left half">
<div class="margin-b10">
Author : <a class="hoverable author-link" href="/<?=$this->sanitize($this->data[2][0]['un'])?>"><img src="<?=SITE.$this->sanitize($this->data[2][0]['pc'])?>" class="author-img responsive-img"></a>
<strong><a class="indigo-text text-darken-3 author-name" href="/<?=$this->sanitize($this->data[2][0]['un'])?>"><?=$this->sanitize($this->data[2][0]['fn']).' '.$this->sanitize($this->data[2][0]['ln'])?></a></strong>
</div>
</div>
<div class="left half like-area-part">
<?php if($this->loggedin):
if(in_array($this->user_id, $expids)):
?>
<span id="like-btn"><i id="liked" class="red-text text-accent-3 mdi-action-stars"></i></span> Likes <span id="like-count"><b><?=$this->valid_number($this->data[0][0]['vt'])?></b></span>
<?php else: ?>
<span id="like-btn"><i id="like-icon" class="blue-text text-accent-4 mdi-action-stars"></i></span> Likes <span id="like-count"><b><?=$this->valid_number($this->data[0][0]['vt'])?></b></span>
<?php endif; ?>
<?php else: ?>
<span id="like-btn"><i id="ask-like" class="blue-text text-accent-4 mdi-action-stars"></i></span> Likes <span id="like-count"><b><?=$this->valid_number($this->data[0][0]['vt'])?></b></span>
<?php endif; ?>
<span class="show-tut-hits"><i class="vert hits-icon mdi-action-visibility red-text text-accent-3"></i> <b><?=$hits?></b> Views</span>
</div>
</div>
<div class="clearfix">
<p class="tut-desc"><?=str_replace(array("\r\n","\r","\n"),"<br>",$this->sanitize($this->data[0][0]['ds']))?></p>
</div>
<div class="margin-b10">
<div><button id="theme-btn" class="grey lighten-2 black-text btn">Light Theme</button></div>
<div class="steps-holder">
<?php
foreach($this->data[1] as $k => $v){
echo '<div class="tutorial-step">';
if($ctb>1){echo '<h5 class="s-no">Step '.++$k.'</h5>';}
echo '<h3 class="step-head">'.$this->sanitize($v['hd']).'</h3><p>'.str_replace(array("\r\n","\r","\n"),"<br>",$this->sanitize($v['ex'])).'</p><pre class="hoverable"><code>'.$this->sanitize($v['cd']).'</code></pre><hr class="hrule"></div>';
}
unset($v);
if(!empty($this->data[0][0]['cn'])){echo '<h5 class="s-no">Conclusion</h5><p>'.str_replace(array("\r\n","\r","\n"),"<br>",$this->sanitize($this->data[0][0]['cn'])).'</p><hr class="hrule">';}
?>
</div>
<div class="row">
<?php $this->js .= '<script src="https://platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script><script src="https://apis.google.com/js/platform.js" async defer></script><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script><script>(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)) return;js=d.createElement(s);js.id=id;js.src="//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.4";fjs.parentNode.insertBefore(js,fjs);}(document,"script","facebook-jssdk"));</script>'; ?>
<div class="social-share-icon left">
<script type="IN/Share"></script>
</div>
<div class="social-share-icon left">
<div class="fb-share-button" data-layout="button"></div>
<div id="fb-root"></div>
</div>
<div class="social-share-icon left">
<div class="g-plus" data-action="share" data-annotation="none"></div>
</div>
<div class="social-share-icon left">
<a href="https://twitter.com/share" class="twitter-share-button" data-via="__codogram" data-count="none" data-hashtags="codogram">Tweet</a>
</div>
</div>
</div>
<div id="comment-row"></div>
</div>
<div class="col s12 m3">
<div class="row"><div><h4 class="right-tag-head">Tags</h4></div>
<?php
$rtg = explode(' ',$this->data[0][0]['tg']);
foreach($rtg as $v){if(!empty($v)){echo '<a href="/search?query='.urlencode($v).'&find=tutorials" class="red lighten-4 tag-name red-text text-darken-4">'.$this->sanitize($v).'</a>';}}unset($v);
?>
</div>
<div id="right-side-top"><div class="right-center"><div class="preloader-wrapper big active"><div class="spinner-layer spinner-blue"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div><div class="spinner-layer spinner-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div><div class="spinner-layer spinner-yellow"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div><div class="spinner-layer spinner-green"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div></div></div>
</div>
</div>
<input type="hidden" value="<?=$token?>" name="token" id="csrf-token">
<script>var USER=<?=$this->valid_number($this->data[0][0]['me'])?>,TUT=<?=$this->valid_number($this->data[0][0]['id'])?></script>