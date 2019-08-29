<?php
require FCPATH.'../mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;
class Emailsystem {
	public function send_mail($email,$sub,$message){
		$mgClient = new Mailgun('key-4cfbfc4568f17460fe1e67e33afc3d9a');
		$result = $mgClient->sendMessage('societywizard.com', array(
		    'from'    => 'Society Wizard <info@societywizard.com>',
		    'to'      => '<'.$email.'>',
		    'subject' => $sub,
		    'html'    => $message
		));
	}
}