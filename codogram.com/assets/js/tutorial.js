function whm(z){Materialize.toast('Please <b><a class="white-text" href="/login">&nbsp;Login&nbsp;</a></b> '+z,10000,'blue accent-4');}$(W).ready(function(){hljs.initHighlightingOnLoad();$('#theme-btn').click(function(){if($('#light-theme').prop('disabled')){$(this).html('Dark Theme');$('#light-theme').prop('disabled',false);$('#dark-theme').prop('disabled',true);}else{$('#light-theme').prop('disabled',true);$('#dark-theme').prop('disabled',false);$(this).html('Light Theme');}});$('#comment-row').html('<div class="progress red lighten-5"><div class="indeterminate red accent-4"></div></div><div class="progress yellow lighten-5"><div class="indeterminate yellow accent-4"></div></div><div class="progress blue lighten-5"><div class="indeterminate blue accent-4"></div></div>');$.ajax({url:'/ajax/load_tutorial_comments.php',type:'post',data:'token='+$('#csrf-token').val(),success:function(r){$('#comment-row').html(r)}});$.ajax({type:'post',url:'/ajax/load_tag.php',data:'tut='+TUT+'&user='+USER+'&token='+$('#csrf-token').val(),success:function(r){$('#right-side-top').html(r);$('.right-author').text($('.author-name').text())}})});$(W).on('click','.show-reply',function(e){EP(e);var T=$(this);var V=T.attr('data-id');$('#comments-area').find('form').slideUp();if(!$('#reply-form-'+V).is(':visible')){$('#reply-form-'+V).slideDown();}});$(W).on('click','.cancel-reply',function(){var T=$(this);var V=T.attr('data-id');$('#reply-form-'+V).slideUp();});