<?php
trait SubjectTrait {
	public function subjects(){
		$cache = new CacheXcache();
        if($cache->cache_isset('subjects')){
            return $cache->get('subjects');
        }
        $s = $this->runSP('fetch_all_subjects',array(),array());
        if(isset($s[0]) && !empty($s[0])){
            $cache->set('subjects',$s[0]);
            return $s[0];
        }
        return array();
	}
}