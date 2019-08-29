<?php
class Redisstore {
	public $redis;
	public function __construct(){
		// $this->redis = new Redis();
		// $this->redis->connect('127.0.0.1', 6379);
		// $this->redis->auth('300b538aec17e0b0247b54bcef3ce28d31e9207947b9714a9eafcd62042f6402');
	}
	public function set($key,$val){
		return $this->redis->set($key,$val);
	}
	public function set_timer($key,$val,$time){
		return $this->redis->setex($key,$time,$val);
	}
	public function get($key){
		return $this->redis->get($key);
	}
	public function remove_set($key){
		$this->redis->delete($key);
	}
	public function key_exist($key){
		return $this->redis->exists($key);
	}
}