<?php
class PageNotFound extends AppController {
    public $lfn,$lln,$body='<body>';
    public function run(){
        $this->goto_login();
        $loguser = $this->login_data();
        $this->lfn = $this->sanitize($loguser['fn']);
        $this->lln = $this->sanitize($loguser['ln']);
        $this->lun = $this->sanitize($loguser['un']);
        $this->page_not_found_header();
        $this->layout_header();
        $this->call_view('page_not_found');
        $this->layout_footer();
    }
}