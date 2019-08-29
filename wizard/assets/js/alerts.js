$(d).ready(function(){
	$('.datepicker').pickadate({selectMonths:true,min:true,format:'dd mmmm'})
	$('.timepicker').pickatime({autoclose:false,darktheme:true,twelvehour:true});
	$('.alert-form').submit(function(e){PD(e)
		t=$(this)
		$('.alert-submit-btn').prop('disabled',true)
		$('.alert-submit-btn').html('<i class="fa fa-spin fa-spinner left"></i> Sending Alert...')
		page_loader()
		$.ajax({
			type:'post',
			url:URL+'alertsform/send_alerts',
			data:t.serialize(),
			dataType:'json',
			success:function(r){
				if(r.success){
					t[0].reset()
					Materialize.toast('Alerts Sent Successfully !',4000,'green bold-400 z-depth-2')
				}
				if(r.alert_type){
					Materialize.toast(r.alert_type,4000,'red accent-3 bold-400 z-depth-2')
				}
				if(r.alert_purpose){
					Materialize.toast(r.alert_purpose,4000,'red accent-3 bold-400 z-depth-2')
				}
				if(r.alert_time){
					Materialize.toast(r.alert_time,4000,'red accent-3 bold-400 z-depth-2')
				}
				if(r.alert_date){
					Materialize.toast(r.alert_date,4000,'red accent-3 bold-400 z-depth-2')
				}
				if(r.alert_venue){
					Materialize.toast(r.alert_venue,4000,'red accent-3 bold-400 z-depth-2')
				}
				if(r.send_to){
					Materialize.toast(r.send_to,4000,'red accent-3 bold-400 z-depth-2')
				}
			},
			complete:function(){
				$('.alert-submit-btn').prop('disabled',false)
				page_loader_exit()
				$('.alert-submit-btn').html('<i class="mdi-content-send left"></i> Send Alert')
			}
		})
	})
})