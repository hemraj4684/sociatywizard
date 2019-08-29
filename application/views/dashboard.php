<div class="row mt10 sum-up height-600">
	<div class="col s12 m6 l3">
		<div class="card animated bounceInDown">
			<div class="card-content">
				<h3>Society Members</h3>
				<p><?=$data_on[0]?> users <a style="" class="center red accent-3 btn btn-small" href="<?=base_url('registeredmembers')?>"><i class="mdi-action-view-list left"></i> view</a></p>
			</div>
		</div>
	</div>
	<div class="col s12 m6 l3">
		<div class="card animated bounceInDown">
			<div class="card-content">
				<h3>Association Members</h3>
				<p><?=$data_on[1]?> members <a class="purple btn btn-small" href="<?=base_url('registeredmembers/association_members')?>"><i class="mdi-action-view-list left"></i> view</a></p>
			</div>
		</div>
	</div>
	<div class="col s12 m6 l3">
		<div class="card animated bounceInDown">
			<div class="card-content">
				<h3>Documents Uploaded</h3>
				<p><?=$data_on[2]?> in total <a class="blue btn btn-small" href="<?=base_url('documents')?>"><i class="mdi-action-view-list left"></i> view</a></p>
			</div>
		</div>
	</div>
	<div class="col s12 m6 l3">
		<div class="card animated bounceInDown">
			<div class="card-content">
				<h3>Total Admins</h3>
				<p><?=$data_on[3]?> accounts <a class="btn btn-small" href="<?=base_url('admins')?>"><i class="mdi-action-view-list left"></i> view</a></p>
			</div>
		</div>
	</div>
	<div class="clearfix col s12 m6 l4">
		<div class="card animated bounceInLeft">
			<div class="card-content">
				<h3><i class="fa fa-file-text-o indigo-text text-accent-4"></i> Maintenance</h3>
				<table>
					<tr><td>Pending</td><td><a class="btn block btn-small red accent-3" href="<?=base_url('flatbill/pending')?>"><?=$data_on[4]?> <i class="fa fa-home"></i></a></td></tr>
					<tr><td width="50%">Collected this month</td><td><a class="btn block btn-small blue accent-3" href="<?=base_url('flatbill/month/'.date('m/Y'))?>"><i class="fa fa-rupee"></i> <?=number_format($data_on[5])?>/-</a></td></tr>
				</table>
			</div>
		</div>
	</div>
	<div class="col s12 m6 l4">
		<div class="card animated bounceInLeft">
			<div class="card-content">
				<h3><i class="fa fa-envelope indigo-text text-accent-4"></i> Messages From Members</h3>
				<table>
					<tr><td><i class="fa fa-exclamation-circle red-text accent-3"></i> Open Complaints</td><td><a class="red accent-3 block btn btn-small" href="<?=base_url('helpdesk/messages/complaints')?>"><i class="mdi-communication-email left"></i> <?=$data_on[6]?></a></td></tr>
					<tr><td><i class="fa fa-inbox"></i> General Message</td><td><a class="orange block accent-3 btn btn-small" href="<?=base_url('helpdesk/messages')?>"><i class="mdi-communication-email left"></i> <?=$data_on[7]?></a></td></tr>
				</table>
			</div>
		</div>
	</div>
	<div class="col s12 m6 l4">
		<div class="card animated bounceInUp">
			<div class="card-content">
				<h3><i class="fa fa-money indigo-text text-accent-4"></i> Income & Expense</h3>
				<table>
					<tr><td>Income last 30 days</td><td><a href="<?=base_url('incomeexpense/expense_list?type=income')?>" class="btn green btn-small block"><i class="fa fa-rupee"></i> <?=number_format($data_on[8])?>/-</a></td></tr>
					<tr><td width="50%">Expense last 30 days</td><td><a href="<?=base_url('incomeexpense/expense_list')?>" class="btn red accent-3 btn-small block"><i class="fa fa-rupee"></i> <?=number_format($data_on[9])?>/-</a></td></tr>
				</table>
			</div>
		</div>
	</div>
	<div class="col s12 m6 help_reply_box"></div>
	<div class="col s12 m6 new_user_notif"></div>
	<!-- <div class="col s12 m6 l4">
		<div class="card animated bounceInRight">
			<div class="card-content">
				<h3><i class="fa fa-tasks indigo-text text-accent-4"></i> My Task</h3>
				<table>
					<tr><td>Total Task</td><td><a class="indigo btn-block accent-3 btn btn-small" href="#">0</a></td></tr>
					<tr><td>Overdue Task</td><td><a class="red accent-3 btn-block btn btn-small" href="#">6</a></td></tr>
				</table>
			</div>
		</div>
	</div> -->
</div>