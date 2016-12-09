<?php
namespace GORM;
class Utils{
	protected static $instance;
	
	public function __construct(){
	}
	/*
	*	Classe segue o padrão Singleton
	*/
	public static function getInstance(){
		if (!isset(self::$instance)){
			self::$instance = new Utils();
		}
		return self::$instance;
	}
	/*
	*	Função para separar os campos key e value de um vetor
	*	OBS: o campo key tem que ser igual ao campo do Banco.
	*	@param Array com as informações que serão separadas
	*	@return array com as informações $retorno['key'] é chave,
	*	$retorno['value'] é o valor desta chave;
	*/
	public function makeSql($array){
		$size = count($array);
		$i = 0;
		$chave = "("; // irá receber o campo
		$valor = "("; // irá receber o valor do campo
		foreach ($array as $key => $value) {
			if($i < ($size - 1) ){
				$chave 	.= $key.", "; 
				$valor	.= "'".$value."', ";
			}else{
				$chave	.= $key.") "; 
				$valor	.= "'".$value."') ";
			}
			$i++;
		}
		$retorno = ["key" 	=> $chave,
					"value" => $valor ];
		return $retorno;
	}
	/*
	*	Metodo que modifica o index do vetor mantendo sua integridade
	*	@param $tableau é o array que sera modificaro
	*	@param $old_key é o index antigo do vetor
	*	@param $new_key é o index novo do array
	*/
	function changeIndex(&$tableau, $old_key, $new_key) {
    $changed = FALSE;
    $temp = 0;
    foreach ($tableau as $key => $value) {
        switch ($changed) {
            case FALSE :
                //creates the new key and deletes the old
                if ($key == $old_key) {
                    $tableau[$new_key] = $tableau[$old_key];
                    unset($tableau[$old_key]);
                    $changed = TRUE;
                }
                break;
            case TRUE :
                //moves following keys
                if ($key != $new_key){
                $temp= $tableau[$key];
                unset($tableau[$key]);
                $tableau[$key] = $temp;
                break;
                }
                else {$changed = FALSE;} //stop
        }
    }
    array_values($tableau); //free_memory
}
	
}
