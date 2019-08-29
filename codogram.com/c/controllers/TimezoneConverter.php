<?php
trait TimezoneConverter {
	public $tzo,$nicet;
	public function __construct(){
		$this->tzo = $this->check_tzo();
	}
	public function nice_time($time){
        $tz = new DateTime($time);
        $now = new DateTime();
        if($this->tzo){
            $tz->modify($_COOKIE['tzo'].' minutes');
            $now->modify($_COOKIE['tzo'].' minutes');
            $nowt = strtotime($now->format('Y-m-d H:i:s'));
            $oldt = strtotime($tz->format('Y-m-d H:i:s'));
            $diff = $nowt - $oldt;
        	$d = round($diff/60);
        	$d1 = round($diff/3600);
        	$d2 = round($diff/86400);
        	if(0 == $d){
        		return 'Just Now';
        	} else if(60 > $d){
        		return $d.' minutes ago';
        	} else if(24 >= $d1) {
				return $d1.' hours ago';
        	} else if($d2==1) {
        		return 'Yesterday';
        	} else if(8>$d2) {
        		return $d2.' days ago';
        	} else {
        		return $tz->format('h:i:sa, d-M-Y');
        	}
        }
        return $tz->format('h:i:sa, d-M-Y').' UTC';
    }
    public function display_time($time,$format){
        $tz = new DateTime($time);
        if($this->tzo){
            $tz->modify($_COOKIE['tzo'].' minutes');
            return $tz->format($format);
        }
        return $tz->format($format).' UTC';
    }
    public function check_tzo(){
    	if(isset($_COOKIE['tzo']) && is_numeric($_COOKIE['tzo'])){
    		return true;
    	}
    	return false;
    }
}