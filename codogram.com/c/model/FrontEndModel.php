<?php
class FrontEndModel extends MyDatabase {
    private $cache,$redis;
    public function __construct(){
        $this->cache = new CacheXcache();
        $this->redis = new RedisStore();
    }
    public function show_tutorial($perma,$id){
        if($this->cache->cache_isset('tp:'.$perma)){
            $getP = $this->cache->get('tp:'.$perma);
            if($this->cache->cache_isset('t:'.$getP) && $this->cache->cache_isset('ts:'.$getP)){
                $c1 = $this->cache->get('t:'.$getP);
                $c2 = $this->cache->get('ts:'.$getP);
                if($c1['vs']==='1' || $id===$c1['me']){
                    if($this->cache->get('u:'.$c1['me'])){
                        $c3 = $this->cache->get('u:'.$c1['me']);
                    } else {
                        $c3 = $this->runSP('get_user_on_public',array($c1['me']),array())[0][0];
                    }
                    return array_merge(array(array($c1)),array($c2),array(array($c3)));
                } else {
                    return array();
                }
            }
        }
        $data = $this->runSP('public_tutorial', array($perma,$id), array());
        if(isset($data[0][0],$data[1][0],$data[2][0]) && !empty($data[0][0]) && !empty($data[1][0]) && !empty($data[2][0])){
            $this->cache->set('t:'.$data[0][0]['id'],$data[0][0]);
            $this->cache->set('ts:'.$data[0][0]['id'],$data[1]);
            $this->cache->set('tp:'.$data[0][0]['lk'],$data[0][0]['id']);
            return $data;
        }
        return array();
    }
    public function create_like($user,$tut,&$st){
        return $this->db_operation('make_like', array($tut,$user), array('@check_exist','@tmp_tid','@chk','@tmp_uid','@total_votes'), $st);
    }
    public function get_tutorial_wise_likes($tut){
        if($this->cache->cache_isset('tl:'.$tut)){
            return $this->cache->get('tl:'.$tut);
        }
        $data = $this->runSP('tutorial_wise_likes', array($tut), array());
        if(isset($data[0][0]['users_id'])){
            $this->cache->set('tl:'.$tut,$data[0][0]['users_id']);
            return $data[0][0]['users_id'];
        }
        return '';
    }
    public function insert_tutorial_comment($user,$tut,$com,&$st){
        return $this->db_operation('insert_tutorial_comment', array($user,$tut,$com), array('@tut_id','@id','@fn','@ln','@un','@pc','@LID'),$st);
    }
    public function load_tutorial_comments($tut){
        $data = $this->runSP('load_tutorial_comments', array($tut), array());
        return $data;
    }
    public function remove_tutorial_comment($tut,$user,$id,&$st){
        return $this->db_operation('remove_tutorial_comment', array($tut,$user,$id), array('@sts'), $st);
    }
    public function insert_tutorial_comment_reply($reply,$cid,$tut,$user,&$st){
        return $this->db_operation('insert_tutorial_comment_reply', array($reply,$cid,$tut,$user), array('@valid_satus','@LID','@id','@fn','@ln','@un','@pc'), $st);
    }
    public function related_user_wise_tutorial($user,$tut){
        if($this->cache->cache_isset('ut:'.$user)){
            $ut = $this->cache->get('ut:'.$user);
            $cut = count($ut);
            $data = [];
            if(empty($ut) || $cut===1){
                return [];
            } else if($cut===2) {
                $rand = array_rand($ut,2);
                foreach($rand as $val){
                if($ut[$val]!==$tut){
                    if($this->cache->cache_isset('t:'.$ut[$val])){
                        array_push($data,$this->cache->get('t:'.$ut[$val]));
                    }
                }
                }
                unset($val);
                return $data;
            } else {
                $rand = array_rand($ut,3);
                foreach($rand as $val){
                if($ut[$val]!==$tut){
                    if($this->cache->cache_isset('t:'.$ut[$val])){
                        array_push($data,$this->cache->get('t:'.$ut[$val]));
                    }
                }
                }
                unset($val);
                return $data;
            }
        }
        $data = $this->runSP('related_user_wise_tutorial', array($user,$tut), array());
        if(isset($data[0])){
            return $data[0];
        }
        return array();
    }
    public function home() {
        $data = $this->runSP('load_home_data_1', array(), array());
        if(isset($data[0])){
            return $data;
        }
        return array();
    }
    public function load_last_tuts(array $ids, $tut){
        $data = [];
        foreach($ids as $val){
            if($this->cache->cache_isset('t:'.$val) && $tut!==$val){
                array_push($data,$this->cache->get('t:'.$val));
            }
        }
        unset($val);
        return $data;
    }
    public function incr_tut_hits($id){
        $this->redis->incr('ti'.$id);
    }
    public function get_tut_hits($id){
        return $this->redis->get('ti'.$id);
    }
}