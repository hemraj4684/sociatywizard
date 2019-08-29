<?php
function h($var){
	return htmlentities($var,ENT_QUOTES);
}
function para($var){
	return str_replace(array("\r\n","\r","\n"),'<br>',h($var));
}
function style_css(){
	return '<link rel="stylesheet" href="'.base_url('assets/css/style_1.1.css').'">';
}
function script_js(){
	return '<script type="text/javascript" src="'.base_url('assets/js/script_1.1.js').'"></script>';
}
function is_valid_number($id){
	if(ctype_digit($id) && $id > 0){
		return true;
	}
	return false;
}
function is_valid_mobile_number($num){
	if(is_valid_number($num) && 10===strlen($num)){
		return true;
	}
	return false;
}
function is_valid_numbers(array $ids){
	foreach ($ids as $key => $value) {
		if(!is_valid_number($value)){
			return false;
			break;
		}
	}
	return true;
}
function is_valid_ints(array $ids){
	foreach ($ids as $key => $value) {
		if(!is_numeric($value)){
			return false;
			break;
		}
	}
	return true;
}
function send_sms($numbers,$msg,$by='',$soc='',$total=0,$notes='',$store_in_db=FALSE){
	$umsg = rawurlencode($msg);
	$curl = curl_init();
	curl_setopt_array($curl, array(CURLOPT_ENCODING => '',CURLOPT_RETURNTRANSFER => 1,CURLOPT_URL=>sms_url($numbers,$umsg)));
	$res = curl_exec($curl);
	curl_close($curl);
	if($store_in_db){
		$ci = get_instance();
		$ci->load->model('smsmodel');
		$store = array('sender_id'=>$by,'society_id'=>$soc,'message'=>$msg,'notes'=>$notes,'total_message'=>$total,'numbers'=>$numbers);
		$ci->smsmodel->store_sms($store);
	}
}
function promotional_sms($numbers,$msg,$by='',$soc='',$total=0,$notes='',$store_in_db=FALSE){
	$umsg = rawurlencode($msg);
	$curl = curl_init();
	curl_setopt_array($curl, array(CURLOPT_ENCODING => '',CURLOPT_RETURNTRANSFER => 1,CURLOPT_URL=>promotional_sms_url($numbers,$umsg)));
	$res = curl_exec($curl);
	curl_close($curl);
	if($store_in_db){
		$ci = get_instance();
		$ci->load->model('smsmodel');
		$store = array('sender_id'=>$by,'society_id'=>$soc,'message'=>$msg,'notes'=>$notes,'total_message'=>$total,'numbers'=>$numbers);
		$ci->smsmodel->store_sms($store);
	}
}
function promotional_sms_url($num,$msg){
	$data = "username=info@webkraft.in&hash=42881c9ba3aa539c50ef48c465150f57b0390f88&message=".$msg."&sender=TXTLCL&numbers=".$num;
	return 'http://api.textlocal.in/send/?'.$data;
}
function sms_url($num,$msg){
	$data = "username=abu2602@gmail.com&hash=b81329fbc574974adca421fcb0228487ec6cf5e2&message=".$msg."&sender=WIZARD&numbers=".$num;
	return 'http://api.textlocal.in/send/?'.$data;
}
function verify_user(){
	$ci = get_instance();
	if($ci->session->user && $ci->session->type && $ci->session->soc){
		if(isset($_COOKIE['user'])){
            if($_COOKIE['user'] === $ci->session->user){
				return true;
			}
		}
	}
	return false;
}
function verify_admin(){
	$ci = get_instance();
	if($ci->session->user && $ci->session->type && $ci->session->soc && $ci->session->type==='1'){
		if(isset($_COOKIE['user'])){
            if($_COOKIE['user'] === $ci->session->user){
				return true;
			}
		}
	}
	return false;
}
function auth_user(){
	return (verify_user()) ? true : redirect();
}
function auth_admin(){
	return (verify_admin()) ? true : redirect();
}
function is_valid_date($date,&$db_date){
	$exdate = explode('-', $date);
	if(count($exdate)===3){
		if(!empty($exdate[0]) && !empty($exdate[1]) && !empty($exdate[2])){
			if(!checkdate($exdate[1],$exdate[0],$exdate[2])){
				return false;
			} else {
				$db_date = $exdate[2].'-'.$exdate[1].'-'.$exdate[0];
				return true;
			}
		}
	}
	return false;
}
function invoice_popup($id){
	return "javascript:void window.open('".base_url('flats/view_bill/'.$id)."','','width=650,height=550,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;";
}
function go_to_mybill($id){
	return "javascript:void window.open('".base_url('me/my_bill/'.$id)."','','width=650,height=550,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;";
}
function ie_edit_popup($id){
	return "javascript:void window.open('".base_url('incomeexpense/edit_ie/'.$id)."','','width=650,height=550,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;";
}
function payment_details_popup($id){
	return "javascript:void window.open('".base_url('flats/payment_details/'.$id)."','','width=650,height=550,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;";
}
function js_popup($url){
	return "javascript:void window.open('".base_url($url)."','','width=650,height=550,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;";
}
function dt_options(){
	return '<script src="'.base_url('assets/js/jquery.dataTables.min.js').'"></script><script src="'.base_url('assets/js/dataTables.buttons.min.js').'"></script><script src="'.base_url('assets/js/buttons.print.min.js').'"></script><script src="'.base_url('assets/js/buttons.colVis.min.js').'"></script><script src="'.base_url('assets/js/dt/tableExport.js').'"></script><script src="'.base_url('assets/js/dt/jquery.base64.js').'"></script><script src="'.base_url('assets/js/jquery.dataTables.columnFilter.js').'"></script>';
}
function dt_css(){
	return '<link rel="stylesheet" href="'.base_url('assets/css/buttons.dataTables.min.css').'"><link rel="stylesheet" href="'.base_url('assets/css/jquery.dataTables.min.css').'">';
}
function is_valid_flat($soc,$flat,$select='',&$return=''){
	if(!empty($flat)){
		$ci = get_instance();
		if(is_array($flat)){
			if(!empty($select)){
				$ci->db->select($select);
			}
			$ci->db->order_by('id DESC')->select('id')->where_in('id',$flat);
			$ci->db->where(array('society_id'=>$soc));
			$res = $ci->db->get('flats');
			if(count($flat)===$res->num_rows()){
				$return = $res->result();
				return true;
			}
		} else {
			if(!empty($select)){
				$ci->db->select($select);
			}
			$ci->db->order_by('id DESC')->select('id')->where(array('society_id'=>$soc,'id'=>$flat));
			$res = $ci->db->get('flats');
			if($res->num_rows() > 0){
				$return = $res->row();
				return true;
			}
		}
	}
	return false;
}
function random_salt(){
	return md5(uniqid().time());
}
function mail_this($email,$sub,$message){
	$ci = get_instance();
	$ci->load->library('emailsystem');
	$ci->emailsystem->send_mail($email,$sub,$message);
}
function sendNotification($msg,$users,$data=array()){
$content = array("en" => $msg);
$fields = array(
'app_id' => "022685c9-a388-49b6-ab00-6e41e86c38de",
// 'included_segments' => array('All'),
// 'include_player_ids' => array('c72e2991-49fd-44bf-8ec1-c1c2fd2eaaba'),
'include_player_ids' => $users,
'data' => $data,
'contents' => $content
);
$fields = json_encode($fields);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic YmUyMTlhY2MtNmEwMC00NmM3LTlhOTQtM2M2MGYwNzVhNTFk'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$response = curl_exec($ch);
curl_close($ch);
return $response;
}
function multiRequest($data, $options = array()) {
  $curly = array();
  $result = array();
  $mh = curl_multi_init();
  foreach ($data as $id => $d) {
    $curly[$id] = curl_init();
    $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
    curl_setopt($curly[$id], CURLOPT_URL,            $url);
    curl_setopt($curly[$id], CURLOPT_HEADER,         0);
    curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
    if (is_array($d)) {
      if (!empty($d['post'])) {
        curl_setopt($curly[$id], CURLOPT_POST,       1);
        curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
      }
    }
    if (!empty($options)) {
      curl_setopt_array($curly[$id], $options);
    }
    curl_multi_add_handle($mh, $curly[$id]);
  }
  $running = null;
  do {
    curl_multi_exec($mh, $running);
  } while($running > 0);
  foreach($curly as $id => $c) {
    $result[$id] = curl_multi_getcontent($c);
    curl_multi_remove_handle($mh, $c);
  }
  curl_multi_close($mh);
  return $result;
}