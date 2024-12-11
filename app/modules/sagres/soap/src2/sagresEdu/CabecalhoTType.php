<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;


/**
 * Class representing CabecalhoTType
 *
 * 
 * XSD Type: cabecalho_t
 */
class CabecalhoTType
{
    #[Serializer\SerializedName("edu:codigoUnidGestora")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $codigoUnidGestora = null;

    #[Serializer\SerializedName("edu:nomeUnidGestora")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $nomeUnidGestora = null;

    #[Serializer\SerializedName("edu:cpfResponsavel")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $cpfResponsavel = null;

    #[Serializer\SerializedName("edu:cpfGestor")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $cpfGestor = null;

    #[Serializer\SerializedName("edu:anoReferencia")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $anoReferencia = null;

    #[Serializer\SerializedName("edu:mesReferencia")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $mesReferencia = null;

    #[Serializer\SerializedName("edu:versaoXml")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $versaoXml = null;

    #[Serializer\SerializedName("edu:diaInicPresContas")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $diaInicPresContas = null;

    #[Serializer\SerializedName("edu:diaFinaPresContas")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $diaFinaPresContas = null;


    // Métodos getters e setters permanecem os mesmos
    
    public function getCodigoUnidGestora():?string
    {
        return $this->codigoUnidGestora;
    }

    public function setCodigoUnidGestora(string $codigoUnidGestora):self
    {
        $this->codigoUnidGestora = $codigoUnidGestora;
        return $this;
    }

    
    public function getNomeUnidGestora():?string
    {
        return $this->nomeUnidGestora;
    }

    
    public function setNomeUnidGestora(string $nomeUnidGestora): self
    {
        $this->nomeUnidGestora = $nomeUnidGestora;
        return $this;
    }

    public function getCpfResponsavel() :?string
    {
        return $this->cpfResponsavel;
    }

    public function setCpfResponsavel( string $cpfResponsavel): self
    {
        $this->cpfResponsavel = $cpfResponsavel;
        return $this;
    }

    public function getCpfGestor(): ?string
    {
        return $this->cpfGestor;
    }

    
    public function setCpfGestor(string $cpfGestor):self
    {
        $this->cpfGestor = $cpfGestor;
        return $this;
    }

    public function getAnoReferencia(): ?int
    {
        return $this->anoReferencia;
    }

    
    public function setAnoReferencia(int $anoReferencia):self
    {
        $this->anoReferencia = $anoReferencia;
        return $this;
    }

    
    public function getMesReferencia():?int
    {
        return $this->mesReferencia;
    }

    
    public function setMesReferencia(int $mesReferencia):self
    {
        $this->mesReferencia = $mesReferencia;
        return $this;
    }

    
    public function getVersaoXml():?int
    {
        return $this->versaoXml;
    }

    
    public function setVersaoXml(int $versaoXml):self
    {
        $this->versaoXml = $versaoXml;
        return $this;
    }

    public function getDiaInicPresContas():?int 
    {
        return $this->diaInicPresContas;
    }

    
    public function setDiaInicPresContas(int $diaInicPresContas): self
    {
        $this->diaInicPresContas = $diaInicPresContas;
        return $this;
    }

   
    public function getDiaFinaPresContas(): ?int 
    {
        return $this->diaFinaPresContas;
    }

    
    public function setDiaFinaPresContas(int $diaFinaPresContas):self
    {
        $this->diaFinaPresContas = $diaFinaPresContas;
        return $this;
    }
}

