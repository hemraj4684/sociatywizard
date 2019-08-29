<?php
class Memberrequests extends Requestcontrol {
	public function admin_helpdesk_replies_on_dashboard(){
		$this->load->model('dashboardmodel');
		$data = $this->dashboardmodel->helpdesk_replies($this->society)->result();
		if(!empty($data)){
			echo '<div class="card animated bounceInUp"><div class="card-content"><h3><i class="fa fa-reply indigo-text text-accent-4"></i> New Helpdesk Replies</h3><table><thead><tr><th>Sent By</th><th>Subject</td><th>Action</th></tr></thead><tbody>';
			foreach ($data as $key => $value) {
				echo '<tr><td>'.h($value->firstname.' '.$value->lastname).'</td><td>'.h($value->message_subject).'</td><td><a class="btn btn-small" href="'.base_url('helpdesk/message_item/'.$value->id).'"><i class="mdi-action-view-list left"></i> view</a></td></tr>';
			}
			unset($value);
			echo '</tbody></table></div></div>';
		}
	}
	public function admin_notifications_dashboard(){
		$this->load->model('adminnotificationdata');
		$data = $this->adminnotificationdata->get_all($this->society)->result();
		if(!empty($data)){
			echo '<div class="card"><div class="card-content"><div class="card-title">Notification</div><ol class="notif-list">';
			foreach ($data as $key => $value) {
				if($value->notification_type==='1'){
					echo '<li>'.h($value->fn_u1.' '.$value->ln_u1).' added a new user <a href="'.base_url('registeredmembers/member_list').'">'.h($value->fn_u2.' '.$value->ln_u2).'</a> <i class="fa fa-times-circle notif-dismiss" title="Dismiss" data-id="'.$value->id.'"></i></li>';
				}
			}
			unset($value);
			echo '</ol></div></div>';
		}
	}
	public function remove_notif_admin($id=0){
		if(is_valid_number($id)){
			$this->load->model('adminnotificationdata');
			if($this->adminnotificationdata->valid_notification($this->society,$id)){
				$this->adminnotificationdata->notification_dismiss($this->society,$id);
			}
		}
	}
}