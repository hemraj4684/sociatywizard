<?php
error_reporting(E_ALL);
function send_sms($phone,$sms){
	$curl = curl_init();
	curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1,CURLOPT_URL=> 'http://sms1.businesslead.co.in:8081/sendSMS?username=classmonitor&message='.urlencode($sms).'&sendername=CLASES&smstype=TRANS&numbers='.$phone.'&apikey=bb087a82-54e6-40f5-aab0-e9104370a464'));
	$exec = curl_exec($curl);
	var_dump($exec);
	curl_close($curl);
}

//send_sms('9930815474','test');
echo 'ok';
send_sms('9930815474','test');
echo dsgfsd();
phpinfo();