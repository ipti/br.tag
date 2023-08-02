<?php

class InListarAlunos implements JsonSerializable
{
    public $inDadosPessoais;
    public $inDocumentos;

    /**
     * Summary of __construct
     * @param InDadosPessoais $inDadosPessoais
     * @param InDocumentos $inDocumentos
     * 
     * @return InListarAlunos
     */
    public function __construct(InDadosPessoais $inDadosPessoais, InDocumentos $inDocumentos)
    {
        $this->inDadosPessoais = $inDadosPessoais;
        $this->inDocumentos = $inDocumentos;
    }

    function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }

    /**
     * Get the value of inDadosPessoais
     */
    public function getInDadosPessoais()
    {
        return $this->inDadosPessoais;
    }

    /**
     * Set the value of inDadosPessoais
     */
    public function setInDadosPessoais($inDadosPessoais): self
    {
        $this->inDadosPessoais = $inDadosPessoais;

        return $this;
    }

    /**
     * Get the value of inDocumentos
     */
    public function getInDocumentos()
    {
        return $this->inDocumentos;
    }

    /**
     * Set the value of inDocumentos
     */
    public function setInDocumentos($inDocumentos): self
    {
        $this->inDocumentos = $inDocumentos;

        return $this;
    }
}