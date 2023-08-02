<?php

class InFichaAluno implements JsonSerializable
{
    public $inDadosPessoais;
    public $inDocumentos;
    public $inCertidaoNova;
    public $inCertidaoAntiga;
    public $inEnderecoResidencial;
    public $inDeficiencia;
    public $inRecursoAvaliacao;
    public $inRastreio;

    function __construct( 
        InDadosPessoais $inDadosPessoais,
        InDocumentos $inDocumentos,
        ?InCertidaoNova $inCertidaoNova,
        ?InCertidaoAntiga $inCertidaoAntiga,
        InEnderecoResidencial $inEnderecoResidencial,
        InDeficiencia $inDeficiencia,
        InRecursoAvaliacao $inRecursoAvaliacao,
        InRastreio $inRastreio
    ) {
        $this->inDadosPessoais = $inDadosPessoais;
        $this->inDocumentos = $inDocumentos;
        $this->inCertidaoNova = $inCertidaoNova;
        $this->inCertidaoAntiga = $inCertidaoAntiga;
        $this->inEnderecoResidencial = $inEnderecoResidencial;
        $this->inDeficiencia = $inDeficiencia;
        $this->inRecursoAvaliacao = $inRecursoAvaliacao;
        $this->inRastreio = $inRastreio;
    }

    public function jsonSerialize()
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

    /**
     * Get the value of inCertidaoNova
     */
    public function getInCertidaoNova()
    {
        return $this->inCertidaoNova;
    }

    /**
     * Set the value of inCertidaoNova
     */
    public function setInCertidaoNova($inCertidaoNova): self
    {
        $this->inCertidaoNova = $inCertidaoNova;

        return $this;
    }

    /**
     * Get the value of inCertidaoAntiga
     */
    public function getInCertidaoAntiga()
    {
        return $this->inCertidaoAntiga;
    }

    /**
     * Set the value of inCertidaoAntiga
     */
    public function setInCertidaoAntiga($inCertidaoAntiga): self
    {
        $this->inCertidaoAntiga = $inCertidaoAntiga;

        return $this;
    }

    /**
     * Get the value of inEnderecoResidencial
     */
    public function getInEnderecoResidencial()
    {
        return $this->inEnderecoResidencial;
    }

    /**
     * Set the value of inEnderecoResidencial
     */
    public function setInEnderecoResidencial($inEnderecoResidencial): self
    {
        $this->inEnderecoResidencial = $inEnderecoResidencial;

        return $this;
    }

    /**
     * Get the value of inDeficiencia
     */
    public function getInDeficiencia()
    {
        return $this->inDeficiencia;
    }

    /**
     * Set the value of inDeficiencia
     */
    public function setInDeficiencia($inDeficiencia): self
    {
        $this->inDeficiencia = $inDeficiencia;

        return $this;
    }

    /**
     * Get the value of inRecursoAvaliacao
     */
    public function getInRecursoAvaliacao()
    {
        return $this->inRecursoAvaliacao;
    }

    /**
     * Set the value of inRecursoAvaliacao
     */
    public function setInRecursoAvaliacao($inRecursoAvaliacao): self
    {
        $this->inRecursoAvaliacao = $inRecursoAvaliacao;

        return $this;
    }

    /**
     * Get the value of inRastreio
     */
    public function getInRastreio()
    {
        return $this->inRastreio;
    }

    /**
     * Set the value of inRastreio
     */
    public function setInRastreio($inRastreio): self
    {
        $this->inRastreio = $inRastreio;

        return $this;
    }
}