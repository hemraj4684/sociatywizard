<?php
class FrontEndController extends AppController {
    public $lfn,$lun,$body='<body>';
    use TimezoneConverter{
        TimezoneConverter::__construct as private __tzoConstruct;
    }
    public function __construct(){
        $this->__tzoConstruct();
        parent::__construct();
    }
    public function public_tutorial($tut){
        $dbtut = new FrontEndModel();
        $this->data = $dbtut->show_tutorial($tut,$this->user_id);
        $this->useridwise_data($logs);
        if(!empty($this->data)){
            $token = $this->token('VPT');
            $seoTitle = $this->sanitize($this->data[0][0]['tt']);
            $this->title = $seoTitle.' - Codogram';
            if(!empty($this->data[2][0]['pc'])){
                $this->data[2][0]['pc'] = 'dp/user-profile-pictures/'.$this->data[2][0]['pc'];
            } else {
                $this->data[2][0]['pc'] = 'assets/img/default-profile.jpg';
            }
            $this->js = '<script src="/assets/js/highlight.pack.js"></script><script src="/assets/js/tutorial.js"></script>';
            $expids=[];
            if($this->loggedin){
                $chkLike = $dbtut->get_tutorial_wise_likes($this->data[0][0]['id']);
                $expids = explode(',',$chkLike);
                $this->js .= '<script src="/assets/js/tut1.0.js"></script>';
            }else{
                $this->js .= '<script src="/assets/js/tut_l_1.0.js"></script>';
            }
            $this->css = '<link id="light-theme" rel="stylesheet" href="/assets/css/xcode.css" disabled><link rel="stylesheet" id="dark-theme" href="/assets/css/monokai_sublime.css">';
            $ctb = (int)$this->data[0][0]['tb'];
            $this->html = '<html itemscope itemtype="http://schema.org/Article">';
            $this->meta = '<meta name="description" content="'.$this->sanitize($this->data[0][0]['ds']).'"><link rel="canonical" href="'.SITE.'tutorials/'.$tut.'.tutorial"><meta property="og:type" content="article"><meta name="twitter:title" property="og:title" content="'.$seoTitle.' - Codogram"><meta property="og:image" itemprop="image" content="http://www.codogram.com/assets/img/codogram-logo-big.png"><meta property="og:url" content="'.SITE.'tutorials/'.$tut.'.tutorial"><meta name="twitter:description" property="og:description" itemprop="description" content="'.$this->sanitize($this->data[0][0]['ds']).'"><meta property="article:published_time" content="'.date('Y-m-d',strtotime($this->data[0][0]['dc'])).'"><meta property="article:modified_time" content="'.date('Y-m-d',strtotime($this->data[0][0]['du'])).'"><meta itemprop="headline" content="'.$seoTitle.'"><meta itemprop="datePublished" content="'.date('Y-m-d',strtotime($this->data[0][0]['dc'])).'"><meta name="twitter:card" value="summary">';
            $this->layout_header();
            $hits = $dbtut->get_tut_hits($this->data[0][0]['id']);
            $this->call_view('public_tutorial',array('seoTitle'=>$seoTitle,'ctb'=>$ctb,'token'=>$token,'expids'=>$expids,'hits'=>$hits));
            $this->layout_footer();
            if(isset($_SESSION['last_tuts'])){
                if(!in_array($this->data[0][0]['id'],$_SESSION['last_tuts'])){
                    if(count($_SESSION['last_tuts']) < 4){
                        array_push($_SESSION['last_tuts'], $this->data[0][0]['id']);
                    } else {
                        array_shift($_SESSION['last_tuts']);
                        array_push($_SESSION['last_tuts'], $this->data[0][0]['id']);
                    }
                }
            } else {
                $_SESSION['last_tuts'] = [];
                $_SESSION['last_tuts'][0] = $this->data[0][0]['id'];
            }
        } else {
            $this->page_not_found_header();
            $this->layout_header();
            $this->call_view('page_not_found');
            $this->layout_footer();
        }
    }
}