<?php
namespace GORM\test

class Usuario extends  Model {
	private $id;
	private $nome;

	/**
	*	@param $data array com as informações para criaçao do usuario
	*/
	public function __construct($data = []){
		isset($data['id'])		? $this->id = $data['id']		:	$this->id;
		isset($data['nome'])	? $this->nome = $data['nome']	:	$this->nome;
		$this->class = $this;
	}

	public function __get($attr){
		switch ($attr) {
			case 'id':
				return $this->id;
				break;
			
			case 'nome':
				return $this->nome;
				break;
			
			default:
				
				break;
		}
	}
	public function __set($attr, $value){
		switch ($attr) {
			case 'id':
				return $this->id = $value;
				break;
			
			case 'nome':
				return $this->nome = $value;
				break;
			
			default:
				
				break;
		}
	}
	
	public function toArray(){
		return array(
			'id'	=>	$this->id,
			'nome'	=>	$this->nome
			);
	}

}