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
$animal->raca = 'Pastor Alemão';
$animal->setUniqueFild('raca');
$animal->makeSelect()->fields('bosta')->where('tipo=bosta');
$animal->select();