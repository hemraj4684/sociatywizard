<?php
require '../c/init.php';
spl_autoload_register('loading_classes');
function loading_classes($c){
    if(file_exists('../'.APP_PATH.'controllers/'.$c.'.php')){
        require '../'.APP_PATH.'controllers/'.$c.'.php';
    } else if(file_exists('../'.APP_PATH.'model/'.$c.'.php')){
        require '../'.APP_PATH.'model/'.$c.'.php';
    }
}