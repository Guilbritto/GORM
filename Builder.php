<?php

namespace GORM;
trait Builder
{
	private static $select	= "SELECT * FROM ";
	private static $insert	= "INSERT INTO ";
	private static $where	= " WHERE ";
    private static $update  = "UPDATE ";
    private static $set     = " SET ";
    private static $inner   = " INNER JOIN ";
    private static $on      = " ON ";
    public static  $condition;
    public static  $table;
    public static  $sql;
    
    // Gera o SQL da classe carregada na Model
	public function makeInsert($class){
        $array = $class->toArray();
        $size 	= count($array);
        $i 		= 0;
    	$chave 	= "("; // irá receber o campo
        $valor 	= "("; // irá receber o valor do campo 
        
        foreach ($array as $key => $value) {
          
            $value = addslashes($value);
            if($i < ($size - 1) ){
                $chave  .= $key.", ";
                $valor  .= "'".$value."', ";
            }else{
                $chave  .= $key.") ";
                $valor  .= "'".$value."') ";
            }
        	$i++;
        }
        $retorno = ["key" => $chave, "value" => $valor ];
        self::$sql = self::$insert.self::$table." ".$retorno['key']." values ".$retorno['value'];
    }

    /**
    *   Gera SQL para select 
    */
    public function makeSelect($class){
        $class = $class->toArray();
        $temp = self::$select.self::$table;
        
        //Insere o que voce quer procurar no seu select
        if (isset(self::$condition['select'])){
            $option = self::$condition['select'];
            $temp = str_replace("*", $option, $temp);
          
        }
        //Faz um inner Join
        if (isset(self::$condition['inner'])){
            $inner = self::$condition['inner'];
            foreach ($inner as $table => $value) {
                $temp .=  self::$inner.$table.self::$on;
                foreach ($value as $key => $values) {
                    $temp .= $key."=".$values;
                }
            } 
        }
        // Monta um Where
        if (isset(self::$condition["where"])){
            $where = self::$condition['where'];
            $temp .= self::$where;
            /**
            * Percorre a primeira camada do Array e verifica 
            */
            foreach ($where as $key => $value) {
                // Faz as verificações para saber como a informação esta sendo passada
                // se for apenas array('nomedocampo'), ele pega a propriedade do objeto com este nome
                if (isset($where[0])){
                    if (is_string($value)){ 
                        $temp .= self::$table.".".$value."='".$class[$value]."'";
                    }else{
                        $temp .= self::$table.".".$value."=".$class[$value];
                    }
                // se contiver um AND ele adiciona na query
                // array("AND" => array('key' => value))
                }elseif(isset($where["AND"])){
                    $count = count($where['AND']);
                    foreach ($value as $key => $values) {
                        $i++;
                        if($i < $count){
                            if (is_string($values)){
                                $temp .= $key."='".$values."' AND ";
                            }else{
                                $temp .= $key."=".$values." AND ";
                            }
                        }else{
                            if (is_string($values)){
                                $temp .= $key."='".$values."'";
                            }else{
                                $temp .= $key."=".$values;
                            }
                        }
                    }
                // Se tiver um OR ele adiciona na query
                // array("OR" => array('key' => value))
                }elseif (isset($where["OR"])) {
                    $count = count($where['OR']);
			         $i=0;
                    foreach ($value as $key => $values) {
                        
                        $i++;
                        if($i<$count){
                            if (is_string($values)){
                                $temp .= $key."='".$values."' OR ";
                            }else{
                                $temp .= $key."=".$values." OR ";
                            }
                        }else{
                            if (is_string($values)){
                                $temp .= $key."='".$values."'";
                            }else{
                                $temp .= $key."=".$values;
                            }
                        }
                    }
                // Se tiver apenas array('key' => value) ele tambem aceita
                }else{
                    if (is_string($value)){
                        $temp .= self::$table.".".$key."='".$value."'";   
                    }else{
                        $temp .= self::$table.".".$key."=".$value;
                    }
                }
            }
        }
        ############ FIM DO WHERE ####################### 
        self::$sql = $temp;
        
    }
    
    public function makeUpdate($class){
        $class = $class->toArray();
        $temp = null;
        $id = $class['id'];
        unset($class['id']);
        foreach ($class as $key => $value) {
            $value = addslashes($value);
            if (empty($value)){
            }else{
                $temp .= " $key='$value',";
            }
        }
        $temp = substr($temp, 0, -1);
        self::$sql = self::$update.self::$table.self::$set.$temp.self::$where." id=".$id;
    }
    
    
}