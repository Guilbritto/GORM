<?php
namespace GORM;
use Exception;
//include('../gorm.php');
class Model {
    /**
     * Variável para armazenar as configurações
     *
     * @var Mixed
     */
    public $configuration;
    /**
     * Variavel que irá armazenar qual é a tabela que será utilizada nesta modificação
     *
     * @var string
     */
    protected $table;
    /**
     * Array com as configurações do sistema, tais como banco de dados e modo de execução
     *
     * @var Mixed
     */
    protected $config;
    /**
     * Metodo que implementa o metodo Singleton
     *
     * @return Instancia 
     */
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        $instance->loadConf();
        return $instance;
    }
    /**
     * Função para carregas as informações do arquivo de configuração 
     * gorm.conf
     *
     * @return Mixed
     */
    public function loadConf(){
        if (file_exists('gorm.conf')){
            if (is_readable('gorm.conf')){
                $arch = file('gorm.conf');
                foreach ($arch as $key => $value) {
                    if (substr($value, 0, 1)!= "#"){
                        $arr = explode('=', $value);
                        $conf[trim($arr[0])] = trim($arr[1]);
                    }
                }
                $this->configuration = $conf;
            }else{
                throw new Exception("Não foi possivel ler o arquivo de configuração", 1);
            }
        }else{
            throw new Exception("Arquivo não Existe", 1);
        }
    }

}