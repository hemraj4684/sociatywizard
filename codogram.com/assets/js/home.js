function parallax(){var l1=$('#home-layer1'),l2=$('#home-layer2'),ww=$(window).width();if(ww>899){l1.css('top',-(window.pageYOffset/1.5)+150+'px');l2.css('margin-top',(window.pageYOffset/0.4)+'px');}else if(ww>600){l1.css('top',-(window.pageYOffset/1.3)+135+'px');l2.css('margin-top',(window.pageYOffset/0.7)+'px');}else{l1.css('top',-(window.pageYOffset/1.3)+215+'px');l2.css('margin-top',(window.pageYOffset/0.7)+'px');}}window.addEventListener("scroll",parallax,false);$(W).ready(function(){var Z=0;$('#home-report-form').submit(function(e){e.preventDefault();if(Z===0){var T=$(this);Z=1;$.ajax({type:'post',url:'/ajax/web-report.php',dataType:'json',data:T.serialize(),success:function(r){Z=0;if(r.error){$('.report-err').text(r.error);}else if(r.success){T.slideUp();$('.report-err').html('<span class="blue-text text-lighten-1">Thank you for submitting the form.<br>We will review your submission.</span>');T[0].reset();}}})}})});