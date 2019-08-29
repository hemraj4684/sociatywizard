<?php
class ReportModel extends MyDatabase {
	public function beta_user_report($user,$report){
		$this->db_operation('new_bug_feedback_report',array($user,$report),array(),$st);
		return $st;
	}
}