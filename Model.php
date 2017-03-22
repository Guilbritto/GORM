<?php
/**
*	Classe desenvolvida com intuito de aprendizado, tentativa de gerar e entender o funcionamento de um ORM
*	@author Guilherme Brito
* 	@version 1.0
*/
namespace GORM;
use PDO;
class Model{
	use Builder;
	use Utils;
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
	public 		$db = array(
		"driver" 	=> "mysql",
		"host"		=> "31.220.104.130",		//31.220.104.130 mysql.hostinger.com.br
		"dbname"	=> "u350275562_aetub", 		//u350275562_aetub u849106042_aetub
		"user"		=> "u350275562_aetub", 		//u350275562_aetub u849106042_aetub
		"pass"		=> "Z0kbFAWsap" 			//Z0kbFAWsap w8IJkSw25j8x
	);

	/**
	*	Função para Salvar uma informação no banco de dados
	*	@return 	true ou fasle
	*/
	public function save($lastId = false){
		$this->loadTable();
		$sql  = Builder::makeInsert($this->class);
		$con  = new ConnectionFactory($this->db);
		$db   = $con->getInstance();
		$stmt = $db->prepare(Builder::$sql);
		if ($stmt->execute()){
			Utils::Debug(Builder::$sql);
			if ($lastId == true){
				return $db->lastInsertId();
			}else{
				return true;
			}
		}else{
			Utils::Debug(Builder::$sql);
			return false;
		}
		
	}
	/**
	*	Função para fazer um Select no banco de dados
	*	@param 		Array $arr
	*	@param		bool	true retorna um Object false retorna um Array
	*	@example 	$arr['select' => 'nome,telefone'], este exemplo ira gerar um sql: SELECT nome,telefone FROM $TABELA
	*	@return 	$obj ou array de objetos com os valores do sql
	*/
	public function select($arr = null, $bool = true){
		$this->loadTable();
		Builder::$condition = $arr;
		Builder::makeSelect($this->class);
		$con                = new ConnectionFactory($this->db);
		$db                 = $con->getInstance();
		$consulta           = $db->query(Builder::$sql);
		if($bool){
			Utils::Debug(Builder::$sql);
			return $this->loadObject($consulta->fetchAll(PDO::FETCH_ASSOC));
		}else{
			Utils::Debug(Builder::$sql);
			return $consulta->fetchAll(PDO::FETCH_ASSOC);
		}
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
		Utils::Debug(Builder::$sql);
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
		foreach ($return as $key => $value) {
				$obj = new $this->instance($value);
				$array[$key] =$obj->toArray();
			}
		return $array;
	}
}
