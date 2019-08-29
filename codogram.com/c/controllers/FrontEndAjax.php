<?php
class FrontEndAjax extends AppController {
    use TimezoneConverter{
        TimezoneConverter::__construct as private __tzoConstruct;
    }
    public function __construct(){
        $this->__tzoConstruct();
        parent::__construct();
    }
    public function like($token){
        if($this->check_token('VPT',$token)){
            if($this->forms_validation_start()){
                $ref = $this->last_refer();
                if(isset($ref['path'])){
                    $lref = explode('/', $ref['path']);
                    if(count($lref) === 3){
                        $upath = pathinfo($lref[2]);
                        if(isset($upath['extension'],$upath['filename']) && strlen($upath['filename']) < 101 && preg_match('/^[a-zA-Z\d]+[\w.-]*[a-zA-Z\d]$/',$upath['filename'])===1){
                            $this->model = new FrontEndModel();
                            $this->data = $this->model->create_like($this->user_id,$upath['filename'],$st);
                            if($st && isset($this->data[0][4])){
                                if($this->data[0][0]==='1'){
                                    $cache = new CacheXcache();
                                    $cache->set('ul:'.$this->user_id,$this->data[0][1]);
                                    $cache->set('tl:'.$this->data[0][2],$this->data[0][3]);
                                    if($cache->cache_isset('t:'.$this->data[0][2])){
                                        $get = $cache->get('t:'.$this->data[0][2]);
                                        $get['vt'] = $this->data[0][4];
                                        $cache->set('t:'.$this->data[0][2], $get);
                                    }
                                }
                                return array('success'=>'ok','data_value'=>$this->data[0][4]);
                            }
                        }
                    }
                }
                return array('error' => 'Something Went Wrong. Please Try Again.');
            } else {
                return array('error'=>'login');
            }
        } else {
            return array('error'=>'token');
        }
    }
    public function tutorial_comment($comment,$token){
        if($this->check_token('VPT',$token)){
            if($this->forms_validation_start()){
                if(!empty(trim($comment))){
                    if(strlen($comment) < 5001){
                        $ref = $this->last_refer();
                        if(isset($ref['path'])){
                            $lref = explode('/', $ref['path']);
                            if(count($lref) === 3){
                                $upath = pathinfo($lref[2]);
                                if(isset($upath['extension'],$upath['filename']) && strlen($upath['filename']) < 101 && preg_match('/^[a-zA-Z\d]+[\w.-]*[a-zA-Z\d]$/',$upath['filename'])===1){
                                    $this->model = new FrontEndModel();
                                    $result = $this->model->insert_tutorial_comment($this->user_id,$upath['filename'],$comment,$st);
                                    if($st && isset($result[0][6]) && $result[0][0]>'0'){if(empty($result[0][5])){$result[0][5]='/assets/img/default-profile.jpg';}else{$result[0][5]='/dp/user-profile-pictures/'.$this->sanitize($result[0][5]);}
                                        return '<div id="comment-item-'.$result[0][6].'" class="collection-item avatar"><a class="circle" href="/'.$this->sanitize($result[0][4]).'"><img src="'.$result[0][5].'" class="responsive-img"></a><a class="title commenter blue-grey-text text-darken-3" href="/'.$this->sanitize($result[0][4]).'">'.$this->sanitize($result[0][2]).' '.$this->sanitize($result[0][3]).'</a><p class="blue-grey-text text-darken-2 comment-time">Just Now</p><p class="comment">'.str_replace(array("\r\n","\r","\n"),"<br>",$this->sanitize($comment)).'</p><a id="remove-comment" data-id="'.$result[0][6].'" href="#!" class="secondary-content blue-grey-text text-lighten-3"><i class="mdi-navigation-close"></i></a><div class="collection" id="reply-'.$result[0][6].'"></div>'.$this->reply_area($result[0][6],0).'</div>';
                                    }
                                }
                            }
                        }
                        return 5;
                    } else {
                        return 4;
                    }
                } else {
                    return 3;
                }
            } else {
                return 2;
            }
        } else {
            return 1;
        }
    }
    public function load_comments($token){
        if($this->check_token('VPT',$token) && $this->check_valid_ajax_request()){
            $ref = $this->last_refer();
            if(isset($ref['path'])){
                $lref = explode('/', $ref['path']);
                if(count($lref) === 3){
                    $upath = pathinfo($lref[2]);
                    if(isset($upath['extension'],$upath['filename']) && strlen($upath['filename']) < 101 && preg_match('/^[a-zA-Z\d]+[\w.-]*[a-zA-Z\d]$/',$upath['filename'])===1){
                        $this->model = new FrontEndModel();
                        $this->data = $this->model->load_tutorial_comments($upath['filename']);
                        if(isset($this->data[1][0]['Cc'])){
                            echo '<h5><b><i class="mdi-editor-mode-comment"></i> Comments (<span id="tut-c-count">'.$this->valid_number($this->data[1][0]['Cc']).'</span>)</b></h5><form method="post" action="" id="comment-form"><div class="input-field"><textarea id="comment-box" name="comment" placeholder="Write your comment" class="materialize-textarea" maxlength="5000"></textarea></div><div class="input-field"><b><div class="ec-err left half red-text text-accent-3">&nbsp;</div></b><div class="left half"><button type="submit" id="comment-btn" class="btn waves-effect waves-light red accent-3 margin-b15 right">Comment</button></div></div></form></div><div class="comment-col"><div id="comments-area" class="collection">';
                            $reply = [];
                            if(!empty($this->data[0][0])){
                                foreach($this->data[0] as $k => $v){
                                    if(empty($v['dp'])){$this->data[0][$k]['dp']='/assets/img/default-profile.jpg';}else{$this->data[0][$k]['dp']='/dp/user-profile-pictures/'.$this->sanitize($v['dp']);}
                                    if(ctype_digit($v['pa'])){
                                        if(!isset($reply[$v['pa']])){
                                            $reply[$v['pa']]=[];
                                        }
                                        array_push($reply[$v['pa']], $this->data[0][$k]);
                                        unset($this->data[0][$k]);
                                    }
                                }
                                unset($v);
                                $this->data[0] = array_values($this->data[0]);
                                foreach($this->data[0] as $ke => $va){
                                    echo '<div id="comment-item-'.$va['id'].'" class="collection-item avatar"><a class="circle" href="/'.$this->sanitize($va['un']).'"><img src="'.$va['dp'].'" class="responsive-img"></a><a class="title commenter blue-grey-text text-darken-3" href="/'.$this->sanitize($va['un']).'">'.$this->sanitize($va['fn']).' '.$this->sanitize($va['ln']).'</a><p class="blue-grey-text text-darken-2 comment-time">'.$this->nice_time($va['ct']).'</p><p class="comment">'.str_replace(array("\r\n","\r","\n"),"<br>",$this->sanitize($va['cm'])).'</p>';
                                    if($va['me']===$this->user_id){
                                        echo '<a id="remove-comment" data-id="'.$va['id'].'" href="#!" class="secondary-content blue-grey-text text-lighten-3"><i class="mdi-navigation-close"></i></a>';
                                    }
                                    echo '<div class="collection" id="reply-'.$va['id'].'">';
                                    if(isset($reply[$va['id']])){
                                        $reply[$va['id']] = array_reverse($reply[$va['id']]);
                                        if($va['rc']>5){
                                            $slice = array_slice($reply[$va['id']],-2);
                                            unset($reply[$va['id']][$va['rc']-1]);
                                            unset($reply[$va['id']][$va['rc']-2]);
                                            echo '<div class="hidden-reply" id="hidden-r-'.$va['id'].'">';
                                            array_reverse($reply[$va['id']]);
                                            foreach($reply[$va['id']] as $ks => $vs){
                                                echo $this->show_reply($vs['id'], $vs['me'], $vs['dp'], $vs['fn'], $vs['ln'], $vs['un'], $vs['ct'], $vs['cm']);
                                            }
                                            unset($vs);
                                            echo '</div>';
                                            for($x=0;$x<=1;$x++){
                                                echo $this->show_reply($slice[$x]['id'],$slice[$x]['me'],$slice[$x]['dp'],$slice[$x]['fn'],$slice[$x]['ln'],$slice[$x]['un'],$slice[$x]['ct'],$slice[$x]['cm']);
                                            }
                                        } else {
                                            foreach($reply[$va['id']] as $ks => $vs){
                                                echo $this->show_reply($vs['id'], $vs['me'], $vs['dp'], $vs['fn'], $vs['ln'], $vs['un'], $vs['ct'], $vs['cm']);
                                            }
                                            unset($vs);
                                        }
                                    }
                                    echo '</div>';
                                    echo $this->reply_area($va['id'],$va['rc']);
                                    echo '</div>';
                                }
                                unset($va);
                            }
                            echo '</div></div>';
                        }
                    }
                }
            }
            echo '';
        } else {
            echo '';
        }
    }
    public function reply_area($id,$rc){
        return '<div class="row margin-b0"><a class="indigo-text text-lighten-2 right show-reply" data-id="'.$id.'" href="#">Reply</a><a href="#" data-id="'.$id.'" class="indigo-text text-lighten-2 more-reply right">Replies('.$rc.')</a></div><form method="post" class="row margin-b0 reply-form" data-reply="'.$id.'" action="" id="reply-form-'.$id.'">
        <div class="input-field"><textarea id="reply-box" name="reply" placeholder="Reply Here..." class="materialize-textarea" maxlength="5000"></textarea></div>
        <div class="input-field"><b><div class="red-text text-accent-3 rp-err-'.$id.'"></div></b><button type="submit" class="btn waves-effect waves-light red accent-3 right margin-b10 reply-btn sm-button reply-btn-'.$id.'">Reply</button><button type="button" class="btn cancel-reply waves-effect waves-light indigo lighten-2 right margin-b10 sm-button" data-id="'.$id.'">Cancel</button></div>
        </form>';
    }
    public function show_reply($id,$uid,$dp,$fn,$ln,$un,$time,$com){
        $reply = '<div id="comment-item-'.$id.'" class="collection-item avatar replies blue-grey lighten-5"><a class="circle" href="/'.$this->sanitize($un).'"><img src="'.$this->sanitize($dp).'" class="responsive-img"></a><a class="title commenter blue-grey-text text-darken-3" href="/'.$this->sanitize($un).'">'.$this->sanitize($fn).' '.$this->sanitize($ln).'</a><p class="blue-grey-text text-darken-2 comment-time">'.$this->nice_time($time).'</p><p class="comment">'.str_replace(array("\r\n","\r","\n"),"<br>",$this->sanitize($com)).'</p>';
        if($uid===$this->user_id){
        $reply .= '<a id="remove-comment" data-id="'.$id.'" href="#!" class="secondary-content blue-grey-text text-lighten-3"><i class="mdi-navigation-close"></i></a>';
        }
        $reply .= '</div>';
        return $reply;
    }
    public function remove_tutorial_comment($id,$token){
        if($this->check_token('VPT',$token)){
            if($this->forms_validation_start()){
                if(ctype_digit($id)){
                    $ref = $this->last_refer();
                    if(isset($ref['path'])){
                        $lref = explode('/', $ref['path']);
                        if(count($lref) === 3){
                            $upath = pathinfo($lref[2]);
                            if(isset($upath['extension'],$upath['filename']) && strlen($upath['filename']) < 101 && preg_match('/^[a-zA-Z\d]+[\w.-]*[a-zA-Z\d]$/',$upath['filename'])===1){
                                $this->model = new FrontEndModel();
                                $this->data = $this->model->remove_tutorial_comment($upath['filename'],$this->user_id,$id,$st);
                                if($st && isset($this->data[0][0]) && $this->data[0][0]==='1'){
                                    return array('success'=>'ok');
                                }
                            }
                        }
                    }
                }
                return array('error' => 'Something Went Wrong. Please Try Again.');
            } else {
                return array('error'=>'login');
            }
        } else {
            return array('error'=>'token');
        }
    }
    public function insert_reply($reply,$token,$cid){
        if($this->check_token('VPT',$token)){
            if($this->forms_validation_start()){
                if(!empty(trim($reply))){
                    if(strlen($reply) < 5001){
                        if(ctype_digit($cid)){
                            $ref = $this->last_refer();
                            if(isset($ref['path'])){
                                $lref = explode('/', $ref['path']);
                                if(count($lref) === 3){
                                    $upath = pathinfo($lref[2]);
                                    if(isset($upath['extension'],$upath['filename']) && strlen($upath['filename']) < 101 && preg_match('/^[a-zA-Z\d]+[\w.-]*[a-zA-Z\d]$/',$upath['filename'])===1){
                                        $this->model = new FrontEndModel();
                                        $this->data = $this->model->insert_tutorial_comment_reply($reply,$cid,$upath['filename'],$this->user_id,$st);
                                        if($st && isset($this->data[0][6]) && $this->data[0][0] === '1'){
                                            if(empty($this->data[0][6])){$this->data[0][6]='/assets/img/default-profile.jpg';}else{$this->data[0][6]='/dp/user-profile-pictures/'.$this->sanitize($this->data[0][6]);}
                                            echo $this->show_reply($this->data[0][1], $this->user_id, $this->data[0][6], $this->data[0][3], $this->data[0][4], $this->data[0][5], date('dS F Y, h:i:sa',time()), $reply);
                                            exit();
                                        }
                                    }
                                }
                            }
                        }
                        return 5;
                    } else {
                        return 4;
                    }
                } else {
                    return 3;
                }
            } else {
                return 2;
            }
        } else {
            return 1;
        }
    }
    public function load_home1($user,$tut,$token){
        if($this->check_token('VPT',$token) && $this->check_valid_ajax_request() && ctype_digit($user)){
            $this->model = new FrontEndModel();
            $this->model->incr_tut_hits($tut);
            $relate = $this->model->related_user_wise_tutorial($user,$tut);
            if(!empty($relate)){
                echo '<div><h4>More From <span class="right-author"></span></h4></div>';
                foreach($relate as $val){
                    echo '<div class="rel-u-tut"><a class="black-text" href="/tutorials/'.$this->sanitize($val['lk']).'.tutorial">'.$this->sanitize($val['tt']).'</a><p class="grey-text text-darken-1">';
                    if(strlen($val['ds']) < 151){
                        echo $this->sanitize($val['ds']);
                    } else {
                        echo substr($this->sanitize($val['ds']), 0, 150).'...';
                    }
                    echo '</p></div>';
                }
            }
            unset($val);
            echo '</div>';
            if(isset($_SESSION['last_tuts'])){
                $lasts = $this->model->load_last_tuts($_SESSION['last_tuts'],$tut);
                if(!empty($lasts)){
                    echo '<div><h4>Recently visited</h4></div>';
                    foreach($lasts as $val){
                        echo '<div class="rel-u-tut"><a class="black-text" href="/tutorials/'.$this->sanitize($val['lk']).'.tutorial">'.$this->sanitize($val['tt']).'</a><p class="grey-text text-darken-1">';
                        if(strlen($val['ds']) < 151){
                            echo $this->sanitize($val['ds']);
                        } else {
                            echo substr($this->sanitize($val['ds']), 0, 150).'...';
                        }
                        echo '</p></div>';
                    }
                    unset($val);
                }
            }
        }
    }
}