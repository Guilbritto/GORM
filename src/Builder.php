<?php
namespace GORM;
use Exception;
trait Builder{
    /**
     * Metodo responsável por criar as querys de inserção no banco de dados
     *
     * @return void
     */
    public function makeInsert(){
        $sql = "INSERT INTO ";
        $this->loadTable();
        $sql .= $this->configuration['table'];
        $chave = ' (';
        $valor = ' (';
        foreach ($this as $key => $value) {
            //Se não fizer parte da variavel de configuração então ele lista as 
            //propriedades do Objeto
            if($key != 'configuration'){
                if(!empty($value)){
                    $value = addslashes($value);
                    $chave .= $key.",";
                    $valor .= "'".$value."',";
                }
            }
        }
        //Retira a ultima virgula das variaveis de valor e chave
        $chave = substr($chave,0, strlen($chave)-1);
        $chave .= ')';
        $valor = substr($valor,0, strlen($valor)-1);
        $valor .= ')';
        $sql .= $chave . " VALUES " . $valor;
        $this->configuration['sql'] = $sql;
    }
    /**
     * Gera uma query de update em um determinado Objeto, 
     * o seu where é por padrão no campo id (Primary Key), caso precise de um outro PK
     * deverá ser setado o novo pk com o metodo setPrimaryKey
     *
     * @return void
     */
    public function makeUpdate(){
        $sql = "UPDATE ";
        $this->loadTable();
        $sql .= $this->configuration['table']; 
        $sql .= " SET ";
        foreach ($this as $key => $value) {
            if($key != 'configuration'){
                if(!empty($value)){
                    $sql .= $key ."='". $value ."',";
                }
            }
        }
        $sql = substr($sql,0, strlen($sql)-1);
        $sql .= ' WHERE ';
        if(isset($this->configuration['primaryKey'])){
            $pk = $this->configuration['primaryKey'];
            // Verifica se o valor da PK é nulo ou vazio
            if (empty($this->$pk)){
                throw new Exception("Valor do campo ".$pk." não pode ser nulo ou vazio", 1);
            }else{
                $sql .= $pk ."=". $this->$pk;
            }
        }else{
            // Verifica se o valor do campo ID é nulo ou vazio
            if(empty($this->id)){
                throw new Exception("Valor do campo 'id' não pode ser nulo ou vazio", 1);
            }else{
                $sql .=  "id = ".$this->id;
            }
        }
        $this->configuration['sql'] = $sql;
    }
}