<?php
class SearchModel extends MyDatabase {
	public function search_tutorials_by_tags($tags){
        return $this->runSP('tag_search', array($tags), array());
    }
    public function search_users_by_names($tags){
        return $this->runSP('search_user_by_names', array($tags), array());
    }
    public function fetch_subject_id($tag1,$tag2){
    	return $this->runSP('search_subject_like',array($tag1,$tag2),array());
    }
    public function search_by_subject($id){
    	return $this->runSP('search_by_subject',array($id),array());
    }
    public function search_by_subject_secondary($id){
    	return $this->runSP('search_by_subjects',array($id),array());
    }
}