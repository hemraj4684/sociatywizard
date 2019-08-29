<?php
require 'mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;

$mgClient = new Mailgun('key-4cfbfc4568f17460fe1e67e33afc3d9a');
$domain = "codogram.com";

$result = $mgClient->sendMessage($domain, array(
    'from'    => 'Codogram <info@codogram.com>',
    'to'      => '<abu2602@gmail.com>',
    'subject' => 'Hello Codogram',
    'html'    => 'Verification demo... <br>Please enter code 123456'
));