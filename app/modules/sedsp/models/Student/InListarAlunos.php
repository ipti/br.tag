<?php

class InListarAlunos implements JsonSerializable
{
    /**
     * @var  InDadosPessoais
     */
    public $inDadosPessoais;

    /**
     * @var InDocumentos
     */
    public $inDocumentos;


    /**
     * 
     * Summary of __construct
     * @param array $searchListOfStudents
     * @return InListarAlunos
     * 
     */
    public function __construct(array $searchListOfStudents)
    {
        $this->inDadosPessoais = new InDadosPessoais((object) $searchListOfStudents['InDadosPessoais']);
        $this->inDocumentos = new InDocumentos((object) $searchListOfStudents['InDocumentos']);
    }

    /**
     * Summary of jsonSerialize
     * @return array
     */
    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}