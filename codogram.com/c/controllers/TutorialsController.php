<?php
class TutorialsController extends AppController {
    public function add(){
        if($this->check_token('ANT',$_POST['token'])){
        if($this->forms_validation_start()){
        if(!empty(trim($_POST['title']))){
        if(strlen($_POST['title']) <= 100){
        if(!empty(trim($_POST['link']))){
        if(strlen($_POST['link']) <= 100){
        if(!ctype_digit($_POST['link'])){
        if(strlen($_POST['description']) <= 1000){
        if(strlen($_POST['conclusion']) <= 1000){
        if(!empty(trim($_POST['tags']))){
        if(strlen($_POST['tags']) <= 30){
        if(isset($_POST['primary-subject']) && $this->valid_subject($_POST['primary-subject'])){
        if(isset($_POST['language'])){
        if($this->valid_subject($_POST['language'])){
        if(!empty(trim($_POST['code']))){
        if(strlen($_POST['explain']) <= 2000){
        if(strlen($_POST['heading']) <= 100){
        if(preg_match('/^[a-zA-Z\d]+[\w.-]*[a-zA-Z\d]$/',$_POST['link'])===1){
        $_POST['link'] = strtolower($_POST['link']);
        $vis='0';
        if(isset($_POST['visible'])){$vis='1';}
        $tut = new TutorialsModel();
        $rs = $tut->add_new(array($this->user_id,$_POST['title'],$_POST['description'],$_POST['link'],$vis,$_POST['heading'],$_POST['language'],$_POST['code'],$_POST['explain'],$_POST['conclusion'],$_POST['tags'],$_POST['primary-subject']),$st);
        if($st && isset($rs[0][0])){
        if($rs[0][0]==='0'){
                echo json_encode(array('error'=>'11'));
        } else {
            $cache = new CacheXcache();
            $cache->set('t:'.$rs[0][0],array('id'=>$rs[0][0],'me'=>$this->user_id,'tt'=>$_POST['title'],'ds'=>$_POST['description'],'cn'=>$_POST['conclusion'],'lk'=>$_POST['link'],'tg'=>$_POST['tags'],'ps'=>$_POST['primary-subject'],'vs'=>$vis,'vt'=>'0','tb'=>'1','du'=>$rs[0][1],'dc'=>$rs[0][1]));
            $cache->set('tp:'.$_POST['link'],$rs[0][0]);
            $cache->set('ts:'.$rs[0][0],array(array('id'=>$rs[0][2],'lg'=>$_POST['language'],'cd'=>$_POST['code'],'ex'=>$_POST['explain'],'hd'=>$_POST['heading'],'po'=>'1')));
            $cache->set('tpc:'.$rs[0][0],'0');
            if($cache->cache_isset('tuc:'.$this->user_id)){
                $cache->incr('tuc:'.$this->user_id);
                $oldb = $cache->get('ut:'.$this->user_id);
                if(empty($oldb)){
                    $cache->set('ut:'.$this->user_id,array($rs[0][0]));
                } else {
                    array_push($oldb,$rs[0][0]);
                    $cache->set('ut:'.$this->user_id,$oldb);
                }
            }
            echo json_encode(array('error'=>'13','success'=>$rs[0][0]));
        }
        } else {
                echo json_encode(array('error'=>'12'));
        }
        
        } else {
                echo json_encode(array('error'=>'10'));
        }
        } else {
                echo json_encode(array('error'=>'9'));
        }
        } else {
                echo json_encode(array('error'=>'8'));
        }
        } else {
                echo json_encode(array('error'=>'7'));
        }
        } else {
                echo json_encode(array('error'=>'14'));
        }
        } else {
                echo json_encode(array('error'=>'6'));
        }
        } else {
            echo json_encode(array('error'=>'19'));
        }
        } else {
            echo json_encode(array('error'=>'18'));
        }
        } else {
            echo json_encode(array('error'=>'17'));
        }
        } else {
         echo json_encode(array('error'=>'16'));
        }
        } else {
                echo json_encode(array('error'=>'5'));
        }
        } else {
                echo json_encode(array('error'=>'15'));
        }
        } else {
                echo json_encode(array('error'=>'4'));
        }
        } else {
                echo json_encode(array('error'=>'3'));
        }
        } else {
                echo json_encode(array('error'=>'2'));
        }
        } else {
                echo json_encode(array('error'=>'1'));
        }
        } else {
                echo json_encode(array('error'=>'49'));
        }
        } else {
                echo json_encode(array('error'=>'50'));
        }
    }
    public function update_intro(){
        if($this->check_token('EDIT',$_POST['token'])){
            if($this->forms_validation_start()){
            if(isset($_POST['primary-subject']) && $this->valid_subject($_POST['primary-subject'])){
                if (!empty(trim($_POST['title']))) {
                    if (strlen($_POST['title']) <= 100) {
                        if(strlen($_POST['description']) <= 1000) {
                            if(strlen($_POST['conclusion']) <= 1000){
                            if(!empty(trim($_POST['tags']))){
                            if(strlen($_POST['tags']) <= 30){
                            $lref = $this->last_refer();
                            if(isset($lref['path'])){
                            $ref = explode('/', $lref['path']);
                            if(count($ref) === 4) {
                                if(ctype_digit($ref[3])) {
                                    $VIS = '0';
                                    if(isset($_POST['visible'])) {
                                        $VIS = '1';
                                    }
                                    $tut = new TutorialsModel();
                                    $rs = $tut->update_main(array($_POST['title'],$_POST['description'],$VIS,$this->user_id,$ref[3],$_POST['conclusion'],$_POST['tags'],$_POST['primary-subject']), array('@udone'), $st);
                                    if($st && isset($rs[0][0]) && $rs[0][0]==='1'){
                                        $cache = new CacheXcache();
                                        if($cache->cache_isset('t:'.$ref[3]) && $cache->cache_isset('ts:'.$ref[3])){
                                            $GETC = $cache->get('t:'.$ref[3]);
                                            $GETC['tt'] = $_POST['title'];
                                            $GETC['ds'] = $_POST['description'];
                                            $GETC['cn'] = $_POST['conclusion'];
                                            $GETC['tg'] = $_POST['tags'];
                                            $GETC['ps'] = $_POST['primary-subject'];
                                            $GETC['vs'] = $VIS;
                                            $GETC['du'] = date('Y-m-d h:i:s');
                                            $cache->set('t:'.$ref[3],$GETC);
                                        }
                                        echo json_encode(array('success' => 'Saved Successfully'));
                                    } else {
                                        echo json_encode(array('error' => '*Something Went Wrong. Please Try Again.*'));
                                    }
                                } else {
                                    echo json_encode(array('error' => '*Something Went Wrong. Please Try Again.*'));
                                }
                            } else {
                                echo json_encode(array('error' => '*Something Went Wrong. Please Try Again.*'));
                            }
                            } else {
                                echo json_encode(array('error' => '*Something Went Wrong. Please Try Again.*'));
                            }
                            } else {
                                echo json_encode(array('error'=>'*Please Enter maximum 30 characters in tags*'));
                            }
                            } else {
                                echo json_encode(array('error'=>'*Please enter tags*'));
                            }
                            } else {
                                echo json_encode(array('error'=>'*Conclusion is too long. Enter maximum 1000 characters.*'));
                            }
                        } else {
                            echo json_encode(array('error' => '*Description is too long. Enter maximum 1000 characters.*'));
                        }
                    } else {
                        echo json_encode(array('error' => '*Title is too long. Enter maximum 100 characters*'));
                    }
                } else {
                    echo json_encode(array('error' => '*Please Enter Title*'));
                }
                } else {
                echo json_encode(array('error'=>'*Please Select A Primary Language*'));
                }
            }
        }
    }
    public function update_block(){
        if($this->check_token('EDIT',$_POST['token'])){
        if($this->forms_validation_start()){
        if(ctype_digit($_POST['ID'])) {
            if($this->valid_subject($_POST['language'])) {
                if(!empty(trim($_POST['code']))) {
                    if(strlen($_POST['explain']) <= 2000) {
                        if(strlen($_POST['heading']) <= 100) {
                            $lref = $this->last_refer();
                            if(isset($lref['path'])){
                            $ref = explode('/', $lref['path']);
                            if(count($ref) === 4){
                                if(ctype_digit($ref[3])) {
                                    $tut = new TutorialsModel();
                                    $result = $tut->update_block(array($_POST['ID'],$_POST['language'],$_POST['code'],$_POST['explain'],$_POST['heading'],$ref[3],$this->user_id), $st);
                                    if($st && isset($result[0][0]) && $result[0][0]==='1'){
                                        $cache = new CacheXcache();
                                        if($cache->cache_isset('t:'.$ref[3]) && $cache->cache_isset('ts:'.$ref[3])){
                                            $GETC = $cache->get('t:'.$ref[3]);
                                            $GETC['du'] = date('Y-m-d h:i:s');
                                            $tcache = $cache->get('ts:'.$ref[3]);
                                            foreach($tcache as $k => $v){
                                                if($v['id']===$_POST['ID']){
                                                    $tcache[$k]['lg']=$_POST['language'];
                                                    $tcache[$k]['cd']=$_POST['code'];
                                                    $tcache[$k]['ex']=$_POST['explain'];
                                                    $tcache[$k]['hd']=$_POST['heading'];
                                                    break;
                                                }
                                            }
                                            unset($v);
                                            $cache->set('ts:'.$ref[3],$tcache);
                                            $cache->set('t:'.$ref[3],$GETC);
                                        }
                                        echo json_encode(array('success'=>'Updated Successfully'));
                                    } else {
                                        echo json_encode(array('error' => 'Something went wrong. Please refresh the page and try again.'));
                                    }
                                } else {
                                    echo json_encode(array('error' => 'Something went wrong. Please refresh the page and try again.'));
                                }
                            } else {
                                echo json_encode(array('error' => 'Something went wrong. Please refresh the page and try again.'));
                            }
                            } else {
                            echo json_encode(array('error' => 'Something went wrong. Please refresh the page and try again.'));
                        }
                        } else {
                            echo json_encode(array('error' => 'Heading is too long. Enter maximum 100 characters'));
                        }
                    } else {
                        echo json_encode(array('error' => 'Explanation is too long. Enter maximum 2000 characters'));
                    }
                } else {
                    echo json_encode(array('error' => 'Please Write Your Code'));
                }
            } else {
                echo json_encode(array('error' => 'Please Select A Valid Language'));
            }
        } else {
            echo json_encode(array('error' => 'Something went wrong. Please refresh the page and try again.'));
        }
    }
    }
    }
    public function add_new_block(){
        if($this->check_token('nb',$_POST['ajaxToken'])){
        if($this->forms_validation_start()){
        if(isset($_POST['language'])){
        if($this->valid_subject($_POST['language'])){
        if(!empty(trim($_POST['code']))){
        if(strlen($_POST['explain']) <= 2000){
        if(strlen($_POST['heading']) <= 100){
        $lref = $this->last_refer();
        if(isset($lref['path'])){
        $ref = explode('/', $lref['path']);
        if(isset($ref[3]) && ctype_digit($ref[3])){
            $tut = new TutorialsModel();
            $result = $tut->add_block(array($ref[3],$this->user_id,$_POST['language'],$_POST['code'],$_POST['explain'],$_POST['heading']),$st);
            if($st && isset($result[0][2])){
                if($result[0][2]==='1' && isset($result[0][1],$result[0][0]) && $result[0][0]>0){
                    $cache = new CacheXcache();
                    if($cache->cache_isset('t:'.$ref[3]) && $cache->cache_isset('ts:'.$ref[3])){
                        $gcache = $cache->get('t:'.$ref[3]);
                        $gcache['tb'] = $gcache['tb']+1;
                        $tcache = $cache->get('ts:'.$ref[3]);
                        array_push($tcache, array('id'=>$result[0][0],'lg'=>$_POST['language'],'cd'=>$_POST['code'],'ex'=>$_POST['explain'],'hd'=>$_POST['heading'],'po'=>$gcache['tb']));
                        $cache->set('ts:'.$ref[3],$tcache);
                        $gcache['du'] = $result[0][1];
                        $cache->set('t:'.$ref[3],$gcache);
                    }
                    return array('success'=>$ref[3]);
                } else {
                    return array('error'=>'*You cannot add more than 20 steps per tutorial*');
                }
            } else {
                return array('error'=>'*Something went wrong. Please refresh the page and try again*');
            }
        } else {
            return array('error'=>'*Something went wrong. Please refresh the page and try again*');
        }
        } else {
            return array('error'=>'*Something went wrong. Please refresh the page and try again*');
        }
        } else {
            return array('error'=>'*Heading is too long. Enter maximum 100 characters*');
        }
        } else {
            return array('error'=>'*Explanation is too long. Enter maximum 2000 characters*');
        }
        } else {
            return array('error'=>'*Please write your code*');
        }
        } else {
            return array('error'=>'*Please Select A Valid Language*');
        }
        } else {
            return array('error'=>'*Please Select A Language*');
        }
    } else {
        return array('error'=>'login');
    }
    } else {
        return array('error'=>'token');
    }
    }
    public function remove_block($id,$token){
        if($this->check_token('EDIT',$token)){
        if($this->forms_validation_start()){
            if(ctype_digit($id)){
                $lref = $this->last_refer();
                if(isset($lref['path'])){
                    $ref = explode('/', $lref['path']);
                    if(isset($ref[3]) && ctype_digit($ref[3])){
                        $tut = new TutorialsModel();
                        $result = $tut->delete_block(array($this->user_id,$ref[3],$id),$st);
                        if($st && isset($result[0][0])){
                            if($result[0][0] === '1'){
                                $cache = new CacheXcache();
                                if($cache->cache_isset('t:'.$ref[3]) && $cache->cache_isset('ts:'.$ref[3])){
                                    $gcache = $cache->get('t:'.$ref[3]);
                                    $tcache = $cache->get('ts:'.$ref[3]);
                                    $done = 0;
                                    foreach($tcache as $k => $v){
                                        if($done===1){
                                            $tcache[$k]['po'] = $tcache[$k]['po']-1;
                                        }
                                        if($v['id']===$id){
                                            $done = 1;
                                            unset($tcache[$k]);
                                        }
                                    }
                                    $gcache['tb'] = $gcache['tb']-1;
                                    $gcache['du'] = date('Y-m-d h:i:s');
                                    $cache->set('ts:'.$ref[3],  array_values($tcache));
                                    $cache->set('t:'.$ref[3],$gcache);
                                    return array('success'=>'ok');
                                }
                            } else if($result[0][0] === '2'){
                                return array('error'=>'You cannot delete this step. There must be atleast one step every tutorial');
                            } else {
                                return array('error'=>'Something went wrong. Please refresh the page and try again');
                            }
                        } else {
                            return array('error'=>'Something went wrong. Please refresh the page and try again');
                        }
                    } else {
                        return array('error'=>'Something went wrong. Please refresh the page and try again');
                    }
                }
            } else {
                return array('error'=>'Something went wrong. Please refresh the page and try again');
            }
        } else {
        return array('error'=>'login');
        }
        } else {
        return array('error'=>'token');
        }
    }
    public function change_positions($pos,$token){
        if($this->check_token('EDIT',$token)){
        if($this->forms_validation_start()){
        $lref = $this->last_refer();
        if(isset($lref['path'])){
        $ref = explode('/', $lref['path']);
        if(isset($ref[3]) && ctype_digit($ref[3])){
        if(is_array($pos) && count($pos) < 21){
        $CP=count($pos);
        $chkunq = array_unique($pos);
        if($CP===count($chkunq)){
        $valid_count=1;
        foreach($pos as $k=>$v){
        if($v<=0 || $v>$CP || !ctype_digit($v)){
        $valid_count = 0;
        break;
        }
        }
        unset($v);
        if($valid_count===1){
        $ipos = implode(',',$pos);
        $tut = new TutorialsModel();
        $result = $tut->update_positions(array($ipos,$ref[3],$this->user_id,$CP),$st);
        if($st && isset($result[0][0],$result[0][1])){
        if($result[0][0]==='1'){
        $cache = new CacheXcache();
        if($cache->cache_isset('ts:'.$ref[3]) && $cache->cache_isset('t:'.$ref[3])){
        $tcache = $cache->get('ts:'.$ref[3]);
        $copydata = $tcache;
        $sorted = $pos;
        sort($sorted);
        $final = [];
        for($z=0;$z<$CP;$z++){
            foreach ($tcache as $key => $value) {
                if($value['po']==$pos[$z]){
                    $copydata[$key]['po'] = $sorted[$z];
                    array_push($final,$copydata[$key]);
                    break;
                }
            }
            unset($value);
        }

        $gmain = $cache->get('t:'.$ref[3]);
        $gmain['du'] = date('Y-m-d h:i:s');
        $cache->set('ts:'.$ref[3],$final);
        $cache->set('t:'.$ref[3],$gmain);
        }
        return array('success'=>'ok');
        } else if($result[0][0]==='2') {
        return array('error'=>'Something went wrong. Please refresh the page and try again');
        } else if ($result[0][0]==='3'){
        return array('error'=>'*The positions you have entered are not valid. Positions should start with 1. Maximum position value for this tutorial is '.$result[0][1].'*');
        }
        } else {
        return array('error'=>'*Something went wrong. Please refresh the page and try again');
        }
        } else {
        return array('error'=>'*The positions you have entered are not valid. Positions should start with 1. Maximum position value for this tutorial is '.$CP.'*');
        }
        } else {
        return array('error'=>'*Each step must have a unique position.*');
        }
        } else {
        return array('error'=>'*Something went wrong. Please refresh the page and try again*');
        }
        } else {
        return array('error'=>'*Something went wrong. Please refresh the page and try again*');
        }
        } else {
        return array('error'=>'*Something went wrong. Please refresh the page and try again*');
        }
        } else {
        return array('error'=>'login');
        }
        } else {
        return array('error'=>'token');
        }
    }
    public function remove_tutorial($token){
        if($this->check_token('EDIT',$token)){
        if($this->forms_validation_start()){
        $lref = $this->last_refer();
        if(isset($lref['path'])){
        $ref = explode('/', $lref['path']);
        if(isset($ref[3]) && ctype_digit($ref[3])){
        $tut = new TutorialsModel();
        $result = $tut->delete_tutorial($ref[3],$this->user_id,$st);
        if($st && isset($result[0][0]) && $result[0][0]==='1'){
        $cache = new CacheXcache();
        if($cache->cache_isset('ts:'.$ref[3]) && $cache->cache_isset('t:'.$ref[3])){
            $cache->cache_unset('ts:'.$ref[3]);
            $mcache = $cache->get('t:'.$ref[3]);
            $cache->cache_unset('t:'.$ref[3]);
            $cache->cache_unset('tp:'.$mcache['lk']);
            $cache->cache_unset('tl:'.$ref[3]);
            $UT = $cache->get('ut:'.$this->user_id);
            $arr = [];
            foreach($UT as $k=>$v){
                if($v!==$ref[3]){
                    array_push($arr, $v);
                }
            }
            unset($v);
            $cache->set('ut:'.$this->user_id,$arr);
            $cache->decr('tuc:'.$this->user_id);
        }
        return array('success'=>'ok');
        } else {
        return array('error'=>'Something went wrong. Please refresh the page and try again');
        }
        } else {
        return array('error'=>'Something went wrong. Please refresh the page and try again');
        }
        } else {
        return array('error'=>'Something went wrong. Please refresh the page and try again');
        }
        } else {
        return array('error'=>'login');
        }
        } else {
        return array('error'=>'token');
        }
    }
    public function change_permalink($link,$token){
        if($this->check_token('EDIT',$token)){
        if($this->forms_validation_start()){
        if(!empty(trim($link))){
        if(strlen($link) <= 100){
        if(!ctype_digit($link)){
        if(preg_match('/^[a-zA-Z\d]+[\w.-]*[a-zA-Z\d]$/',$link)===1){
        $link = strtolower($link);
        $lref = $this->last_refer();
        if(isset($lref['path'])){
        $ref = explode('/', $lref['path']);
        if(isset($ref[3]) && ctype_digit($ref[3])){
        $tut = new TutorialsModel();
        $result = $tut->change_permalink($link,$ref[3],$this->user_id,$st);
        if($st && isset($result[0][0]) && ctype_digit($result[0][0])){
        if($result[0][0]==='1'){
        $cache = new CacheXcache();
        if($cache->cache_isset('ts:'.$ref[3]) && $cache->cache_isset('t:'.$ref[3])){
        $gmain = $cache->get('t:'.$ref[3]);
        $gmain['du'] = date('Y-m-d h:i:s');
        $cache->cache_unset('tp:'.$gmain['lk']);
        $cache->set('tp:'.$link,$gmain['id']);
        $gmain['lk'] = $link;
        $cache->set('t:'.$ref[3],$gmain);
        }
        $cache->set('tpc:'.$ref[3],'1');
        return array('success'=>'ok');
        } else if($result[0][0]==='2') {
        return array('error'=>'*You dont have permission to change the permalink again.*');
        } else if($result[0][0]==='4'){
        return array('error'=>'*Permalink already exists*');
        }
        }
        }
        }
        return array('error'=>'*Something went wrong. Please refresh the page and try again*');
        } else {
        return array('error'=>'*Permalink can contain only alphanumeric, dashes, underscores and dots characters. No special characters at the end or begining. Minimum 2 characters.*');
        }
        } else {
        return array('error'=>'*Permalink cannot be a number*');
        }
        } else {
        return array('error'=>'*Permalink is too long. Maximum 100 characters.*');
        }
        } else {
        return array('error'=>'*Permalink cannot be empty*');
        }
        } else {
        return array('error'=>'login');
        }
        } else {
        return array('error'=>'token');
        }
    }
}