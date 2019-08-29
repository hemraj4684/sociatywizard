<div class="container">
<div class="row search-page white">
<form method="get" id="search-form" action="/search" autocomplete="off">
<div class="input-field col s12">
<input type="text" value="<?=$this->sanitize($query)?>" id="search-enter" maxlength="100" placeholder="Search" name="search">
<input type="hidden" value="<?=$token?>" name="token" id="csrf-token">
</div>
<div class="input-field s-type-col col s12">
<input type="radio" name="abu" value="abulakdawala">
<input type="radio" id="type-1" name="searching" value="1" <?php if($find==='tutorials'){echo 'checked';}?>><label for="type-1">Tutorial</label>
<input type="radio" id="type-2" name="searching" value="2" <?php if($find==='people'){echo 'checked';}?>><label for="type-2">People</label>
</div>
</form>
<div id="search-result"><div class="col s12 search-preloader"><div class="progress red lighten-5"><div class="indeterminate red accent-3"></div></div><div class="progress yellow lighten-5"><div class="indeterminate yellow"></div></div><div class="progress blue lighten-5"><div class="indeterminate blue accent-4"></div></div></div></div>
</div>
</div>