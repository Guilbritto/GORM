# GORM

Tentativa de criar um pseudo ORM
Informações adicionais, para utilizar é so extender o a classe Model do GORM.

# Utilização
Para a utilização as Entidades tem que representar fielmente a tabela do banco de dados.
A classe Entidade tem que implementar o toArray();

# EX: Tabela Usuario.

id int,
nome varchar,

# Class

class Usuario extends GORM\Model{
	
	private id;
	private nome;

	/**
	*	@param $data array com as informações para criaçao do usuario
	/*
	public function __construc($data = []){
		isset($data['id'])		? $this->setId($data['id'])		:	$this->setId();
		isset($data['nome'])	? $this->setNome($data['nome'])	:	$this->setNome();
	}

	public function toArray(){
		return array(
			'id'	=>	$this->getId(),
			'nome'	=>	$this->getNome()
			);
	}

}

# Insert

	$data = array('nome' => "João");

	$usuario = new Usuario($data);
	$usuario->save();

# Select
Neste caso ele irá buscar na tabela Usuario um registro com nome=João

	$data = array('nome' => "João");
	$usuario = new Usuario($data);
	$usuario->select();

Neste caso ele irá ignorar o conteudo da classe e ira buscar um id=1
	
	$usuario = new Usuario();
	$usuario->select('id' => 1);


Caso utilize Inner
	
	$usuario = new Usuario();
	$usuario->select(array(
		'inner' => array( 'nome_da_tabela_do_Inner' => array('campo_do_usuario' => 'campo_da_tabela_do_inner') )
			)
		);

# Where
Neste caso iremos utilizar da seguinte forma
	
	$data = array('nome' => "João", 'cpf' => "1112223344");
	$usuario = new Usuario($data);
	$usuario->select('WHERE' => array('nome' => $data['nome']));

# WHERE + AND
	$data = array('nome' => "João", 'cpf' => "1112223344");
	$usuario = new Usuario($data);
	$usuario->select('WHERE' => array('AND' => array(
												'nome' => $data['nome'],
												'cpf ' => $data['cpf'])
												)
											);

#WHERE + OR
	
	$data = array('nome' => "João", 'cpf' => "1112223344");
	$usuario = new Usuario($data);
	$usuario->select('WHERE' => array('OR' => array(
												'nome' => $data['nome'],
												'cpf ' => $data['cpf'])
												)
											);


