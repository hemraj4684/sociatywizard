(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create','UA-77429908-1','auto');ga('send','pageview');
(function(b){b.event.special.keystop={add:function(a){var d=b(this),c=".__"+a.guid,f=a.data||500,e=-1;a.namespace+=c;d.on("input"+c+" propertychange"+c,function(){clearTimeout(e);e=setTimeout(function(){d.trigger("keystop"+c)},f)})},remove:function(a){a=".__"+a.guid;b(this).off("input"+a+" propertychange"+a)}};b.fn.keystop=function(a,b){return a?this.on("keystop",b,a):this.trigger("keystop")}})(jQuery);
$.fn.searchflat=function(){ff_v=0;this.keystop(function(){if(ff_v===0){ff_v=1;t=$(this);t.prop('disabled',true);page_loader();$.ajax({type:'post',data:'var='+t.val()+'&user_token='+token,url:URL+'flatsform/search_flat_keyword',complete:function(){ff_v=0;t.prop('disabled',false);t.focus();page_loader_exit()},success:function(res){t.next().html(res)}})}if(t.val().trim()===''){$(this).prev().val('')}});$(d).on('click','.drop-f-item',function(){t2=$(this);t.val(t2.data('value'));t.prev().val(t2.data('id'));t.next().html('')})}
$.fn.smoothScroll=function(){$(this).click(function(){if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {var target = $(this.hash);target = target.length ? target : $('[name=' + this.hash.slice(1) +']');if(target.length){$('html, body').animate({scrollTop: target.offset().top-50},1000);return false;}}})}
var d=document,W=window;function PD(e){e.preventDefault()}
var dt_obj={dom:'lBfrtip',buttons:[{extend:'print',title:$('#hidden_sname').val(),message:$('#hidden_saddr').val(),exportOptions:{columns:':visible'},customize:function(win){$(win.document.body).find('table').css({'box-shadow':'none','border-right':'1px solid #bbbbbb','border-left':'1px solid #bbbbbb'});}},{text:'Excel',action:function(e,dt,node,config){$('.data_list').tableExport({type:'excel',escape:'false'});}},'colvis',],"aaSorting":[],"autoWidth":false}
var colors = ['#ff1744','#303f9f','#9c27b0','#455a64','#2196F3','#ff9100','#424242','#bdbdbd','#222222','#795548'];
$(d).ready(function(){
$('.notification-item').click(function(){$('.notification-box').hide()})
$('.scroll_me').smoothScroll()
$('.notification-icon').click(function(e){PD(e);
  $(this).toggleClass('active')
  $('.notification-box').toggle().css({'right':($(window).width() - ($(this).offset().left + $(this).outerWidth()))+"px"})
})
$('.tooltipped').tooltip({delay: 50});
$('.modal-trigger').leanModal();
$('.tab-bar').click(function(e){
PD(e)
$('.tab-bar').removeClass('active')
$(this).addClass('active')
dt = $(this).data('target')
$('.tab-item').removeClass('active')
$('#'+dt).addClass('active')
})
$('select').material_select();
$('.has-submenu').click(function(e){PD(e);ic=$(this).find('.nav-action');$('.nav-action').removeClass('mdi-content-remove').addClass('mdi-content-add');if($(this).hasClass('active')){$(this).next().slideUp();ic.removeClass('mdi-content-remove');$(this).removeClass('active');}else{$(this).next().slideDown();ic.addClass('mdi-content-remove');$('.has-submenu.active').next().slideUp();$('.has-submenu').removeClass('active');$(this).addClass('active');}})
$('#sidebar-btn').click(function(){$('#sidebar').toggleClass('hide-under show-sidebar-sm');$('#page-content').toggleClass('full-page')})
$('.header-action').click(function(e){PD(e);$(this).toggleClass('active');if($('#header-dropdown').is(":visible")){$('#header-dropdown').hide()}else{$('#header-dropdown').show()}})
$('.toggle-fullscreen').click(function(){
toggleFullScreen()
})
$('.file_select_all').change(function(){
  chk = $('.files-area').find('input[type="checkbox"]')
  if($(this).is(':checked')){
    chk.each(function(){
      $(this).prop('checked',true)
    })
  } else {
    chk.each(function(){
      $(this).prop('checked',false)
    })
  }
})
$('.check_all').change(function(){
  chk = $('.data_list').find('input[type="checkbox"]')
  if($(this).is(':checked')){
    chk.each(function(){
      $(this).prop('checked',true)
    })
  } else {
    chk.each(function(){
      $(this).prop('checked',false)
    })
  }
})
$('#sms_form').submit(function(e){PD(e);$('.send-sms-btn').prop('disabled',true);$('.send-sms-btn').html('<i class="fa fa-spinner fa-pulse"></i> Sending...');chk=$('.data_list .sms_ok:checked');numbers={};numbers.numbers={};numbers.user_token=token;numbers.message=$('#sms_form textarea[name="message"]').val();numbers.url=$('#sms_form input[name="url"]').val();$.each(chk,function(k,v){numbers.numbers[k]=$(this).data('number')});t=$(this);$.ajax({type:'post',url:URL+'smsgateway/send_group',dataType:'json',data:numbers,complete:function(){$('.send-sms-btn').html('Send');$('.send-sms-btn').prop('disabled',false)},success:function(res){if(res.msg){$('.msg_err').text(res.msg)}else{$('.msg_err').text('')}if(res.user){$('.user_err').text(res.user)}else{$('.user_err').text('')}if(res.success){t[0].reset();$('.form-success').html('<div class="card success-area green"><div class="card-content">SMS Sent Successfully!</div></div>');setTimeout(function(){$('#sms_modal').closeModal();$('.form-success').html('');$('#user_select_all').attr('checked',false);$('.data_list .sms_ok').attr('checked',false);},1000);}}})})})
function sidebar_out(){$('#sidebar').removeClass('hide-under show-sidebar-sm');$('#page-content').removeClass('full-page')}
function sidebar_in(){$('#sidebar').addClass('hide-under show-sidebar-sm');$('#page-content').addClass('full-page')}
function print_init(){
  $('header').addClass('no-print')
  $('footer').addClass('no-print')
  $('#sidebar').addClass('no-print')
  window.print();
}
function page_loader(){$('body').append('<div class="progress page_loader"><div class="indeterminate"></div></div>')}
function page_loader_exit(){$('.progress.page_loader').remove()}
var token=$('input[name="user_token"]').val()
$.ajaxSetup({error:function(err){console.log(err);if(err.status===401){alert('Please Login!')}if(err.status===400){alert('You are not authorized!')}if(err.status===403){alert('Token Error!');location.reload()}}})
$(d).on('click','.success-area',function(){$(this).slideUp()})