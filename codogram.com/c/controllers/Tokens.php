<?php
trait Tokens {
	public function token($var){
        if(isset($_SESSION[$var]) && $this->valid_token_time()){
            return $_SESSION[$var];
        }
        $this->store_session_time();
        return $_SESSION[$var] = base64_encode(hash('sha256', SALT.time().mt_rand()));
	}
	public function unique_token($var){
        return $_SESSION[$var] = base64_encode(hash('sha256', SALT.time().mt_rand()));
	}
	public function check_token($var,$token){
        return (isset($_SESSION[$var]) && $this->valid_token_time() && $_SESSION[$var]===$token) ? true : false;
	}
    public function check_only_token($var,$token){
        if(isset($_SESSION[$var]) && $_SESSION[$var]===$token){
            return true;
        }
        return false;
    }
	public function check_unique_token($var,$token){
        if(isset($_SESSION[$var]) && $_SESSION[$var]===$token){
            unset($_SESSION[$var]);
            return true;
        }
        return false;
	}
    public function valid_token_time(){
        if(isset($_SESSION['time']) && (time()-$_SESSION['time'])<3600){
            return true;
        }
        return false;
    }
    public function store_session_time(){
    	session_regenerate_id(true);
    	$_SESSION['time']=time();
    	$_SESSION['VPT'] = $this->token_string();
        $_SESSION['ANT'] = $this->token_string();
        $_SESSION['E1'] = $this->token_string();
        $_SESSION['search'] = $this->token_string();
        $_SESSION['EDIT'] = $this->token_string();
        $_SESSION['nb'] = $this->token_string();
    }
    public function token_string(){
    	return base64_encode(hash('sha256', SALT.time().mt_rand()));
    }
}