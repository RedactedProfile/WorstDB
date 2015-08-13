<?php

class ShittyDB {

  public function exists($key) {
    return $this->lazyCheck($key);
  }

  // 
  public function get($key) {
    if($this->exists($key))
      return file_get_contents($key);
    else 
      return false;
  }

  // create/overwrite key, optionally set a ttl
  public function set($key, $value, $ttl = 0) {
    $return = file_put_contents($key, $value);
    if($return && $ttl) 
      $this->ttl($key, $ttl);

    return $return;
  }

  // if key exists, create ttl file
  public function ttl($key, $ttl) {
    if($this->lazyCheck($key)) {
      return file_put_contents($key . "_ttl", strtotime($ttl . " seconds"));
    } else {
      return false;
    }
  }

  // deletes key and ttl file
  public function delete($key) {
    @unlink($key . "_ttl");
    @unlink($key);
    return true;
  }

  // lazy pruner
  private function lazyCheck($key) {
    if(file_exists($key . "_ttl")) {
      if(strtotime("now") > (int)file_get_contents($key . "_ttl"))  {
        $this->delete($key);
        return false;
      }
    }
    
    return file_exists($key);
  }

}
