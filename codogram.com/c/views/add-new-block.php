<div class="container">
<div class="row">
<div class="col s12"><br>
<a href="/user/edit-tutorial/<?=$this->valid_number($id)?>" class="btn btn-block waves-effect waves-light red accent-3"><i class="mdi-hardware-keyboard-arrow-left left"></i>Go Back</a>
<br><br><h6><b><u>Add A Step Here</u></b></h6>
<form id="add-block" method="post" action="">
<div class="input-field col s12">
<select name="language">
<option value="" disabled selected>Select A Language *</option>
<?php
foreach($subjects as $k => $v){
    echo '<option value="'.$this->valid_number($v['id']).'">'.$this->sanitize($v['name']).'</option>';
}
unset($v);
?>
</select>
</div>
<div class="input-field col s12">
<input id="heading" name="heading" type="text" class="validate" maxlength="100">
<label for="heading">Give it a heading</label>
</div>
<div class="input-field col s12">
<textarea id="explain" name="explain" class="materialize-textarea" maxlength="1000"></textarea>
<label for="explain">Explanation</label>
</div>
<div class="input-field col s12">
<h6>Enter Your Code Below</h6>
<div id="code"></div>
</div>
<div class="input-field col s12">
<button class="btn waves-effect waves-light red accent-3" type="submit" id="ab-btn" name="action"><i class="mdi-action-done right"></i> Submit</button>
</div>
<div class="input-field col s12">
<input type="hidden" value="<?=$token?>" name="ajaxToken" id="ajaxToken">
<div id="err"><div class="z-depth-1 center-align f-err red-text text-accent-4 indigo lighten-5"></div></div>
</div>
</form>
</div>
</div>
</div>