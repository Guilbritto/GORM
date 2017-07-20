<?php
namespace GORM\Collection;
use Exception, IteratorAggregate;
class ECollectionKeyInUse extends Exception {

  public function __construct($key){
    parent::__construct('Key ' . $key . ' already exists in collection');
  }
}

class ECollectionKeyInvalid extends Exception {

  public function __construct($key){
    parent::__construct('Key ' . $key . ' does not exist in collection');
  }
}
class Collection implements IteratorAggregate {

  
  private $data = array();

  
  public function getIterator(){
    return new CollectionIterator($this);
  }

  
  public function add($item, $key = null){
    if ($key === null){
      // key is null, simply insert new data
      $this->data[] = $item;
    }
    else {
      // key was specified, check if key exists
      if (isset($this->data[$key]))
        throw new ECollectionKeyInUse($key);
      else
        $this->data[$key] = $item;
    }
  }
  public function get($key){
    if (isset($this->data[$key]))
      return $this->data[$key];
    else
      throw new ECollectionKeyInvalid($key);
  }
  public function remove($key){
    // check if key exists
    if (!isset($this->data[$key]))
      throw new ECollectionKeyInvalid($key);
    else
      unset($this->data[$key]);
  }

  public function getAll(){
    return $this->data;
  }
  public function keys(){
    return array_keys($this->data);
  }

  public function length(){
    return count($this->data);
  }

  public function clear(){
    $this->data = array();
  }

  public function exists($key){
    return isset($this->data[$key]);
  }
}
