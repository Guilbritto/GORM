<?php
namespace GORM;
require_once('src/Model.php');

class Animal extends \GORM\Model{
    public $id;
    public $raca;
    public $nome;
    public $tipo;
}
$animal = Animal::getInstance();
$animal->id = 2;
$animal->raca = 'Doberman';
print_r($animal->update());