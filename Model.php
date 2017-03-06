<?php
/**
*	Classe desenvolvida com intuito de aprendizado, tentativa de gerar e entender o funcionamento de um ORM
*	@author Guilherme Brito
* 	@version 1.0
*/
namespace GORM;
use PDO;
class Model{
	use Builder, Utils;

	/**
	*	Variavel recebe uma instancia das classes filhas
	*   @access protected	
	*	@name $class 
	*/
	protected  		$class;
	/**
	* 	Variavel recebe o caminho completo da classe filha que há está chamando
	* 	@access protected
	*	@name $instance
	*/
	protected		$instance;
	/**
	* 	Variavel que recebe um array com as informações de conexão
	* 	@access public 
	* 	@name $db
	*/
	public 			$db = array(
		"driver" 	=> "",
		"host"		=> "",
		"dbname"	=> "", 
		"user"		=> "", 
		"pass"		=> "" 
	);
	

	/**
	*	Função para Salvar uma informação no banco de dados
	*	@return 	true ou fasle
	*/
	public function save(){
		$this->loadTable();
		$sql  = Builder::makeInsert($this->class); 
		$con  = new ConnectionFactory($this->db);	
		$db   = $con->getInstance();
		$stmt = $db->prepare(Builder::$sql);
		if ($stmt->execute()){ 					
			Utils::Debug(Builder::$sql);
			return true;
		}else{
			Utils::Debug(Builder::$sql);
			return false;
		}
	}
	/**
	*	Função para fazer um Select no banco de dados
	*	@param 		Array $arr 
	*	@example 	$arr['select' => 'nome,telefone'], este exemplo ira gerar um sql: SELECT nome,telefone FROM $TABELA
	*	@return 	$obj ou array de objetos com os valores do sql
	*/
	public function select($arr = null){
		$this->loadTable();
		Builder::$condition = $arr;
		Builder::makeSelect($this->class);
		$con                = new ConnectionFactory($this->db);
		$db                 = $con->getInstance();
		$consulta           = $db->query(Builder::$sql);
		$return $this->loadObject($consulta->fetchAll(PDO::FETCH_ASSOC));
		
	}
	/**
	*	Função para atualizar as informações no banco de dados
	* 	@param 		Parametro é atraves do objeto $this->class SQL será gerado conforme as informações neste obj
	* 	@return 	Objeto
	*/
	public function update(){
		$this->loadTable();
		Builder::makeUpdate($this->class);
		$con                   = new ConnectionFactory($this->db);
		$db                    = $con->getInstance();
		$linha                 = $db->prepare(Builder::$sql);
		return $linha->execute();
	}
	/**
	*	Carrega a Tabela na variavel dentro da trait Builder::$table 
	*	@return void
	*/
	public function loadTable(){
		$this->instance = get_called_class();
		Builder::$table = str_replace("\\", "/", strtolower($this->instance));
		Builder::$table = explode('/', Builder::$table);
		Builder::$table = Builder::$table[3];	

	}
	/**
	* 	Retorna um ou mais objetos de seus respectivos tipos
	* 	@param Array $return
	* 	@return Array $array
	*/
	public function loadObject($return){
		foreach ($r as $key => $value) {
				$obj = new $this->instance($value);
				$array[$key] =$obj->toArray();
			}
		return $array;
	}
}
