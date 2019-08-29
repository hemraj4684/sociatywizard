<?php
class UserLoginRegister extends AppController {
    public $body='<body>';
	public function login(){
            if($this->loggedin){header('Location:/');exit();}
            $this->body = '<body class="login_body bg_color">';
            $this->title = 'Login - Codogram';
            $err = $this->make_login();
            $this->meta = '<link rel="canonical" href="http://www.codogram.com/login"><meta name="description" content="LogIn or Create a new account on Codogram.">';
            $this->layout_header();
            $this->call_view('login',array('err'=>$err));
            $this->layout_footer();
	}
	public function make_login(){
            if(isset($_POST['email'],$_POST['password'],$_POST['token'])){
                if($this->check_unique_token('L',$_POST['token'])){
                    if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
                        if($this->password_length($_POST['password'])){
                            $DB = new LoginRegister();
                            $data = $DB->login($_POST['email']);
                            if(isset($data[0],$data[0][0]['sl'],$data[0][0]['un'],$data[0][0]['pw']) && !empty($data[0])){
                                if(hash('sha256', $_POST['password'].$data[0][0]['sl'])===$data[0][0]['pw']){
                                    return $this->success_login($data[0][0]['id'],$data[0][0]['un']);
                                }
                            }
                        }
                    }
                }
                return '<div class="input-field center col s12"><div class="red-text text-accent-4 indigo lighten-5 pad-sm"><b>*Incorrect Email / Password*</b></div></div>';
            }
            return '';
	}
	public function success_login($id,$un){
            $_SESSION['user'] = $id;
            $_SESSION['check'] = hash('sha256',$_SERVER['HTTP_USER_AGENT'].SALT);
            setcookie('user',$id,time() + (172800),'/',null,null,TRUE);
            session_regenerate_id(true);
            header('Location:/'.$un);
            exit();
	}
	public function register(){
            if($this->loggedin){header('Location:/');exit();}
            $this->title = 'Registration On Codogram';
            $this->body = '<body class="register_body bg_color">';
            $countries = $this->get_countries();
            $this->js = '<script src="/assets/js/register.js"></script>';
            $this->meta = '<link rel="canonical" href="http://www.codogram.com/registration"><meta name="description" content="Create an account on Codogram. Share your tutorials with others. Get tutorials of different programming languages. Connect with programmers around the world.">';
            $this->layout_header();
            $this->call_view('register',array('countries'=>$countries));
            $this->layout_footer();
	}
	public function get_countries(){
        return $this->model->countries();
    }
}