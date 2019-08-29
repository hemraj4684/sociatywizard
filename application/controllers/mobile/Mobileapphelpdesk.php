<?php
class Mobileapphelpdesk extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){$this->output->set_header('HTTP/1.0 403 Forbidden');}
	}
	public function new_help(){
		$this->output->set_header('Content-Type: application/json');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('message', 'Message', 'required|min_length[10]',array('required'=>'Please Write Your Message','min_length'=>'Your Message Is Too Short.'));
		$this->form_validation->set_rules('msg_type', 'Message Type', 'required',array('required'=>'Please select the message type'));
		$this->form_validation->set_rules('subject', 'Subject', 'required|min_length[3]',array('min_length'=>'Your subject is too short.','required'=>'Please Write Your Subject'));
		if($this->form_validation->run()){
			$this->load->model('mobilemodel');
			$type="1";
			if($this->input->post('msg_type')!=='1'){
				$type="2";
			}
			$data = array('message'=>$this->input->post('message'),'message_subject'=>$this->input->post('subject'),'message_type'=>$type,'sender_id'=>$this->input->server('HTTP_USER_ID'),'society_id'=>$this->input->server('HTTP_SOCIETY'));
			$last_id = $this->mobilemodel->insert_help($data);
			$outp = '<a data-transition="slide" href="helpdesk-message-item.html?id='.$last_id.'" class="collection-item"><span class="black-text helpdesk-list-item">'.h($this->input->post('subject')).'<br><span class="blue darken-1 white-text my-sm-badge font-12 no-bold">'.date('d M Y').'</span>';
			$outp .= ' <span class="indigo accent-3 white-text my-sm-badge font-12 no-bold">open</span>';
			$outp .= '<span class="secondary-content"><i class="mdi-image-navigate-next"></i></span></a>';
			echo json_encode(array('success'=>1,'output'=>$outp));
		} else {
			if(form_error('subject')!=''){
				echo json_encode(array('err' => form_error('subject')));
			} else if(form_error('message')!='') {
				echo json_encode(array('err' => form_error('message')));
			} else {
				echo json_encode(array('err' => form_error('msg_type')));
			}
		}
	}
	public function get_single_message($id=0){
		if(is_valid_number($id)){
			$this->load->model('mobilemodel');
			$data = $this->mobilemodel->get_single_helpdesk($id,$this->input->server('HTTP_USER_ID'))->row();
			if(!empty($data)){
				$user = $this->mobilemodel->get_user_name($this->input->server('HTTP_USER_ID'))->row();
				echo '<div class="card"><div class="card-content">';
				if($data->message_type==='1'){
					echo ' <span class="indigo accent-3 white-text my-sm-badge font-12 no-bold">general</span><input type="hidden" id="single_msg_type" value="1">';
				} else {
					echo ' <span class="red accent-3 white-text my-sm-badge font-12 no-bold">complaint</span><input type="hidden" id="single_msg_type" value="2">';
				}
				if($data->sent_from==='1'){
					echo ' <span class="grey-text text-darken-2 font-12"><i class="mdi-action-android"></i> Sent from android app</span>';
				} else {
					echo ' <span class="grey-text text-darken-2 font-12"><i class="mdi-device-dvr"></i> Sent from desktop</span>';
				}
				echo '<div class="grey-text mt10 text-darken-2 font-12">Date Sent : <i class="mdi-device-access-time vert"></i> '.date('h:ia, dS M Y',strtotime($data->date_sent)).'</div><p class="grey-text mt10 font-12 text-darken-2">Subject : '.h($data->message_subject).'</p><p class="mt10 font-16 black-text">'.para($data->message).'</p>';
				if($data->conv_type==='2'){
					echo '<span class="red accent-3 white-text my-sm-badge font-12 no-bold">closed</span>';
				}
				echo '</div></div>';
				echo '<div class="reply-thread"></div>';
				if($data->conv_type==='1'){
					echo '<div class="helpdesk-comment-form-area white z-depth-1"><form id="helpdesk-reply-form" data-ajax="false" method="post"><div class="row mb0"><input value="'.h($user->firstname.' '.$user->lastname).'" name="username" type="hidden"><div class="input-field col s10"><i class="mdi-editor-insert-comment prefix"></i><textarea class="materialize-textarea max-200-height comment-reply-help mb0" name="comment-reply" placeholder="Enter Comment"></textarea></div><button type="submit" class="btn col s2 btn-flat flat-btn-trans reply-help-btn input-field"><i class="mdi-content-send small"></i></button></div></form></div>';
				}
			}
		}
	}
	public function get_message_replies($id=0){
		if(is_valid_number($id)){
			$this->load->model('mobilemodel');
			$data = $this->mobilemodel->get_message_replies($this->input->server('HTTP_USER_ID'),$id);
			if($data){
				$res = $data->result();
				if(!empty($res)){
					foreach ($res as $key => $value) {
						$name = $value->admin_fn.' '.$value->admin_ln;
						if(empty($value->admin_fn) && empty($value->admin_ln)){
							$name = $value->user_fn.' '.$value->user_ln;
						}
						$this->call_reply_view($value->reply_text,$name,$value->reply_date,$value->reply_from,$value->userid);
					}
					unset($value);
				}
			}
		}
	}
	public function insert_new_message($id=0){
		if(is_valid_number($id)){
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('comment-reply', 'Reply', 'required');
			if($this->form_validation->run()){
				$this->load->model('mobilemodel');
				$data = array('reply_text'=>$this->input->post('comment-reply'),'user_id'=>$this->input->server('HTTP_USER_ID'),'parent_id'=>$id);
				$this->mobilemodel->insert_help_comment($data,$this->input->server('HTTP_USER_ID'),$id);
				$this->call_reply_view($this->input->post('comment-reply'),$this->input->post('username'),date('Y-M-d h:m:i'),1,$this->input->server('HTTP_USER_ID'));
			}
		}
	}
	public function call_reply_view($reply,$username,$date,$from,$userid){
		echo '<div class="card';
		if(!empty($userid)){
			echo ' right-align';
		}
		echo '"><div class="card-content"><p><p class="font-16 black-text">'.str_replace(array("\r\n","\r","\n"),'<br>',h($reply)).'</p><p class="mt10 text-darken-2 grey-text font-12">- '.h($username).'</p><p class="text-darken-2 font-12 mt10 grey-text"><i class="mdi-device-access-time vert"></i> '.date('h:ia, dS M Y',strtotime($date)).'</p>';
		if($from==1){
			echo ' <span class="grey-text text-darken-2 font-12"><i class="mdi-action-android"></i> Sent from android app</span>';
		} else {
			echo ' <span class="grey-text text-darken-2 font-12"><i class="mdi-device-dvr"></i> Sent from desktop</span>';
		}
		echo '</div></div>';
	}
	public function help_list($data){
		if(empty($data)){
			echo '<div class="na-data">No message available</div>';
		} else {
			foreach ($data as $key => $value) {
				echo '<a data-transition="slide" href="helpdesk-message-item.html?id='.$value->id.'" class="collection-item"><span class="black-text helpdesk-list-item">'.h($value->message_subject).'<br><span class="blue darken-1 white-text my-sm-badge font-12 no-bold">'.date('d M Y',strtotime($value->date_sent)).'</span>';
				if($value->conv_type==='1'){
					echo ' <span class="indigo accent-3 white-text my-sm-badge font-12 no-bold">open</span>';
				} else {
					echo ' <span class="red accent-3 white-text my-sm-badge font-12 no-bold">closed</span>';
				}
				echo '<span class="secondary-content"><i class="mdi-image-navigate-next"></i></span></a>';
			}
			unset($value);
		}
	}
	public function get_help_history(){
		$this->load->model('mobilemodel');
		$data = array();
		if($this->input->server('HTTP_SOCIETY')){
			$data = $this->mobilemodel->get_help_history($this->input->server('HTTP_USER_ID'))->result();
		}
		$this->help_list($data);
	}
}