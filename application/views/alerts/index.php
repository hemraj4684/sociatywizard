<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12">
<h5 class="breadcrumbs-title"><i class="fa fa-bell"></i> Alerts</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Alerts</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12">
	<ul class="collapsible popout alert_collapsible" data-collapsible="accordion">
		<li class="active">
			<div class="collapsible-header active">Meeting Alerts</div>
			<div class="collapsible-body">
				<?=form_open('','class="alert-form"')?>
					<p>
						Hi,<br>
						Society Meeting : <input type="text" name="alert_purpose" class="alert-inputs" placeholder="Enter purpose of meeting ?" maxlength="40"><br>
						Dated : <input type="text" name="alert_date" class="datepicker alert-inputs" placeholder="Enter meeting date"> at <input type="text" name="alert_time" class="timepicker alert-inputs" placeholder="Enter meeting time"><br>
						Venue : <input type="text" name="alert_venue" class="alert-inputs" placeholder="Enter meeting venue" maxlength="23"><br><br>
						- Sent From SocietyWizard.com<br><br>
						<b>Send To : </b><input value="1" type="radio" name="send_to" id="send_to_assoc_1" class="with-gap" checked><label for="send_to_assoc_1">Association Members</label> &nbsp;&nbsp;&nbsp;&nbsp;
						<input value="2" type="radio" name="send_to" id="send_to_all_1" class="with-gap"><label for="send_to_all_1">All Society Members</label>
						<br><br>
						<span class="row"><span class="col s12"><button class="right alert-submit-btn btn indigo accent-3 waves-effect waves-light"><i class="mdi-content-send left"></i> Send Alert</button></span></span>
					</p>
					<input type="hidden" name="alert_type" value="meeting">
				<?=form_close()?>
			</div>
	    </li>
	    <li>
	    	<div class="collapsible-header">Event Alerts</div>
	    	<div class="collapsible-body">
				<?=form_open('','class="alert-form"')?>
					<p>
						Hi,<br>
						Event : <input type="text" name="alert_purpose" class="alert-inputs" placeholder="Write what is the event ?" maxlength="40"><br>
						Dated : <input type="text" name="alert_date" class="datepicker alert-inputs" placeholder="Enter event date"> at <input type="text" name="alert_time" class="timepicker alert-inputs" placeholder="Enter event time"><br>
						Venue : <input type="text" name="alert_venue" class="alert-inputs" placeholder="Enter event venue" maxlength="23"><br><br>
						- Sent From SocietyWizard.com<br><br>
						<b>Send To : </b><input value="1" type="radio" name="send_to" id="send_to_assoc_2" class="with-gap" checked><label for="send_to_assoc_2">Association Members</label> &nbsp;&nbsp;&nbsp;&nbsp;
						<input value="2" type="radio" name="send_to" id="send_to_all_2" class="with-gap"><label for="send_to_all_2">All Society Members</label>
						<br><br><span class="row"><span class="col s12"><button class="right alert-submit-btn btn indigo accent-3 waves-effect waves-light"><i class="mdi-content-send left"></i> Send Alert</button></span></span>
					</p>
					<input type="hidden" name="alert_type" value="event">
				<?=form_close()?>
			</div>
	    </li>
	</ul>
</div>
</div>