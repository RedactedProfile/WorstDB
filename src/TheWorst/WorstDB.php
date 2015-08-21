<?php

namespace TheWorst;

class WorstDB {

  /**
   * @var string
   */
  private $path;

  public function __construct($path = './') {
    $this->path = $path;
  }

  /**
   * deletes key and ttl file
   * @param string $key
   * @return bool
   */
  public function delete($key) {
    try {
      unlink($key . "_ttl");

    } catch(\ErrorException $e) {
      error_log($e->getMessage());
    }
    try {
      unlink($key);
    } catch(\ErrorException $e) {
      error_log($e->getMessage());
    }

    return true;
  }

  /**
   * lazy pruner
   * @param string $key
   * @return bool
   */
  private function lazyCheck($key) {
    if(file_exists($this->path . $key . "_ttl")) {
      try {
        if(strtotime("now") > (int)file_get_contents($this->path . $key . "_ttl"))  {
          $this->delete($key);
          return false;
        }
      } catch (\ErrorException $e) {
        error_log($e->getMessage());
        return false;
      }
    }

    return file_exists($this->path . $key);
  }

  /**
   * if key exists, attach ttl file
   * @param string $key
   * @param int $ttl
   * @return bool
   */
  public function ttl($key, $ttl) {
    if($this->lazyCheck($key)) {
      try {
        $bytes = file_put_contents($this->path . $key . "_ttl", (string)strtotime(sprintf("%d seconds", $ttl)));
        return $bytes === 0 ? true : false;
      } catch(\ErrorException $e) {
        error_log($e->getMessage());
        return false;
      }
    } else {
      return false;
    }
  }

  /**
   * cheaply checks to see if db file exists
   * @param string $key
   * @return bool
   */
  public function exists($key) {
    return $this->lazyCheck($key);
  }

  /**
   * gets data from db file
   * @param string $key
   * @return string
   */
  public function get($key) {
    if($this->exists($key))
      try {
        return file_get_contents($this->path . $key);
      } catch (\ErrorException $e) {
        error_log($e->getMessage());
        return "";
      }
    else  {
      return "";
    }
  }

  /**
   * create/overwrite key, optionally set a ttl
   * @param string $key
   * @param string $value
   * @param int $ttl
   * @return bool
   */
  public function set($key, $value, $ttl = 0) {
    try {
      $ret = file_put_contents($this->path . $key, $value) > 0 ? true : false;
    } catch(\ErrorException $e) {
      error_log($e->getMessage());
      $ret = false;
    }

    if($ret && $ttl > 0)
      $this->ttl($key, $ttl);

    return $ret;
  }

}
