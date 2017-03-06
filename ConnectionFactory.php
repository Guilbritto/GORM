<?php
namespace GORM;
use PDO;
class ConnectionFactory{
	private static $driver	=	null;
	private static $host	=	null;
	private static $dbname	=	null;
	private static $user	=	null;
	private static $pass	=	null;
	private static $instance;
	public function __construct($data){
		self::$driver 	= $data['driver'];
		self::$host 	= $data['host'];
		self::$dbname 	= $data['dbname'];
		self::$user 	= $data['user'];
		self::$pass 	= $data['pass'];
		return $this->getInstance();
	}
	/**
	*	Retorna a conexão com banco de dados
	*/
	public function getInstance(){
		try{
		if (!isset(self::$instance)) {
                self::$instance = new PDO(self::$driver.":host=".self::$host."; dbname=".self::$dbname, self::$user, self::$pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                      self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                      self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }
        return self::$instance;
		}catch (Exeption $e){
			Throw new Exception("Ocorreu um problema ao tentar conectar o banco de dados ".$e);
			return false;
		}
    }      
}
