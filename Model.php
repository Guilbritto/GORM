<?php
namespace GORM;
use PDO;
class Model{
	use Builder;
	protected  		$class;
	protected		$instance;
		public 		$mode = array(
		"driver" 	=> "mysql",
		"host"		=> "31.220.104.130",
		"dbname"	=> "u350275562_aetub", //u350275562_aetub
		"user"		=> "u350275562_aetub", //u350275562_aetub
		"pass"		=> "Z0kbFAWsap" //Z0kbFAWsap
	);
	/**
	*	Salva um registro no banco de dados
	*/
	public function save(){
		$this->loadTable();
		$sql  = Builder::makeInsert($this->class);
		\Api\Controller\Log::Debug(Builder::$sql);
		$con  = new ConnectionFactory($this->mode);
		$db   = $con->getInstance();
		$stmt = $db->prepare(Builder::$sql);
		if ($stmt->execute()){
			return true;
		}else{
			Throw new Exeption("Ocorreu algum problema na execução da SQL");
		}
	}
	/**
	*	Cria select da conforme o objeto da classe que chamou o metodo;
	*	EX: se o Objeto tem um campo nome e este campo esta com valor João ele vai fazer um select 
	*	na coluna nome = João caso vazio $arr ele tras um SELECT * FROM $tabela
	*/
	public function select($arr = null){
		$this->loadTable();
		Builder::$condition = $arr;
		Builder::makeSelect($this->class);
		$con                = new ConnectionFactory($this->mode);
		$db                 = $con->getInstance();
		try{
			\Api\Controller\Log::Debug(Builder::$sql);
			$consulta           = $db->query(Builder::$sql);
			$this->loadObject($consulta->fetchAll(PDO::FETCH_OBJ));
			
			return $array;
		}catch(PDOException $e){
			Throw new Exception("Não foi possivel realizar a consulta ".$e->getMessage());
		}
	}
	/**
	*	Atualiza informação conforme o objeto que chamou está função
	*/
	public function update(){
		$this->loadTable();
		Builder::makeUpdate($this->class);
		\Api\Controller\Log::Debug(Builder::$sql);
		$con                   = new ConnectionFactory($this->mode);
		$db                    = $con->getInstance();
		$linha                 = $db->prepare(Builder::$sql);
		return $linha->execute();
	}
	/**
	*	Carrega a Tabela na variavel dentro da trait Builder::$table 
	*/
	public function loadTable(){
		$this->instance = get_called_class();
		Builder::$table = str_replace("\\", "/", strtolower($this->instance));
		Builder::$table = explode('/', Builder::$table);
		Builder::$table = Builder::$table[3];	

	}
	/**
	* Retorna um ou mais objetos de seus respectivos tipos
	*/
	public function loadObject($return){
		foreach ($r as $key => $value) {
				$obj = new $this->instance($value);
				$array[$key] =$obj->toArray();
			}
	}
}
