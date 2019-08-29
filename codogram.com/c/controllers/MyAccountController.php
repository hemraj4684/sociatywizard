<?php
class MyAccountController extends AppController {
    use TimezoneConverter{
        TimezoneConverter::__construct as private __tzoConstruct;
    }
    public function __construct(){
        $this->__tzoConstruct();
        parent::__construct();
    }
    public $u_id,$fn,$ln,$un,$cn,$lfn,$lln,$lun,$dp,$tutorial,$uwise,$myurl,$uabt,$fb,$tw,$lk,$bn,$body='<body>',$ll,$sk,$fo,$fw;
    public function edit_tutorial($id){
        $this->goto_login();
        $this->meta = '<meta name="robots" content="noindex, nofollow">';
        $this->useridwise_data($logs);
        $this->user_vars($logs);
        $this->title = 'Editing tutorial...';
        $this->js = '<script src="/assets/js/jquery-ui.min.js"></script><script src="/assets/js/jquery.ui.touch-punch.min.js"></script><script src="/assets/js/editor/ace.js" type="text/javascript" charset="utf-8"></script><script src="/assets/js/require.js"></script><script src="/assets/js/editor/ext-language_tools.js"></script><script src="/assets/js/edit.js"></script>';
        $tut = new TutorialsModel();
        $this->data = $tut->get_edit_tutorial($this->user_id,$id);
        $subjects = $tut->fetch_subjects();
        if(isset($this->data[1])){
            $perma_chk = $tut->permalink_changed($id);
            if($perma_chk==='0'){
                $this->js .= '<script src="/assets/js/cp1.js"></script>';
            }
            $token = $this->token('EDIT');
            $this->layout_header();
            $this->call_view('edit_tutorial',array('token'=>$token,'id'=>$id,'subjects'=>$subjects,'perma_chk'=>$perma_chk));
        } else {
            $this->page_not_found_header();
            $this->layout_header();
            $this->call_view('page_not_found');
        }
        $this->layout_footer();
    }
    public function userwise_data(){
        $this->uwise = $this->username_data();
        if(!empty($this->uwise)){
            $this->user_vars($this->uwise);
        }
    }
    public function user_vars($var){
        $this->u_id = $this->valid_number($var['id']);
        $this->fn = $this->sanitize($var['fn']);
        $this->ln = $this->sanitize($var['ln']);
        $this->un = $this->sanitize($var['un']);
        $this->dp = '/dp/user-profile-pictures/'.$this->sanitize($var['pc']);
        if(empty($var['pc'])){
            $this->dp = '/assets/img/default-profile.jpg';
        }
        $this->bn = '/dp/user-banners/'.$this->sanitize($var['bn']);
        if(empty($var['bn'])){
            $this->bn = '/assets/img/default-banner.jpg';
        }
        $this->ll = $this->sanitize($var['ll']);
        $this->cn = $this->sanitize($this->get_single_country($var['cn']));
        $this->myurl = $this->sanitize($var['mu']);
        $this->uabt = $this->sanitize($var['am']);
        $this->fb = $this->sanitize($var['fb']);
        $this->tw = $this->sanitize($var['tw']);
        $this->sk = $var['sk'];
        $this->fo = $this->sanitize($var['fo']);
        $this->fw = $this->sanitize($var['fw']);
    }
    public function profile(){
        $this->body_class = '<body class="profile_body bg_color">';
        $err = $this->make_login();
        $subj = [];
        $this->userwise_data();
        $this->useridwise_data($loguser);
        if(!empty($this->uwise)){
        $this->tutorial = new TutorialsModel();
        $ll='';
        $token = '';
        if($this->loggedin){
            $ll = explode(',', $this->ll);
            $this->data = $this->tutorial->get_user_tutorials($this->uwise['id'],$this->user_id);
            $subj = $this->model->subjects();
            $token = $this->token('E1');
        }
        $this->title = $this->fn.' '.$this->ln.' - Codogram';
        $this->meta = '<link rel="canonical" href="'.SITE.$this->un.'"><meta property="og:image" content="'.trim(SITE,'/').$this->dp.'"><meta name="description" content="View '.$this->fn.' '.$this->ln.'\'s profile on Codogram. Join Codogram to connect with '.$this->fn.' '.$this->ln.' and other people.">';
        $this->layout_header();
        $this->call_view('profile',array('err'=>$err,'ll'=>$ll,'subj'=>$subj,'token'=>$token));
        } else {
            $this->page_not_found_header();
            $this->layout_header();
            $this->call_view('page_not_found');
        }
        $this->layout_footer();
    }
    public function make_login(){
        if(isset($_POST['email'],$_POST['password'],$_POST['token'])){
            if($this->check_unique_token('L',$_POST['token'])){
                if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
                    if($this->password_length($_POST['password'])){
                        $DB = new LoginRegister();
                        $data = $DB->login($_POST['email']);
                        if(isset($data[0],$data[0][0]['sl']) && !empty($data[0])){
                            if(hash('sha256', $_POST['password'].$data[0][0]['sl'])===$data[0][0]['pw']){
                                return $this->success_login($data[0][0]['id']);
                            }
                        }
                    }
                }
            }
            return '<div class="input-field col s12 center"><div class="red-text text-accent-4 indigo lighten-5 pad-sm"><b>*Incorrect Email / Password*</b></div></div>';
        }
        return '';
    }
    public function add_new_tutorial(){
        $this->useridwise_data($logs);
        $this->user_vars($logs);
        $this->js = '<script src="/assets/js/editor/ace.js" type="text/javascript" charset="utf-8"></script><script src="/assets/js/require.js"></script><script src="/assets/js/editor/ext-language_tools.js"></script><script src="/assets/js/n0.9.js"></script>';
        if($this->loggedin){
            $token = $this->token('ANT');
            $this->js .= '<script src="/assets/js/n1.js"></script>';
        } else {
            $token = 'token';
            $this->js .= '<script src="/assets/js/n0.8.js"></script>';
        }
        $this->meta = '<link rel="canonical" href="http://www.codogram.com/code/add-new-tutorial"><meta name="description" content="Share your tutorials with people. Start creating tutiorials from different programming languages. Select Java, HTML, PHP, Python, Ruby, Javascript etc....">';
        $this->title = 'Create Your Own Tutorial On Codogram';
        $getsubject = new TutorialsModel();
        $this->data = $getsubject->fetch_subjects();
        $this->layout_header();
        $this->call_view('add-new-tutorial',array('token'=>$token));
        $this->layout_footer();
    }
    public function success_login($id){
        $_SESSION['user'] = $id;
        $_SESSION['check'] = hash('sha256',$_SERVER['HTTP_USER_AGENT'].SALT);
        setcookie('user',$id,time() + (172800),'/',null,null,TRUE);
        session_regenerate_id(true);
        header('Location:'.URI);
        exit();
    }
    public function add_block($id){
        $this->goto_login();
        $this->meta = '<meta name="robots" content="noindex, nofollow">';
        $token = $this->token('nb');
        $this->title = 'Adding step...';
        $this->useridwise_data($logs);
        $this->user_vars($logs);
        $this->js = '<script src="/assets/js/editor/ace.js" type="text/javascript" charset="utf-8"></script><script src="/assets/js/require.js"></script><script src="/assets/js/editor/ext-language_tools.js"></script><script src="/assets/js/new_block.js"></script>';
        $tut = new TutorialsModel();
        $subjects = $tut->fetch_subjects();
        $this->layout_header();
        $this->call_view('add-new-block',array('token'=>$token,'id'=>$id,'subjects'=>$subjects));
        $this->layout_footer();
    }
    public function edit_profile(){
        $this->goto_login();
        $this->meta = '<meta name="robots" content="noindex, nofollow">';
        $token = $this->token('E1');
        $this->js = '<script src="/assets/js/edit_u.js"></script><script async defer src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyC3EnBd7EzI_mjOWWb-wQZOFglUVXSThqg&callback=initMap"></script>';
        $this->title = 'Edit Profile';
        $this->useridwise_data($logs);
        $this->user_vars($logs);
        $subj = $this->model->subjects();
        $this->layout_header();
        $this->call_view('edit_profile',array('token'=>$token,'subj'=>$subj));
        $this->layout_footer();
    }
    public function user_notifications(){
        $this->goto_login();
        $this->meta = '<meta name="robots" content="noindex, nofollow">';
        $this->useridwise_data($logs);
        $this->body = '<body class="notification_body bg_color">';
        $this->title = 'Your Notifications - Codogram';
        $this->data = $this->model->user_notifications($this->user_id);
        $this->layout_header();
        $this->call_view('user_notifications');
        $this->layout_footer();
    }
    public function forgot_password(){
        if($this->loggedin){header('Location:/');exit();}
        $this->body = '<body class="pw_forgot bg_color">';
        $this->title = 'Apply For New Password - Codogram';
        $this->js = '<script src="/assets/js/new_password_call.js"></script>';
        $this->layout_header();
        $this->call_view('forgot_password');
        $this->layout_footer();
    }
    public function verify_forgot_password($token,$id){
        if($this->loggedin){header('Location:/');exit();}
        $valid = false;
        $this->title = 'Reset Password - Codogram';
        $redis = new RedisStore();
        $exists = $redis->key_exist('fp:'.$id);
        $err = '';
        if($exists){
            $get = $redis->get('fp:'.$id);
            if($get===$token){$valid=true;}
            if(isset($_POST['new-p'],$_POST['new-cp'],$_POST['token'])){
                if($this->check_unique_token('FP',$_POST['token'])){
                    if($this->password_length($_POST['new-p'])){
                        if($_POST['new-p']===$_POST['new-cp']){
                            $salt = md5(time().SALT.mt_rand());
                            $hash = hash('sha256',$_POST['new-cp'].$salt);
                            $status = $this->model->edit8($id,$hash,$salt);
                            if($status){
                                $err = '<div class="center"><b>Your password has been successfully changed.<br>You can now login.</b></div>';
                                $this->js = '<script>setTimeout(function(){location.href="/login"}, 4000);</script>';
                                $redis->remove_set('fp:'.$id);
                            } else {
                                $err = 'Something went wrong. Please refresh the page and try again';
                            }
                        } else {
                            $err = 'Your passwords does not match';
                        }
                    } else {
                        $err = 'Password must be atleast 5 characters long';
                    }
                } else {
                    $err = 'Refresh the page and try again';
                }
            }
        }
        $this->layout_header();
        $this->call_view('lost_password_reset',array('valid'=>$valid,'err'=>$err));
        $this->layout_footer();
    }
}