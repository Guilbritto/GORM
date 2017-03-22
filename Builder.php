<?php
/**
*   Classe que irá gerar os SQL de forma Generica.
*   @author Guilherme Brito
*   @version 1.0
*/
namespace GORM;
trait Builder
{
    /**
    *
    * Conjunto de variavel que recebe os valores para se gerar um SQL
    * @access private
    * @name $select
    * @name $insert
    * @name $where
    * @name $update
    * @name $set
    * @name $inner
    * @name $on
    */
    private static $select  = "SELECT * FROM ";
    private static $insert  = "INSERT INTO ";
    private static $where   = " WHERE ";
    private static $update  = "UPDATE ";
    private static $set     = " SET ";
    private static $inner   = " INNER JOIN ";
    private static $on      = " ON ";
    private static $group   = " GROUP BY ";
    private static $order   = " ORDER BY ";
    /**
    * Variavel que recebe um array com as opções para gerar o SQL
    * @access public
    * @name $condition
    * @example $condition = array('where' => array('campo' => 'valor'))
    */
    public static  $condition;
    /**
    * Recebe o nome da tabela da função Model::loadTable()
    * @access public
    * @name $table
    */
    public static  $table;
    /**
    * Recebe a String que formara o SQL
    * @access public
    * @name $sql
    */
    public static  $sql;

    /**
    * Função para gerar um Insert no banco de dados com base nas informações passadas no parametro
    * @param Object $class
    * @return void
    */
    public function makeInsert($class){
        $array = $class->toArray();
        $size   = count($array);
        $i      = 0;
        $chave  = "(";
        $valor  = "(";
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
    * Função gera um SQL com base nas informações passadas pela variavel self::$condition
    * possiveis posições para o Array:
    * @example ['select' => 'nome,telefone']
    * ---- Modifica o SELECT * para SELECT nome,telefone
    * @example ['inner' => array('nomeDaTabela' => array('nomeDoCampo1' => 'nomeDoCampo2'))]
    * ---- irá gerar algo como SELECT * FROM $TABELA INNER JOIN nomeDaTabela ON nomeDoCampo1=nomeDoCampo2
    * @example ['where' => array('nomeDocampo1' => 'nomeDoCampo2')]
    * ---- irá gerar algo como SELECT * FROM $TABELA WHERE cnomeDoCampo1=nomeDoCampo2
    * @example ['AND' => array(' nomeDocampo1' => 'nomeDoCampo2', 'nomeDocampo3' => 'nomeDoCampo4' )]
    * ---- essa opção pode ser usada com o where ficando da seguinte forma: ['where' => 'AND' => array('nomeDocampo1' => 'nomeDoCampo2', 'nomeDocampo3' => 'nomeDoCampo4' )]
    * @param Object $class
    * @return void
    */
    public function makeSelect($class){
        //Converte a classe em Array
        $class = $class->toArray();
        $temp = self::$select.self::$table;

        /**
        * Checa se a posição ['select'] existe
        */
        if (isset(self::$condition['select'])){
            $option = self::$condition['select'];
            $temp = str_replace("*", $option, $temp);
        }
        /**
        * Checa se a posição ['inner'] existe
        */
        if (isset(self::$condition['inner'])){
            $inner = self::$condition['inner'];
            foreach ($inner as $table => $value) {
                $temp .=  self::$inner.$table.self::$on;
                foreach ($value as $key => $values) {
                    $temp .= $key."=".$values;
                }
            }
        }
        /**
        * Checa se a posição ['where'] existe
        */
        if (isset(self::$condition["where"])){
            $where = self::$condition['where'];
            $temp .= self::$where;
            /**
            * Percorre a primeira camada do array para checar se existe alguma clausula como 'AND' ou 'OR'
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
                /**
                * Checa se a posição ['AND'] existe
                */
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
                /**
                * Checa se a posição ['OR'] existe
                */
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
        if (isset(self::$condition["group"])){
          $temp .= self::$group.self::$condition['group'];
        }
        if (isset(self::$condition["order"])){
          $temp .= self::$order.self::$condition['order'];
        }
        /**
        * Insere o SQL gerado na variavel self::$sql
        */
        self::$sql = $temp;

    }

    /**
    * Função para gerar um Update com base nas informações do OBJ
    * @param Object $class
    * @return void
    */
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
