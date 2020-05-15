<?php

class conteudoXML{

    public $titulo;
    public $dataDePublicacao;
    public $linkOriginal;
    public $descricao;

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getLinkOriginal()
    {
        return $this->linkOriginal;
    }

    public function __construct($dataDePublicacao , $descricao ,$titulo,$linkOriginal){
        $this->dataDePublicacao= $dataDePublicacao;
        $this->descricao = $descricao;
        $this->linkOriginal = $linkOriginal;
        $this->titulo = $titulo;
    }//construct

}//conteudoXML
