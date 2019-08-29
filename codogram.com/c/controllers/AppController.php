<?php
class AppController {
    protected $model;
    use Tokens;
    public $css,$js,$title,$loggedin,$user_id='0',$data=[],$html='<html>',$meta;
    public function __construct(){
        $this->model = new Users();
        $this->loggedin = $this->isLogin();
    }
    public function sanitize($var){
        return htmlentities($var,ENT_QUOTES,'UTF-8');
    }
    public function valid_number($num){
        return (ctype_digit($num)) ? $num : '0';
    }
    public function isLogin(){
        if(isset($_SESSION['user'],$_SESSION['check'])){
            if($_SESSION['check'] === hash('sha256',$_SERVER['HTTP_USER_AGENT'].SALT)){
                if(isset($_COOKIE['user'])){
                    if($_COOKIE['user'] === $_SESSION['user']){
                        $this->user_id = $_SESSION['user'];
                        return true;
                    }
                }
            }
        }
        return false;
    }
    public function username_data(){
        return $this->model->username_data(USERURL);
    }
    public function login_data(){
        return $this->model->common_loggedin_data($this->user_id);
    }
    public function get_single_country($id){
    	return $this->model->get_single_country($id);
    }
    public function forms_validation_start(){
        if($this->loggedin && $this->check_valid_ajax_request()){
            return true;
        }
        return false;
    }
    public function layout_header(){
    	require APP_PATH.'layout/header_layout.php';
    }
    public function layout_footer(){
    	require APP_PATH.'layout/footer_layout.php';
    }
    public function call_view($file,array $data=[]){
        foreach($data as $key => $value){
            ${$key} = $value;
        }
    	require APP_PATH.'views/'.$file.'.php';
    }
    public function page_not_found_header(){
        $this->title = '404 - Page Not Found';
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    }
    public function password_length($pw){
        return (strlen($pw) > 4) ? true : false;
    }
    public function goto_login(){
        if(!$this->loggedin){
            header('Location:/login');
            exit();
        }
    }
    public function useridwise_data(&$loguser){
        if($this->loggedin){
            $loguser = $this->login_data();
            $this->lfn = $this->sanitize($loguser['fn']);
            $this->lln = $this->sanitize($loguser['ln']);
            $this->lun = $this->sanitize($loguser['un']);
        }
    }
    public function last_refer(){
    	if(isset($_SERVER['HTTP_REFERER'])){
    		return parse_url($_SERVER['HTTP_REFERER']);
    	}
    	return array();
    }
    public function last_path(){
        return pathinfo($_SERVER['HTTP_REFERER']);
    }
    public function home(){
        $model = new FrontEndModel();
        return $model->home();
    }
    public function valid_subject($int){
        if(filter_var($int, FILTER_VALIDATE_INT, array("options" => array("min_range"=>1, "max_range"=>76))) === false){
            return false;
        }
        return true;
    }
    public function check_valid_ajax_request(){
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest'===strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])){
            return true;
        }
        return false;
    }
}