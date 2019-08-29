<?php
class EditingProfile extends AppController {
    public function form1($fn,$ln,$token){
        if($this->check_token('E1',$token)){
            if($this->forms_validation_start()){
                if(!empty(trim($fn))){
                    if(strlen($fn)<31){
                        if(!empty(trim($ln))){
                            if(strlen(trim($ln))<31){
                                $result = $this->model->edit1($this->user_id,$fn,$ln);
                                if($result){
                                    return array('success'=>'ok');
                                } else {
                                    return array('error'=>'*Something went wrong. Please refresh the page and try again*');
                                }
                            } else {
                                return array('error'=>'*Lastname should be maximum 30 characters long*');
                            }
                        } else {
                            return array('error'=>'*Please Enter Lastname*');
                        }
                    } else {
                        return array('error'=>'*Firstname should be maximum 30 characters long*');
                    }
                } else {
                    return array('error'=>'*Please Enter Firstname*');
                }
            } else {
                return array('error'=>'login');
            }
        } else {
            return array('error'=>'token');
        }
    }
    public function form2($about,$token){
        if($this->check_token('E1',$token)){
            if($this->forms_validation_start()){
                if(strlen($about)<251){
                    $result = $this->model->edit2($this->user_id,$about);
                    if($result){
                        return array('success'=>'ok');
                    } else {
                        return array('error'=>'*Something went wrong. Please refresh the page and try again*');
                    }
                } else {
                    return array('error'=>'*Please enter maximum 250 characters*');
                }
            } else {
                return array('error'=>'login');
            }
        } else {
            return array('error'=>'token');
        }
    }
    public function form3($wb,$fb,$tw,$token){
        if($this->check_token('E1',$token)){
            if($this->forms_validation_start()){
                if(strlen($wb)<101){
                    if(empty($wb) || filter_var($wb,FILTER_VALIDATE_URL)){
                        if(strlen($fb)<51){
                            if(strlen($tw)<16){
                                $result = $this->model->edit3($this->user_id,$wb,$fb,$tw);
                                if($result){
                                    return array('success'=>'ok');
                                } else {
                                    return array('error'=>'*Something went wrong. Please refresh the page and try again*');
                                }
                            } else {
                                return array('error'=>'*Twitter username cannot be greater than 15 characters*');
                            }
                        } else {
                            return array('error'=>'*Facebook username cannot be greater than 50 characters*');
                        }
                    } else {
                        return array('error'=>'*Please enter correct website url*');
                    }
                } else {
                    return array('error'=>'*Please enter maximum 100 characters for website*');
                }
            } else {
                return array('error'=>'login');
            }
        } else {
            return array('error'=>'token');
        }
    }
    public function form4($pic,$token){
        if($this->check_token('E1',$token)){
            if($this->forms_validation_start()){
                if(isset($pic['name'],$pic['size']) && $pic['error'] === 0){
                    if($pic['size']>0 && ($pic['size']/1024) < 2048){
                    $info = pathinfo($pic['name']);
                    $ftype = array('jpg','png');
                    if(isset($info['extension']) && in_array(strtolower($info['extension']), $ftype)){
                        @$getimg = getimagesize($pic['tmp_name']);
                        if(isset($getimg[0],$getimg[1],$getimg['mime'])){
                            if($getimg[0]>=500){
                                if($getimg['mime']==='image/png' || $getimg['mime']==='image/jpeg'){
                                    $newfile = sha1(mt_rand().time().$pic['name'].SALT).'.'.$info['extension'];
                                    $nwidth = 500;
                                    $nheight=($getimg[1]/$getimg[0])*$nwidth;
                                    $image_res = false;
                                    $result = $this->model->edit4($this->user_id,$newfile,$oldpic);
                                    if($result){
                                        if(!empty($oldpic)){if(file_exists('../dp/user-profile-pictures/'.$oldpic)){unlink('../dp/user-profile-pictures/'.$oldpic);}}
                                        switch($getimg['mime']){
                                        case 'image/png':
                                            $image_res =  imagecreatefrompng($pic['tmp_name']); break;
                                        case 'image/jpeg':
                                            $image_res = imagecreatefromjpeg($pic['tmp_name']); break;
                                        }
                                        $tmpimg=imagecreatetruecolor($nwidth,$nheight);
                                        imagealphablending($tmpimg,false);
                                        imagesavealpha($tmpimg,true);
                                        imagecopyresampled($tmpimg,$image_res,0,0,0,0,$nwidth,$nheight,$getimg[0],$getimg[1]);
                                        $newthumb = '../dp/user-profile-pictures/'.$newfile;
                                        switch($getimg['mime']){
                                            case 'image/png':
                                                imagepng($tmpimg,$newthumb, 9);
                                                break;
                                            case 'image/jpeg':
                                                imagejpeg($tmpimg,$newthumb, 90);
                                                break;
                                        }
                                        imagedestroy($tmpimg);
                                        imagedestroy($image_res);
                                        return array('success'=>$newfile);
                                    } else {
                                        return array('error'=>'*Something went wrong. Please refresh the page and try again*');
                                    }
                                } else {
                                    return array('error'=>'*Invalid Image. Only png and jpg are allowed*');
                                }
                            } else {
                                return array('error'=>'*Image width must be atleast 500px*');
                            }
                        } else {
                            return array('error'=>'*Invalid Image*');
                        }
                    } else {
                        return array('error'=>'*Invalid Image. Only png and jpg are allowed*');
                    }
                    } else {
                        return array('error'=>'*Image must be under 2MB*');
                    }
                } else {
                    return array('error'=>'*Invalid Image. Max file size is 2MB*');
                }
            } else {
                return array('error'=>'login');
            }
        } else {
            return array('error'=>'token');
        }
    }
    public function form5($user,$token){
        if($this->check_token('E1',$token)){
            if($this->forms_validation_start()){
                if(!ctype_digit($user)){
                    if(strlen($user)>2){
                        if(strlen($user)<31){
                            if(preg_match('/^[a-zA-Z\d]+[\w.-]*[a-zA-Z\d]$/',$user)===1){
                                if(!in_array($user,array('user','code','login','registration','tutorials','search','ajax','assets','index.php','web.php','somebody','xcache-admin'))){
                                    $result = $this->model->edit5($this->user_id,$user);
                                    if(isset($result[0][0]) && $result[0][0]==='2'){
                                        $cache = new CacheXcache();
                                        if($cache->cache_isset('u:'.$this->user_id)){
                                            $data = $cache->get('u:'.$this->user_id);
                                            if($cache->cache_isset('un:'.$data['un'])){
                                                $cache->cache_unset('un:'.$data['un']);
                                            }
                                            $cache->set('un:'.strtolower($user),$this->user_id);
                                            $data['un'] = strtolower($user);
                                            $cache->set('u:'.$this->user_id,$data);
                                        }
                                        return array('success'=>'ok');
                                    } else {
                                        return array('error'=>'*Username not avaliable*');
                                    }
                                } else {
                                    return array('error'=>'*Username not avaliable*');
                                }
                            } else {
                                return array('error'=>'*Only Alphanumeric, dashes, underscores and dots. No special characters at the end or begining*');
                            }
                        } else {
                            return array('error'=>'*Maximum length for username is 30 characters*');
                        }
                    } else {
                        return array('error'=>'*Please enter minimum 3 characters*');
                    }
                } else {
                    return array('error'=>'*Username cannot be a number*');
                }
            } else {
                return array('error'=>'login');
            }
        } else {
            return array('error'=>'token');
        }
    }
    public function form6($pic,$token){
        if($this->check_token('E1',$token)){
            if($this->forms_validation_start()){
                if(isset($pic['name'],$pic['size']) && $pic['error'] === 0){
                    if($pic['size']>0 && ($pic['size']/1024) < 2048){
                    $info = pathinfo($pic['name']);
                    $ftype = array('jpg','png');
                    if(isset($info['extension']) && in_array(strtolower($info['extension']), $ftype)){
                        @$getimg = getimagesize($pic['tmp_name']);
                        if(isset($getimg[0],$getimg[1],$getimg['mime'])){
                            if($getimg[0]>=1000 && $getimg[1]>=250){
                                if($getimg['mime']==='image/png' || $getimg['mime']==='image/jpeg'){
                                    $newfile = md5(mt_rand().time().$pic['name'].SALT).'.'.$info['extension'];
                                    $result = $this->model->edit6($this->user_id,$newfile,$oldpic);
                                    $nwidth = 1000;
                                    $nheight=($getimg[1]/$getimg[0])*$nwidth;
                                    $image_res = false;
                                    if($result){
                                        if(!empty($oldpic)){if(file_exists('../dp/user-banners/'.$oldpic)){unlink('../dp/user-banners/'.$oldpic);}}
                                        switch($getimg['mime']){
                                        case 'image/png':
                                        $image_res =  imagecreatefrompng($pic['tmp_name']); break;
                                        case 'image/jpeg':
                                        $image_res = imagecreatefromjpeg($pic['tmp_name']); break;
                                        }
                                        $tmpimg=imagecreatetruecolor($nwidth,$nheight);
                                        imagealphablending($tmpimg,false);
                                        imagesavealpha($tmpimg,true);
                                        imagecopyresampled($tmpimg,$image_res,0,0,0,0,$nwidth,$nheight,$getimg[0],$getimg[1]);
                                        $newthumb = '../dp/user-banners/'.$newfile;
                                        switch($getimg['mime']){
                                        case 'image/png':
                                        imagepng($tmpimg,$newthumb, 9);
                                        break;
                                        case 'image/jpeg':
                                        imagejpeg($tmpimg,$newthumb, 90);
                                        break;
                                        }
                                        imagedestroy($tmpimg);
                                        imagedestroy($image_res);
                                        return array('success'=>$newfile);
                                    } else {
                                        return array('error'=>'*Something went wrong. Please refresh the page and try again*');
                                    }
                                } else {
                                    return array('error'=>'*Invalid Image. Only png and jpg are allowed*');
                                }
                            } else {
                                return array('error'=>'*Banner must have atleast 1000px width and 250px height*');
                            }
                        } else {
                            return array('error'=>'*Invalid Image*');
                        }
                    } else {
                        return array('error'=>'*Invalid Image. Only png and jpg are allowed*');
                    }
                    } else {
                        return array('error'=>'*Image must be under 2MB*');
                    }
                } else {
                    return array('error'=>'*Invalid Image. Max file size is 2MB*');
                }
            } else {
                return array('error'=>'login');
            }
        } else {
            return array('error'=>'token');
        }
    }
    public function form7($token,$lat,$long){
        if($this->check_token('E1',$token)){
            if($this->forms_validation_start()){
                if(is_numeric($lat) && is_numeric($long)){
                    if($long<182 && $long>-182 && $lat>-86 && $lat <86){
                        if($this->model->edit7($this->user_id,$lat.','.$long)){
                            return array('success'=>'Location Saved Successfully');
                        } else {
                            return array('error'=>'*Something went wrong. Please refresh the page and try again*');
                        }
                    }
                }
                return array('error'=>'*Please Select A Valid Area In The Map*');
            } else {
                return array('error'=>'login');
            }
        } else {
            return array('error'=>'token');
        }
    }
    public function form8($old,$new,$repeat,$token){
        if($this->check_token('E1',$token)){
            if($this->forms_validation_start()){
                if(!empty(trim($old))){
                    if(!empty($new)){
                        if(!empty($repeat)){
                            if($this->password_length($old)){
                                if($this->password_length($new)){
                                    if($new===$repeat){
                                        if($old!==$new){
                                            $salt = md5(SALT.time().mt_rand());
                                            $hash = hash('sha256', $new.$salt);
                                            if($this->model->edit8($this->user_id,$hash,$salt)){
                                                return array('success'=>'ok');
                                            } else {
                                                return array('error'=>'*Something went wrong. Please refresh the page and try again*');
                                            }
                                        } else {
                                            return array('error'=>'*Old And New Passwords Are Same*');
                                        }
                                    } else {
                                        return array('error'=>'*Passwords Do Not Match*');
                                    }
                                } else {
                                    return array('error'=>'*New Password Length Should Be Minimum 5 Characters*');
                                }
                            } else {
                                return array('error'=>'*Incorrect Old Password*');
                            }
                        } else {
                            return array('error'=>'*Please Enter Password Again*');
                        }
                    } else {
                        return array('error'=>'*Please Enter New Password*');
                    }
                } else {
                    return array('error'=>'*Please Enter Correct Old Password*');
                }
            } else {
                return array('error'=>'login');
            }
        } else {
            return array('error'=>'token');
        }
    }
    public function form9($token,array $skill){
        if($this->check_token('E1',$token)){
            if($this->forms_validation_start()){
                $valid = true;
                $collect = '';
                if(count($skill)<16){
                    foreach($skill as $val){
                        if(!ctype_digit($val) || $val==='0' || !$this->valid_subject($val)){
                            $valid = false;
                            break;
                        }
                        $collect .= $val.',';
                    }
                    unset($val);
                    if($valid){
                        $collect = trim($collect,',');
                        if($this->model->edit9($this->user_id,$collect)){
                            return array('success'=>'ok');
                        } else {
                            return array('error'=>'*Something went wrong. Please refresh the page and try again*');
                        }
                    } else {
                        return array('error'=>'*Please select valid skills*');
                    }
                } else {
                    return array('error'=>'*You can select maximum 15 skills*');
                }
            } else {
                return array('error'=>'login');
            }
        } else {
            return array('error'=>'token');
        }
    }
    public function follow_unfollow($token,$id){
        if($this->forms_validation_start()){
            if($this->check_token('E1',$token)){
                if(ctype_digit($id) && $id!=='0'){
                    if($id !== $this->user_id){
                        $entry = $this->model->follow_unfollow($this->user_id,$id);
                        if($entry){
                            return array('success'=>'ok');
                        } else {
                            return array('error'=>'*Something went wrong. Please refresh the page and try again*');
                        }
                    } else {
                        return array('error'=>'*You cannot follow yourself*');
                    }
                } else {
                    return array('error'=>'*Invalid user*');
                }
            } else {
                return array('error'=>'Token error. Please refresh the page.');
            }
        } else {
            return array('error'=>'Login to follow');
        }
    }
}