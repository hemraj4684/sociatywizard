<div class="container">
<div class="row">
<div class="col s12">
<div class="btn grey darken-4 abs waves-effect waves-light note-btn" title="Read Note"><i class="mdi-notification-event-note"></i></div>
<div class="card-panel abs tut-note">
    <h4>Note</h4><span class="note-close circle abs"><b class="red-text text-accent-3 mdi-navigation-close"></b></span>
    <ol>
    <li>Your tutorial will be searched by your tags.</li>
    <li>Avoid special characters in tags.</li>
    <li>Dont separate tags by comma.</li>
    <li><b>Example tag:</b> asp dotnet webservices with angularjs</li>
    <li>You permalink will create the following URL : http://www.codogram.com/tutorials/&lt;your-permalink&gt;.tutorial</li>
    <li>You can have minimum one step to maximum 20 steps in a single tutorial.</li>
    <li>Primary language is the programming language your tutorials is about.</li>
    <li>Select a language in step one is the language you are writing your first step in.</li>
    </ol>
</div>

<h5>Create A Tutorial</h5>
<form id="new-tutorial" method="post">
  <div class="row" id="part1">
    <div class="input-field col s12">
      <input id="title" name="title" type="text" class="validate" maxlength="100">
      <label for="title">Title Of Your Tutorial *</label>
    </div>
    <div class="input-field col s12">
      <input id="link" name="link" type="text" class="validate" maxlength="100">
      <label for="link">Permalink*</label>
    </div>
    <div class="input-field col s12">
      <input id="tags" name="tags" type="text" class="validate" maxlength="30">
      <label for="tags">Tags *</label>
    </div>
    <div class="input-field col s12">
        <select name="primary-subject">
          <option value="" disabled selected>Select Primary Language *</option>
          <?php
            foreach($this->data as $k => $v){
                echo '<option value="'.$this->valid_number($v['id']).'">'.$this->sanitize($v['name']).'</option>';
            }
            unset($v);
          ?>
        </select>
    </div>
    <div class="input-field col s12">
      <textarea id="description" name="description" class="materialize-textarea" maxlength="1000"></textarea>
      <label for="description">Write a description</label>
    </div>
    <div class="input-field col s12">
      <textarea id="conclusion" name="conclusion" class="materialize-textarea" maxlength="1000"></textarea>
      <label for="conclusion">Conclusion</label>
    </div>
    <input type="hidden" name="token" value="<?=$token?>">
    <div class="input-field col s12">
      <input type="checkbox" id="visible" value="1" name="visible">
      <label data-position="right" data-delay="0" data-tooltip="Check it if you want this tutorial to be visible immediately to public" class="tooltipped" for="visible">Live</label>
    </div>
    <div class="input-field col s12">
      <button class="btn waves-effect waves-light red accent-3" type="button" id="part1-btn" name="action"><i class="mdi-content-forward right"></i> Go To Step 1</button>
    </div>
  </div>
  <div class="row hide" id="part2">
  	<div class="input-field col s12">
      <button class="btn waves-effect waves-light red accent-3" type="button" id="part1-back-btn" name="action"><i class="mdi-hardware-keyboard-arrow-left left"></i>Go Back</button>
      <h5><b><u>Step 1</u></b></h5>
    </div>
    <div class="input-field col s12">
        <select name="language">
          <option value="" disabled selected>Select A Language *</option>
          <?php
            foreach($this->data as $k => $v){
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
      <textarea id="explain" name="explain" class="materialize-textarea" maxlength="2000"></textarea>
      <label for="explain">Explanation</label>
    </div>
    <div class="input-field col s12">
      <h6>Enter Your Code Below *</h6>
      <div id="code"></div>
    </div>
    <div class="input-field col s12">
      <button class="btn waves-effect waves-light red accent-3" type="submit" id="more-btn" name="action"><i class="mdi-content-add right"></i> Add Next Step</button>
      <button class="btn waves-effect waves-light red accent-3" type="submit" id="part2-btn" name="action"><i class="mdi-action-done right"></i> Submit</button>
    </div>
  </div>
</form>
</div>
</div>
</div>