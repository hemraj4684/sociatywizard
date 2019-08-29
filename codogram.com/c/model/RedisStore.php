<?php
class RedisStore {
	public $redis;
	public function __construct(){
		$this->redis = new Redis();
		$this->redis->connect('127.0.0.1', 6379);
		$this->redis->auth('300b538aec17e0b0247b54bcef3ce28d31e9207947b9714a9eafcd62042f6402');
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
	public function set_hash($key,array $val){
		return $this->redis->hMset($key,$val);
	}
	public function get_all_hash($key){
		return $this->redis->hGetAll($key);
	}
	public function key_exists($key){
		return $this->redis->exists($key);
	}
	public function redis_multi(){
		$this->redis->multi();
	}
	public function redis_exec(){
		$this->redis->exec();
	}
	public function set_sort($key,$pos,$val){
		return $this->redis->zAdd($key,$pos,$val);
	}
	public function get_sorted_list($key,$st,$en){
		return $this->redis->zRange($key,$st,$en);
	}
	public function remove_sorted_list($key,$val){
		return $this->redis->zRem($key,$val);
	}
	public function count_zsort($key,$st,$en){
		return $this->redis->zCount($key,$st,$en);
	}
	public function incr($key){
		$this->redis->incr($key);
	}
}