<?php

require_once '../utils/database.php';

class Produto{
    private $idProduto;
    private $nomeProduto;
    private $valorProduto;
    private $idCategoria;

    public function __construct($idProduto, $nomeProduto, $valorProduto, $idCategoria){
        $this->idProduto = $idProduto;
        $this->nomeProduto = $nomeProduto;
        $this->valorProduto = $valorProduto;
        $this->idCategoria = $idCategoria;
    }

    public function getIdProduto(){
        return $this->idProduto;
    }
    public function setIdProduto($idProduto){
        $this->idProduto = $idProduto;
    }

    public function getNomeProduto(){
        return $this->nomeProduto;
    }
    public function setNomeProduto($nomeProduto){
        $this->nomeProduto = $nomeProduto;
    }
    
    public function getValorProduto(){
        return $this->valorProduto;
    }
    public function setValorProduto($valorProduto){
        $this->valorProduto = $valorProduto;
    }

    public function getIdCategoria(){
        return $this->idCategoria;
    }
    public function setIdCategoria($idCategoria){
        $this->idCategoria = $idCategoria;
    }


    public function gravar(){
        
    }


}