<?php
namespace GORM\Collection;
use Iterator;
class CollectionIterator implements Iterator {
  /**
   * Essa é nossa collection class
   */
  private $Collection = null;
  /**
   * Current index
   */
  private $currentIndex = 0;
  /**
   * Keys in collection
   */
  private $keys = null;

  /**
   * Collection iterator constructor
   *
   */
  public function __construct(Collection $Collection){
    // assign collection
    $this->Collection = $Collection;
    // assign keys from collection
    $this->keys = $Collection->keys();
  }

  /**
   * Implementação do método
   *
   * Esse metodo retorna o item atual na coleção em currentIndex.
   */
  public function current(){
    return $this->Collection->get($this->key());
  }

  /**
   * Get current key
   *
   */
  public function key(){
    return $this->keys[$this->currentIndex];
  }

  /**
   * Move to next idex
   *
   * Esse método adiciona currentIndex em um.
   */
  public function next(){
    ++$this->currentIndex;
  }

  /**
   * Rewind
   *
   * Esse metodo reseta o currentIndex, fazendo-o tornar zero
   */
  public function rewind(){
    $this->currentIndex = 0;
  }

  /**
   * Verifica se o currentIndex é valido
   *
   * Esse método verifica se o currentIndex é valido pelas chaves do arrays
   */
  public function valid(){
    return isset($this->keys[$this->currentIndex]);
  }
}
