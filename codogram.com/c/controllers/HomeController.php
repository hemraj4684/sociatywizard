<?php
class HomeController extends AppController {
    use TimezoneConverter{
        TimezoneConverter::__construct as private __tzoConstruct;
    }
    public $body='<body>',$lfn,$lln,$lun;
    public function __construct(){
        $this->__tzoConstruct();
        parent::__construct();
        $this->useridwise_data($logs);
        $this->data = $this->home();
        $this->title = 'Online Community For Programmers | Codogram';
        $TM = new TutorialsModel();
        $this->js = '<script src="/assets/js/home.js"></script>';
        $this->meta = '<link rel="canonical" href="http://www.codogram.com/"><meta name="description" property="og:description" content="A website for programmers interested in learning, sharing and writing tutorials. Also connect with other programmers around the globe."><meta property="og:type" content="website"><meta property="og:image" content="http://www.codogram.com/assets/img/codogram-logo-big.png"><meta property="og:url" content="http://www.codogram.com/">';
        $this->layout_header();
        $this->call_view('home');
        $this->layout_footer();
    }
}