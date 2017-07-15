<?php
namespace GORM;

trait Persistent{
    /**
     * Salva um objeto no banco de dados conforme as configurações
     * e caso a variavel lastId for true ele retorna o id da ultima inserção
     *
     * @param boolean $lastId
     * @return bool
     */
    public function save($lastId = false){
        
    }
}