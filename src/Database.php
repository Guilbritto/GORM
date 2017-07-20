<?php
namespace GORM;
use PDO;
trait Database
{
    /**
     * Variavel que irá armazenar a conexão com Banco de Daos
     *
     * @var Connection
     */
    public static $connection;
    /**
     * Metodo que retorna uma conexão com banco de dados, essa função segue o 
     * padrão de projeto Singleton.
     *
     * @return Obj
     */
    public static function getConnection(){
        // Pega a instancia da classe que está chamando a função
        $cls = self::getCalledClass();
            if (!isset(self::$connection)) {
                self::$connection = new PDO($cls->configuration['driver'].':host='.$cls->configuration['host'].';dbname='.$cls->configuration['dbname'], $cls->configuration['user'], $cls->configuration['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));            
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
            }
            return self::$connection;    
    }
}