<?php
$COUNT=count($this->data[1]);
?>
<div class="container">
<div class="row">
<div class="col s12"><br>
<h6 class="edit-head blue-grey lighten-5"><b>Edit : </b> <a class="blue-text text-accent-4" href="/tutorials/<?=$this->sanitize($this->data[0][0]['lk'])?>.tutorial"><?=$this->sanitize($this->data[0][0]['tt'])?></a></h6><br>
<div style="overflow:auto">
<ul <?php if($COUNT>7){echo 'style="width:1000px !important;"';}else{echo 'style="min-width:500px"';}?> class="tabs tut_edit_tab">
<li class="tab col s3"><a href="#main"><span class="hide-on-med-and-down">Intro</span><span class="hide-on-large-only">I</span></a></li>
<?php $j='0'; for ($j;$j < $COUNT; $j++) { ?>
<li class="tab col s3">
<?php if($COUNT<9): ?>
<a href="#step<?=$j?>">Step <?=$j+1?></a>
<?php else: ?>
<a href="#step<?=$j?>"><?=$j+1?></a>
<?php endif; ?>
</li>
<?php } ?>
</ul></div>
<div id="main">
<form id="update-main" method="post">
<div class="input-field col s12">
  <input id="title" name="title" type="text" class="validate" value="<?=$this->data[0][0]['tt']?>" maxlength="100" required>
  <label for="title">Title Of Your Tutorial *</label>
</div>
<div class="input-field col s12">
  <input id="tags" name="tags" type="text" class="validate" maxlength="30" value="<?=$this->data[0][0]['tg']?>">
  <label for="tags">Tags *</label>
</div>
<div class="input-field col s12">
<select name="primary-subject">
<?php
foreach($subjects as $k => $v){
echo '<option value="'.$this->valid_number($v['id']).'" ';
if($this->data[0][0]['ps']===$v['id']){
echo 'selected';
}
echo '>'.$this->sanitize($v['name']).'</option>';
}
unset($v);
?>
</select>
</div>
<div class="input-field col s12">
  <textarea id="description" name="description" class="materialize-textarea" maxlength="1000"><?=$this->data[0][0]['ds']?></textarea>
  <label for="description">Write a description</label>
</div>
<div class="input-field col s12">
<textarea id="conclusion" name="conclusion" class="materialize-textarea" maxlength="1000"><?=$this->data[0][0]['cn']?></textarea>
<label for="conclusion">Conclusion</label>
</div>
<div class="input-field col s12">
  <input type="checkbox" id="visible" name="visible" value="1" <?=($this->data[0][0]['vs']==='1') ? 'checked' : '' ?>>
  <label for="visible">Live</label>
</div>
<div class="input-field col s12">
  <button class="btn waves-effect waves-light red accent-3" type="submit" id="main-btn" name="action"><i class="mdi-action-done right"></i>Update</button>
  <a href="/user/add-block/<?=$id?>" class="btn waves-effect waves-light indigo lighten-1" name="action">Add a step <i class="mdi-content-add right"></i></a>
  <a class="btn rt-btn right grey darken-1 tooltipped modal-trigger" href="#modal2" data-position="top" data-delay="0" data-tooltip="Delete This Tutorial"><i class="mdi-navigation-close"></i></a>
</div>
</form>
<div class="input-field col s12">
    <div class="z-depth-1 center-align f-err red-text text-accent-4 indigo lighten-5"><div id="one-err"></div></div>
</div>
</div>

<div class="step-blocks">
<?php
$i=0;
for($i;$i < $COUNT; $i++){
echo '<div id="step'.$i.'">';
?>
<form action="" class="steps" data-id="<?=$this->data[1][$i]['id']?>" method="post" >
<div class="input-field col s12">
<select name="language">
<?php
foreach($subjects as $k => $v){
  echo '<option value="'.$this->valid_number($v['id']).'" ';
  if($this->data[1][$i]['lg']===$v['id']){
    echo 'selected';
  }
  echo '>'.$this->sanitize($v['name']).'</option>';
}
unset($v);
?>
</select>
</div>
<div class="input-field col s12">
<input id="heading" name="heading" type="text" class="validate" maxlength="100" value="<?=$this->sanitize($this->data[1][$i]['hd'])?>">
<label for="heading">Give it a heading</label>
</div>
<div class="input-field col s12">
<textarea id="explain" name="explain" class="materialize-textarea" maxlength="2000"><?=$this->sanitize($this->data[1][$i]['ex'])?></textarea>
<label for="explain">Explanation</label>
</div>
<div class="input-field col s12">
<div id="code" class="editor-<?=$this->data[1][$i]['id']?>"><?=$this->sanitize($this->data[1][$i]['cd'])?></div>
</div>
<div class="input-field col s12">
<button class="btn waves-effect waves-light parts-btn red accent-3" type="submit"><i class="mdi-action-done right"></i> Update</button>
<?php if($COUNT>1):?>
<a data-position="top" data-delay="0" data-tooltip="Remove This Step" class="tooltipped waves-effect waves-light btn m-btn grey darken-1 modal-trigger right" href="#modal1"><i class="mdi-navigation-close"></i></a><input type="hidden" value="<?=$this->data[1][$i]['id']?>" name="stid" autocomplete="off">
<?php endif;?>
</div>
<div class="input-field col s12">
<div class="z-depth-1 center-align f-err red-text text-accent-4 indigo lighten-5"><div id="err<?=$this->data[1][$i]['id']?>"></div></div>
</div>
</form>
<?php
echo '</div>';
}
?>
</div>
<input type="hidden" value="<?=$token?>" name="ajaxToken" id="ajaxToken">
<div class="clearfix"></div>
<?php if($perma_chk==='0'): ?>
<hr class="hrule">
<div class="row">
<div class="col s12">
<h6><b><u>Change Permalink</u></b></h6>
</div>
<form id="new-perma" action="" method="post">
<div class="input-field col s12">
<input maxlength="100" type="text" name="permalink" value="<?=$this->data[0][0]['lk']?>">
</div>
<div class="col s12"><input type="submit" value="Submit" class="btn cpl sm-button red margin-b10 accent-3 right"><span class="grey lighten-2 left black-text pad-5">You can change the permalink only one time</span></div>
<div class="input-field col s12">
<div class="z-depth-1 center-align red-text text-accent-4 indigo lighten-5 left pad-5 perma-err"><div id="perma-err"></div></div>
</div>
</form>
</div>
<?php endif; ?>
<?php if($COUNT>1):?>
<hr class="hrule">
<h6><b><u>Change Positions</u></b></h6>
<form id="fc-pos" action="" method="post">
<div class="col s12">
<div id="sortable">
<?php
$x=0;
for($x;$x < $COUNT;$x++){
    echo '<div class="input-field col s12"><div class="truncate ui-state-default">Step ';
    echo $x+1;
    echo ' ( '.$this->sanitize(substr($this->data[1][$x]['cd'], 0,125)).' ... )';
    echo '<input class="pos-inp validate" min="1" max="'.$COUNT.'" name="positions[]" type="hidden" value="'.$this->data[1][$x]['po'].'" required></div></div>';

}
?>
</div>
</div>
<div class="input-field col s12"><button class="pos-btn btn waves-effect waves-light red accent-3" type="submit" ><i class="mdi-action-done right"></i> Update</button></div>
<div class="input-field col s12 m6"><div class="z-depth-1 center-align f-err2 red-text text-accent-4 indigo lighten-5"><div id="pos-err"></div></div></div>
</form>
<?php endif;?>
</div>
</div>
</div>
<div id="modal1" class="modal">
<div class="modal-content"><h4>Confirm!</h4><p>Are You Sure You Want To Remove This Step ?</p></div>
<div class="modal-footer">
<a href="#!" class="remove-block modal-action waves-effect waves-green btn-flat">Confirm Remove</a><input type="hidden" name="tzid" id="tzid" autocomplete="off">
<button data-target="modal1" class="modal-action modal-close btn-flat waves-effect waves-light">Cancel</button></div></div>
<div id="modal2" class="modal">
<div class="modal-content"><h4>Confirm!</h4><p>Are You Sure You Want To Delete This Tutorial ?</p></div>
<div class="modal-footer">
<div class="left a-d"><span class="pad-5 white-text indigo darken-3"><i class="mdi-action-done"></i> Successfully Deleted</span></div>
<button data-id="<?=$id?>" class="remove-tut modal-action waves-effect waves-green btn-flat">Confirm Delete</button>
<button data-target="modal1" class="modal-action modal-close btn-flat waves-effect waves-light">Cancel</button></div></div>