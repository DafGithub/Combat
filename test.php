<?php

class MaClasse
{
    private $attributs = [];
    private $unAttributPrive;

    public function __set($nom, $valeur)
    {
        $this->attributs[$nom] = $valeur;
    }

    public function afficherAttributs()
    {
        echo '<pre>', print_r($this->attributs, true), '</pre>';
    }
}

$obj = new MaClasse;

$obj->attribut = 'Simple test';
$obj->unAttributPrive = 'Autre simple test';

$obj->afficherAttributs();