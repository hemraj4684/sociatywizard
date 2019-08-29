function dt_report(){
$('.total_html').html($('.total_value').val())
dt_obj.paging=false
dt_obj.buttons[0].title = $('.report-title').html()
dt_obj.buttons[0].message = $('.report-desc').html()
return $('.report-table').dataTable(dt_obj)
}
$(d).ready(function(){$('.datepicker').pickadate({selectMonths:true,selectYears:100,format:'dd-mm-yyyy'});dt_report()
$('#custom_ie').submit(function(e){PD(e);t=$(this);page_loader();$('.sr-btn').prop('disabled',true)
$.ajax({type:'post',url:URL+'ie_form/search_datewise_report',success:function(res){
if(res.data){$('.table-holder').html(res.data);dt_report()}
if(res.to_err){Materialize.toast(res.to_err,4000,'red accent-3 bold-500')}
if(res.from_err){Materialize.toast(res.from_err,4000,'red accent-3 bold-500')}
},data:t.serialize(),dataType:'json',complete:function(){page_loader_exit();$('.sr-btn').prop('disabled',false)}
})})
$('#custom_ie_category').submit(function(e){PD(e);t=$(this);page_loader();$('.sr-btn').prop('disabled',true)
$.ajax({type:'post',data:t.serialize(),url:URL+'ie_form/search_datewise_report_category',dataType:'json',
success:function(res){if(res.to_err){Materialize.toast(res.to_err,4000,'red accent-3 bold-500')}if(res.data){$('.table-holder').html(res.data);dt_report()}
if(res.from_err){Materialize.toast(res.from_err,4000,'red accent-3 bold-500')}
if(res.cat){Materialize.toast(res.cat,4000,'red accent-3 bold-500')}
},complete:function(){page_loader_exit();$('.sr-btn').prop('disabled',false)}})
})
})