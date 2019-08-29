<?php
require '../gun/mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;
class ForgotPasswordAjax extends AppController {
	public function reset_password($email,$token){
		if($this->check_only_token('FP',$token)){
			if(!$this->loggedin){
				if($this->check_valid_ajax_request()){
					if(filter_var($email,FILTER_VALIDATE_EMAIL)){
						$this->data = $this->model->email_exists($email);
						if(isset($this->data[0][0]['user_id']) && $this->data[0][0]['user_id']>0){
							$salt = hash('sha512',time().SALT.$this->data[0][0]['user_id'].mt_rand());
							$salt = strtolower($salt);
							$redis = new RedisStore();
							$set = $redis->set_timer('fp:'.$this->data[0][0]['user_id'],$salt,7200);
							if($set){
								unset($_SESSION['FP']);
								$mgClient = new Mailgun('key-4cfbfc4568f17460fe1e67e33afc3d9a');
								$domain = "codogram.com";
								$result = $mgClient->sendMessage($domain,array(
							    'from' => 'Codogram <info@codogram.com>', 'to' => $email,
							    'subject' => 'Codogram - Reset Your Password',
							    'html' => '<p><img style="max-width:100%;display:block;margin:0 auto;" src="http://www.codogram.com/assets/img/codogram-email.png"></p><p>You recently requested a password reset.</p><p>Click the following link or copy paste it in your browser to reset your password.</p><a target="_blank" href="http://www.codogram.com/u/verify/'.$salt.'/'.$this->data[0][0]['user_id'].'">http://www.codogram.com/u/verify/'.$salt.'/'.$this->data[0][0]['user_id'].'</a><p><b>Note:</b> This link will expire in 2 hours.</p><p>Thanks for using Codogram!</p>'
								));
								return array('success'=>'ok');
							} else {
								return array('error'=>'Something went wrong. Please try again.');
							}
						} else {
							return array('error'=>'The email does not exists');
						}
					} else {
						return array('error'=>'Please enter valid email address');
					}
				} else {
					return array('error'=>'Your browser does not support the request');
				}
			} else {
				return array('error'=>'You are already loggedin.');
			}
		} else {
			return array('error'=>'Token error');
		}
	}
}