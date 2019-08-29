<?php
class Users extends MyDatabase {
    use SubjectTrait;
    private $cached;
    public function __construct(){
        $this->cached = new CacheXcache();
    }
    public function common_loggedin_data($user){
        if($this->cached->cache_isset('u:'.$user)){
            return $this->cached->get('u:'.$user);
        }
        $dbd = $this->runSP('loggedin_user_data',array($user),array());
        if(isset($dbd[0][0]) && count($dbd[0][0]) > 0){
            $this->cached->set('u:'.$user,$dbd[0][0]);
            $this->cached->set('un:'.$dbd[0][0]['un'],$dbd[0][0]['id']);
            return $dbd[0][0];
        }
        return array();
    }
    public function username_data($user){
        if($this->cached->cache_isset('un:'.$user) && $this->cached->cache_isset('u:'.$this->cached->get('un:'.$user))){
            return $this->cached->get('u:'.$this->cached->get('un:'.$user));
        }
        $data = [];
        $dbd = $this->runSP('small_data_by_username',array($user),array());
        if(isset($dbd[0][0]) && count($dbd[0][0]) > 0){
            $this->cached->set('un:'.$user,$dbd[0][0]['id']);
            $this->cached->set('u:'.$dbd[0][0]['id'], $dbd[0][0]);
            return $dbd[0][0];
        }
    	return $data;
    }
    public function countries(){
        if($this->cached->cache_isset('country')){
            return $this->cached->get('country');
        }
        $c = $this->runSP('fetch_countries',array(),array());
        $this->cached->set('country',$c[0]);
        return $c[0];
    }
    public function get_single_country($id){
        $c = $this->countries();
        foreach($c as $key => $val){
            if($val['id']===$id){
                return $val['name'];
                break;
            }
        }
        return '';
    }
    public function edit1($id,$fn,$ln){
        $this->db_operation('edit_user1', array($id,$fn,$ln), array(),$st);
        if($st){
            if($this->cached->cache_isset('u:'.$id)){
                $data = $this->cached->get('u:'.$id);
                $data['fn'] = $fn;
                $data['ln'] = $ln;
                $this->cached->set('u:'.$id,$data);
            }
        }
        return $st;
    }
    public function edit2($id,$about){
        $this->db_operation('edit_user2', array($id,$about), array(),$st);
        if($st){
            if($this->cached->cache_isset('u:'.$id)){
                $data = $this->cached->get('u:'.$id);
                $data['am'] = $about;
                $this->cached->set('u:'.$id,$data);
            }
        }
        return $st;
    }
    public function edit3($id,$wb,$fb,$tw){
        $this->db_operation('edit_user3', array($id,$wb,$fb,$tw), array(),$st);
        if($st){
            if($this->cached->cache_isset('u:'.$id)){
                $data = $this->cached->get('u:'.$id);
                $data['mu'] = $wb;
                $data['fb'] = $fb;
                $data['tw'] = $tw;
                $this->cached->set('u:'.$id,$data);
            }
        }
        return $st;
    }
    public function edit4($id,$pic,&$oldpic){
        $this->db_operation('edit_user4', array($id,$pic), array(),$st);
        if($st){
            if($this->cached->cache_isset('u:'.$id)){
                $data = $this->cached->get('u:'.$id);
                $oldpic = $data['pc'];
                $data['pc'] = $pic;
                $this->cached->set('u:'.$id,$data);
            }
        }
        return $st;
    }
    public function edit5($id,$user){
        return $this->db_operation('edit_user5', array($id,$user), array('@cexists'),$st);
    }
    public function edit6($id,$pic,&$oldpic){
        $this->db_operation('edit_user6', array($id,$pic), array(),$st);
        if($st){
            if($this->cached->cache_isset('u:'.$id)){
                $data = $this->cached->get('u:'.$id);
                $oldpic = $data['bn'];
                $data['bn'] = $pic;
                $this->cached->set('u:'.$id,$data);
            }
        }
        return $st;
    }
    public function edit7($id,$latlong){
        $this->db_operation('edit_user7',array($id,$latlong),array(),$st);
        if($st){
            if($this->cached->cache_isset('u:'.$id)){
                $data = $this->cached->get('u:'.$id);
                $data['ll'] = $latlong;
                $this->cached->set('u:'.$id,$data);
            }
        }
        return $st;
    }
    public function edit8($id,$pw,$salt){
        $this->db_operation('edit_user8',array($id,$pw,$salt),array(),$st);
        if($st){
            if($this->cached->cache_isset('u:'.$id)){
                $data = $this->cached->get('u:'.$id);
                $data['pw'] = $pw;
                $data['sl'] = $salt;
                $this->cached->set('u:'.$id,$data);
            }
        }
        return $st;
    }
    public function edit9($id,$skill){
        $this->db_operation('edit_user9',array($id,$skill),array(),$st);
        if($st){
            if($this->cached->cache_isset('u:'.$id)){
                $data = $this->cached->get('u:'.$id);
                $data['sk'] = $skill;
                $this->cached->set('u:'.$id,$data);
            }
        }
        return $st;
    }
    public function user_notifications($id){
        return $this->runSP('user_notifications_list',array($id),array());
    }
    public function email_exists($email){
        return $this->runSP('email_exists',array($email),array());
    }
    public function follow_unfollow($id,$uid){
        $res = $this->db_operation('follow_unfollow',array($id,$uid),array('@fn1','@fn2','@sts'),$st);
        if($st){
            if(isset($res[0][2]) && $res[0][2]==='1'){
                if($this->cached->cache_isset('u:'.$id)){
                    $data = $this->cached->get('u:'.$id);
                    $data['fo'] = $res[0][0];
                    $this->cached->set('u:'.$id,$data);
                }
                if($this->cached->cache_isset('u:'.$uid)){
                    $data = $this->cached->get('u:'.$uid);
                    $data['fw'] = $res[0][1];
                    $this->cached->set('u:'.$uid,$data);
                }
            }
        }
        return $st;
    }
    public function users_following($id){
        if($this->cached->cache_isset('u:'.$id)){
            $data = $this->cached->get('u:'.$id);
            if(!empty($data['fo'])){
                $fo = explode(',',$data['fo']);
                $nocache = [];
                $follow = [];
                foreach($fo as $val){
                    if($this->cached->cache_isset('u:'.$val)){
                        array_push($follow,$this->cached->get('u:'.$val));
                    } else {
                        array_push($nocache,$val);
                    }
                }
                unset($val);
                $merged = [];
                if(!empty($nocache)){
                    $dbd = $this->fetch_users_by_id(implode(',', $nocache));
                    return array_merge($follow,$dbd);
                }
                return $follow;
            }
            return [];
        }
        return [];
    }
    public function users_followers($id){
        if($this->cached->cache_isset('u:'.$id)){
            $data = $this->cached->get('u:'.$id);
            if(!empty($data['fw'])){
                $fo = explode(',',$data['fw']);
                $nocache = [];
                $follow = [];
                foreach($fo as $val){
                    if($this->cached->cache_isset('u:'.$val)){
                        array_push($follow,$this->cached->get('u:'.$val));
                    } else {
                        array_push($nocache,$val);
                    }
                }
                unset($val);
                $merged = [];
                if(!empty($nocache)){
                    $dbd = $this->fetch_users_by_id(implode(',', $nocache));
                    return array_merge($follow,$dbd);
                }
                return $follow;
            }
            return [];
        }
        return [];
    }
    public function fetch_users_by_id($id){
        $data = $this->runSP('users_list_by_id',array($id),array());
        if(isset($data[0])){
            return $data[0];
        }
        return [];
    }
}