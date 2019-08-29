<?php
require '../../gun/mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;
class Registration {
	private $model;
	use Tokens;
	public function make_register($fn,$ln,$em,$pw,$country,$token){
        if($this->check_only_token('R',$token)){
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest'===strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])){
                if(!empty(trim($fn))){
                    if(strlen($fn)<31){
                        if(!empty(trim($ln))){
                            if(strlen(trim($ln))<31){
                                if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
                                    if($this->password_length($_POST['password'])){
                                        if(filter_var($country, FILTER_VALIDATE_INT, array("options" => array("min_range"=>1, "max_range"=>242)))){
                                            $salt = md5(SALT.time().mt_rand());
                                            $hash = hash('sha256', $pw.$salt);
                                            $this->model = new LoginRegister();
                                            $data = $this->model->register(array($fn,$ln,$em,$hash,$salt,$country),array('@lid','@LASTID','@the_time'),$st);
                                            if($st && isset($data[0])){
                                                if(isset($data[0][0]) && ctype_digit($data[0][0])){
                                                    return array('error'=>'Email Id already exists');
                                                } else {
                                                    $cache = new CacheXcache();
                                                    $cache->set('u:'.$data[0][1],array('id'=>$data[0][1],'fn'=>$fn,'ln'=>$ln,'un'=>$data[0][1],'em'=>$em,'pw'=>$hash,'pc'=>'','sl'=>$salt,'ac'=>'0','cn'=>$country,'am'=>'','mu'=>'','bn'=>'','ll'=>'','rt'=>$data[0][2],'fb'=>'','tw'=>'','sk'=>'','fo'=>'','fw'=>''));
                                                    $cache->set('un:'.$data[0][1],$data[0][1]);
                                                    $cache->set('tuc:'.$data[0][1],0);
                                                    $cache->set('ut:'.$data[0][1],array());
                                                    $_SESSION['user'] = $data[0][1];
                                                    $_SESSION['check'] = hash('sha256',$_SERVER['HTTP_USER_AGENT'].SALT);
                                                    setcookie('user',$data[0][1],time() + (172800),'/',null,null,TRUE);
                                                    session_regenerate_id(true);
                                                    unset($_SESSION['R']);
                                                    /*$actcode = hash('sha512',time().SALT.$data[0][1].mt_rand());
                                                    $actcode = strtolower($actcode);
                                                    $redis = new RedisStore();
                                                    $redis->set_timer('aa:'.$data[0][1],$actcode,86400);*/
                                                    $mgClient = new Mailgun('key-4cfbfc4568f17460fe1e67e33afc3d9a');
                                                    $domain = "codogram.com";
                                                    $result = $mgClient->sendMessage($domain,array(
                                                    'from' => 'Codogram <info@codogram.com>', 'to' => $em,
                                                    'subject' => 'New Registration On Codogram',
                                                    'html' => '<p><a href="http://www.codogram.com/"><img style="max-width:100%;display:block;margin:0 auto;" src="http://www.codogram.com/assets/img/codogram-email.png"></a></p><p>Welcome '.$fn.' '.$ln.',</p><h1 style="font-size:21px">Your Account Has Been Created!</h1><p>Thank you for joining Codogram.</p><p>If you have any questions, please mail us to connect@codogram.com</p>'
                                                    ));
                                                    return array('success'=>$data[0][1]);
                                                }
                                            } else {
                                                return array('error'=>'*Something went wrong. Please refresh the page and try again*');
                                            }
                                        } else {
                                            return array('error'=>'*Please Select A Country*');
                                        }
                                    } else {
                                        return array('error'=>'*Password Length Should Be Minimum 5 Characters*');
                                    }
                                } else {
                                    return array('error'=>'*Please enter correct email address*');
                                }
                            } else {
                                return array('error'=>'*Lastname should be maximum 30 characters long*');
                            }
                        } else {
                            return array('error'=>'*Please enter lastname*');
                        }
                    } else {
                        return array('error'=>'*Firstname should be maximum 30 characters long*');
                    }
                } else {
                    return array('error'=>'*Please enter firstname*');
                }
            } else {
                return array('error'=>'');
            }
        } else {
            return array('token_error'=>'refresh');
        }
	}
	public function password_length($pw){
		return (strlen($pw) > 4) ? true : false;
	}
}