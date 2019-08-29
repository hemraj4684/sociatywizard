<?php
class LoginRegister extends MyDatabase {
	public function login($em){
		return $this->runSP('login_user',array($em),array());
	}
        public function register(array $in, array $out, &$st){
            return $this->db_operation('user_register',$in,$out, $st);
        }
}