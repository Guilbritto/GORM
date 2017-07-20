<?php
namespace GORM;
use PDO;
trait Finder{
    /**
     * Executa a busca do Select que estiver na variavel this
     *
     * @return Collection
     */
    public function select(){
        $cls = get_called_class();
        $this->makeSelect();
        $stmt = $this::getConnection()->prepare($this->configuration['sql']);
        $stmt->execute();
        $line = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $collection = new \GORM\Collection\Collection();
        foreach ($line as $key => $value) {
             $cls = new $cls();
             $cls->load($value);
             $collection->add($cls);
        }
        return $collection;
    }
    
   
}