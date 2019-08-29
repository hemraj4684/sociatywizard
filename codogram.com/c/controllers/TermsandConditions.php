<?php
class TermsandConditions extends AppController {
	public $body='<body>',$lfn,$lln,$lun;
	public function __construct(){
		parent::__construct();
		$this->body = '<body class="bg_color">';
		$this->useridwise_data($logs);
		$this->title = 'Terms and conditions - Codogram';
		$this->layout_header();
        $this->call_view('terms_conditions');
        $this->layout_footer();
	}
}