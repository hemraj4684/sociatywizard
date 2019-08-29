<?php
$st = microtime(true);
?>
<?php
require 'c/init.php';
spl_autoload_register('loading_classes');
function loading_classes($c){
    if(file_exists(APP_PATH.'controllers/'.$c.'.php')){
        require APP_PATH.'controllers/'.$c.'.php';
    } else if(file_exists(APP_PATH.'model/'.$c.'.php')){
        require APP_PATH.'model/'.$c.'.php';
    }
}
$purl = parse_url(URI);
$pquery = '';
if(isset($purl['query'])){
$pquery = $purl['query'];
}

if(isset($purl['path'])){
$exuri = explode('/', $purl['path']);
$curi = count($exuri);
if($curi>1){
if(!empty($exuri[1])){
define('USERURL', $exuri[1]);
    function run_2($uri,$pquery){
        switch($uri){
            case 'search':
                $class = new SearchController();
                return $class->search($pquery);
                break;
            case 'login':
                $class = new UserLoginRegister();
                return $class->login();
                break;
            case 'registration':
                $class = new UserLoginRegister();
                return $class->register();
                break;
            case preg_match('/^[a-zA-Z\d]+[\w.-]*[a-zA-Z\d]$/', USERURL)===1 || ctype_digit(USERURL):
                $class = new MyAccountController();
                $class->profile();
                break;
            default:
                return page_404();
                break;
        }
    }
    function run_3($uri,$u2){
        if($u2==='tutorials' && strlen($uri) < 110){
            $upath = pathinfo($uri);
            if(isset($upath['extension'],$upath['filename']) && preg_match('/^[a-zA-Z\d]+[\w.-]*[a-zA-Z\d]$/',$upath['filename'])===1){
                $exext = explode('?',$upath['extension']);
                if(isset($exext[0]) && $exext[0]==='tutorial'){
                    $class = new FrontEndController();
                    return $class->public_tutorial($upath['filename']);
                }
            }
            return page_404();
        } else if($u2==='code' && $uri==='add-new-tutorial'){
            $class = new MyAccountController();
            return $class->add_new_tutorial();
        } else if($u2==='user') {
            switch($uri){
                case 'notifications':
                    $class = new MyAccountController();
                    return $class->user_notifications();
                case 'edit_profile':
                    $class = new MyAccountController();
                    return $class->edit_profile();
                    break;
                default:
                    return page_404();
                    break;
            }
        } else if($u2==='somebody'){
            if($uri==='forgot-password'){
                $class = new MyAccountController();
                return $class->forgot_password();
            } else {
                return page_404();
            }
        } else {
            return page_404();
        }
    }
    function page_404(){
        $class = new PageNotFound();
        return $class->run();
    }
    function run_4($first,$uri,$uri2){
        if(ctype_digit($uri2) && $first==='user'){
            switch($uri){
                case 'edit-tutorial':
                    $class = new MyAccountController();
                    return $class->edit_tutorial($uri2);
                    break;
                case 'add-block':
                    $class = new MyAccountController();
                    return $class->add_block($uri2);
                    break;
                default:
                    return page_404();
                    break;
            }
        }
        return page_404();
    }
    function run_5($one,$two,$three,$four){
         if($one==='u' && $two==='verify' && ctype_alnum($three) && ctype_digit($four) && strlen($three)===128){
            $class = new MyAccountController();
            return $class->verify_forgot_password($three,$four);
        }
        return page_404();
    }
    switch($curi){
        case 2:
            run_2(USERURL,$pquery);
            break;
        case 3:
            run_3($exuri[2],$exuri[1]);
            break;
        case 4:
            run_4($exuri[1],$exuri[2],$exuri[3]);
            break;
        case 5:
            run_5($exuri[1],$exuri[2],$exuri[3],$exuri[4]);
            break;
        default:
            return page_404();
            break;
    }
}
}
}
$en = microtime(true);
?>
<script>
console.log(<?=$en-$st?>);
</script>