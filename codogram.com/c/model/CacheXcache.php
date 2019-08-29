<?php
class CacheXcache {
	public function set($key,$val) {
        xcache_set($key, $val);
    }
    public function get($key) {
        return xcache_get($key);
    }
    public function cache_isset($key) {
        return xcache_isset($key);
    }
    public function cache_unset($key) {
        xcache_unset($key);
    }
    public function incr($key){
        xcache_inc($key);
    }
    public function decr($key){
        xcache_dec($key);
    }
    public function set_time($key,$val,$time){
        xcache_set($key,$val,$time);
    }
}