<?php
namespace GORM;
require_once('src/Model.php');

class Animal extends \GORM\Model{
    public $raca;
    public $nome;
    public $tipo;
}
$animal = Animal::getInstance();
$animal->raca = 'basset';
print_r($animal::getConnection());