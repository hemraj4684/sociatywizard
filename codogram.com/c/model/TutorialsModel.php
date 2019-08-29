<?php
class TutorialsModel extends MyDatabase {
    use SubjectTrait;
    public function get_user_tutorials($user,$id){
        $cache = new CacheXcache();
        $rdata = [];
        if($cache->cache_isset('tuc:'.$user) && $cache->cache_isset('ut:'.$user)){
            $IDS = $cache->get('ut:'.$user);
            rsort($IDS);
            if($user===$id){
                foreach($IDS as $k => $v){
                    array_push($rdata,$cache->get('t:'.$v));
                }
            } else {
                foreach($IDS as $k => $v){
                    array_push($rdata,$cache->get('t:'.$v));
                }
                unset($v);
                foreach($rdata as $k => $v){
                    if($v['vs']==='0'){
                        unset($rdata[$k]);
                    }
                }
                unset($v);
            }
            return $rdata;
        }
        $data = $this->runSP('tutorials_by_user',array($user),array());
        $ids = [];
        $oldid = '0';
        foreach($data as $k => $v){
            if($k%2===0){
                $oldid = '0';
                $cache->incr('tuc:'.$user);
                if(isset($v[0]['id'])){
                    $oldid = $v[0]['id'];
                    array_push($rdata, $v[0]);
                    array_push($ids, $v[0]['id']);
                    $cache->set('t:'.$v[0]['id'],$v[0]);
                    $cache->set('tp:'.$v[0]['lk'],$v[0]['id']);
                }
            } else {
                if($oldid!=='0'){
                    $cache->set('ts:'.$oldid,$v);
                }
            }
        }
        $cache->set('ut:'.$user,$ids);
        unset($v);
        if($user===$id){
            return $rdata;
        }
        foreach($rdata as $k => $v){
            if($v['vs']==='0'){
                unset($rdata[$k]);
            }
        }
        unset($v);
        return $rdata;
    }
    public function get_edit_tutorial($user,$id){
        $cache = new CacheXcache();
        if($cache->cache_isset('t:'.$id) && $cache->cache_isset('ut:'.$user)){
            if(in_array($id,$cache->get('ut:'.$user))){
                $m1 = array(array($cache->get('t:'.$id)));
                $m2 = array($cache->get('ts:'.$id));
                return array_merge($m1,$m2);
            }
            return array(array());
        }
        return $this->runSP('tutorials_for_edit',array($user,$id),array());
    }
    public function update_main(array $in, array $out, &$st){
        return $this->db_operation('update_intro_tutorials', $in, $out, $st);
    }
    public function update_block(array $in, &$st){
        return $this->db_operation('update_single_block', $in, array('@check_valid'), $st);
    }
    public function add_block(array $in, &$st){
        return $this->db_operation('add_new_block', $in,array('@lid','@mytime','@maxtb'),$st);
    }
    public function add_new(array $in, &$st){
        return $this->db_operation('insert_tutorials', $in,array('@iid','@mytime','@iid2'),$st);
    }
    public function fetch_subjects(){
        return $this->subjects();
    }
    public function delete_block(array $in, &$st){
        return $this->db_operation('removing_step', $in, array('@check_valid'), $st);
    }
    public function update_positions(array $in, &$st){
        return $this->db_operation('changing_positions', $in, array('@check_valid','@ps'), $st);
    }
    public function delete_tutorial($id,$user,&$st){
        return $this->db_operation('delete_tutorial', array($id,$user), array('@check_valid'), $st);
    }
    public function change_permalink($perma,$tut,$user,&$st){
        return $this->db_operation('change_tutorial_permalink', array($perma,$tut,$user), array('@change_status'), $st);
    }
    public function permalink_changed($id){
        $cache = new CacheXcache();
        if($cache->cache_isset('tpc:'.$id)){
            return $cache->get('tpc:'.$id);
        }
        $data = $this->runSP('tutorial_permalink_changes', array($id), array());
        if(isset($data[0][0]['change_amount'])){
            $cache->set('tpc:'.$id,$data[0][0]['change_amount']);
            return $data[0][0]['change_amount'];
        }
        return '1';
    }
}