var a1=ace.edit("code");a1.setTheme("ace/theme/clouds");a1.getSession().setMode("ace/mode/html");W.getElementById("code").style.fontSize="14px";a1.setOptions({enableBasicAutocompletion:true,enableSnippets:true,enableLiveAutocompletion:false,minLines:15,wrap:true,maxLines:100});var AD=0;$(W).ready(function(){$("#add-block").submit(function(e){EP(e);if(AD===0){loader_start();$('#ab-btn').html('Wait....');$('#ab-btn').prop('disabled',true);AD=1;var T=$(this);$.ajax({type:'post',url:'/ajax/add_block.php',dataType:'json',data:T.serialize()+'&code='+encodeURIComponent(a1.getSession().getValue()),success:function(r){$('#ab-btn').html('<i class="mdi-action-done right"></i> Submit');loader_end();$('#ab-btn').prop('disabled',false);AD=0;if(r.error){if(r.error==='token'){location.reload()}else if(r.error==='login'){location.href='/login'}$(".f-err").fadeIn().text(r.error)}if(r.success){location.href='/user/edit-tutorial/'+r.success}}})}})});