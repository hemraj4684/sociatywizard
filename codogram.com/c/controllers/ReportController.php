<?php
class ReportController extends AppController {
	public function web_report($report,$token){
		if($this->report_token_validate('H',$token)){
            if($this->forms_validation_start()){
            	if(!empty(trim($report))){
            		if(strlen($report) > 19){
            			$this->model = new ReportModel();
            			$this->data = $this->model->beta_user_report($this->user_id,$report);
            			if($this->data){
                            unset($_SESSION['H']);
            				return array('success'=>'ok');
            			} else {
            				return array('error'=>'Something went wrong. Please refresh the page and try again.');
            			}
            		} else {
            			return array('error'=>'*Please enter atleast 20 characters*');
            		}
            	} else {
            		return array('error'=>'*You cannot submit blank form*');
            	}
            } else {
                return array('error'=>'*You should login to submit the form*');
            }
        } else {
            return array('error'=>'*Token Error. Please refresh the page*');
        }
	}
	public function report_token_validate($var,$token){
		if(isset($_SESSION[$var]) && $_SESSION[$var]===$token){
			return true;
		}
		return false;
	}
}