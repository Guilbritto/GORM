<?php
namespace GORM;
use PDOException;
trait Persistent
{
    /**
     * Salva um objeto no banco de dados conforme as configurações
     * e caso a variavel lastId for true ele retorna o id da ultima inserção
     *
     * @param boolean $lastId
     * @return bool
     */
    public function save($lastId = false){
        // Mode de Desenvolvimento
        if($this->configuration['mode'] == 'devel'){
            $this->makeInsert();
            try{
                if (!$this::getConnection()->prepare($this->configuration['sql'])->execute()){
                    throw new Exception("Não foi possivel inserir a informação no banco de dados", 1);
                }else{
                    return [
                        'flag' => true, 
                        'message' => 'Dados inseridos no Banco de dados com sucesso!', 
                        'debug' => [
                            'sql' => $this->configuration['sql']
                            ]
                        ];
                }
            }catch(PDOException $e){
                switch ($e->getCode()) {
                    case '42000':
                        echo "Erro de sintaxe : ". $this->configuration['sql'];
                        break;
                    default:
                        echo $e->getMessage()."<br>". $this->configuration['sql'];
                        break;
                }
            }
            //Modo de Produção
        }else if ($this->configuration['mode'] == 'production'){
            $this->makeInsert();
            try{
                if (!$this::getConnection()->prepare($this->configuration['sql'])->execute()){
                    throw new Exception("Não foi possivel inserir a informação no banco de dados", 1);
                }else{
                    return [
                        'flag' => true, 
                        'message' => 'Dados inseridos no Banco de dados com sucesso!'];
                }
            }catch(PDOException $e){
                switch ($e->getCode()) {
                    case '42000':
                        throw $e;
                        break;
                    default:
                        echo $e->getMessage();
                        break;
                }
            }
        }
    }
    /**
     * Gera query para fazer o update de um objeto especifico. O seu where é baseado no primaryKey 
     * por padrão ele é o campo id caso queira trocar o mesmo é so utilizar o metodo setPrimaryKey
     *
     * @return void
     */
    public function update(){
        // Modo de Desenvolvimento
        if ($this->configuration['mode'] == 'devel'){
            $this->makeUpdate();
            try{
                if(!$this::getConnection()->prepare($this->configuration['sql'])->execute()){
                    throw new Exception("Não foi posivel fazer o update das informações", 1);
                }else{
                    return [
                        'flag' => true,
                        'message' => 'Update efetuado com sucesso',
                        'debug' => $this->configuration['sql']
                    ];
                }
            }catch(PDOException $e){
                switch ($e->getCode()) {
                    case '42000':
                        echo "Erro de sintaxe : ". $this->configuration['sql'];
                        break;
                    default:
                        echo $e->getMessage()."<br>". $this->configuration['sql'];
                        break;
                }
            }
        // Modo de Produção
        }else if ($this->configuration['mode'] == 'production'){
            $this->makeUpdate();
            try{
            if(!$this::getConnection()->prepare($this->configuration['sql'])->execute()){
                throw new Exception("Não foi posivel fazer o update das informações", 1);
            }else{
                return [
                    'flag' => true,
                    'message' => 'Update efetuado com sucesso'
                ];
            }
            }catch(PDOException $e){
                switch ($e->getCode()) {
                    case '42000':
                        throw $e;
                        break;
                    default:
                        echo $e->getMessage();
                        break;
                }
            }
        }
    }
}