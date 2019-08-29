<?php
class SearchController extends AppController {
    public $lfn,$lun,$body='<body>';
    public function search($uri){
        parse_str($uri);
        if(!isset($query)){$query='';}
        if(!isset($find)){$find='';}
        if($find==='tutorials'){
            $this->title = 'Search tutorials related to '.$query.'  on Codogram';
        }else if($find==='people'){
            $this->title = 'Search people with names '.$query.'  on Codogram';
        }
        $this->body = '<body class="bg_color">';
        $token = $this->token('search');
        $this->useridwise_data($logs);
        $this->js = '<script src="/assets/js/search.js"></script>';
        $this->layout_header();
        $this->call_view('search',array('token'=>$token,'query'=>$query,'find'=>$find));
        $this->layout_footer();
    }
    public function search_result($txt,$what,$token){//check if special chars
        $st = microtime(true);
        if($this->check_token('search',$token) && $this->check_valid_ajax_request()){
           if(!empty($txt) && ($what === '1' || $what === '2')){
                $exp = explode(' ', $txt);
                $exp = array_unique($exp);
                $keys = $this->search_useless_keyword();
                foreach($exp as $key => $val){
                    if(empty($val) || in_array($val, $keys) || !isset($val[1]) || isset($val[12])){
                        unset($exp[$key]);
                    }
                }
                unset($val);
                $exp = array_values($exp);
                $kcount = count($exp);
                if($kcount>5){
                    usort($exp, array($this,'sort_by_length'));
                    $exp = array_slice($exp,0,5);
                }
                $imexp = implode(' ',$exp);
                if(!empty($imexp)){
                    $this->model = new SearchModel();
                    if($what==='1'){
                        $this->data = $this->model->search_tutorials_by_tags($imexp);
                    } else if($what==='2') {
                        $this->data = $this->model->search_users_by_names($imexp);
                    }
                    /*
                        now search is valid
                        if one or two keyword, search subject wise
                        ************************************************************
                                    for future use create 1 column 
                            if php searched, search its frameworks and cms
                        ************************************************************
                    */
                    /*
                    if($kcount>0){
                        if($kcount<3){
                            $exp1 = '';
                            if(isset($exp[1])){
                                $exp1 = $exp[1];
                            }
                            $this->subjects = $this->model->fetch_subject_id($exp[0],$exp1);
                            if(isset($this->subjects[1])){
                                // if primary subject id found
                                if(isset($this->subjects[0][0]['id'])){
                                    $this->data = $this->model->search_by_subject($this->subjects[0][0]['id']);
                                    var_dump($this->data);
                                } else {
                                    $cs = count($this->subjects[1]);
                                    if($cs>0){
                                        $take = [];
                                        foreach($this->subjects[1] as $key => $val){
                                            array_push($take, $val['id']);
                                        }
                                        unset($val);
                                        $this->data = $this->model->search_by_subject_secondary(implode($take,','));
                                    }
                                }
                            }
                        }
                    }*/
                    /*
                        no else bcoz 0 key present
                    */
                //}
                /*
                    $this->data = $this->model->search($imexp);
                    var_dump($this->data);
                */
                }
                require '../c/views/search_result.php';
            }
        }
        $en = microtime(true);
        $tt = substr($en-$st,0,5);
        echo "<script>var tt='".$tt."';$('.search-time-taken').html('Time taken '+tt+' seconds');</script>";
    }
    public function search_useless_keyword(){
        return array('and','the','for','you','do','to','it','on','of','off','an','by','is','if','no');
    }
    public function sort_by_length($k,$v){
        return strlen($v) - strlen($k);
    }
}