<?php

namespace SagresEdu;

/**
 * Class representing PrestacaoContasTType
 *
 * 
 * XSD Type: prestacaoContas_t
 */
class PrestacaoContasTType {


    /**
     * @var String $codigoUnidGestora
     */
    private $codigoUnidGestora = null;

    /**
     * @var String $nomeUnidGestora
     */
    private $nomeUnidGestora = null;

    /**
     * @var String $cpfContador
     */
    private $cpfContador = null;

    /**
     * @var String $cpfGestor
     */
    private $cpfGestor = null;

    /**
     * @var int $anoReferencia
     */
    private $anoReferencia = null;

    /**
     * @var int $mesReferencia
     */
    private $mesReferencia = null;

    /**
     * @var int $versaoXml
     */
    private $versaoXml = null;

    /**
     * @var int $diaInicPresContas
     */
    private $diaInicPresContas = null;

    /**
     * @var int $diaFinaPresContas
     */
    private $diaFinaPresContas = null;	


    /**
     * Get $codigoUnidGestora
     *
     * @return  String
     */ 
    public function getCodigoUnidGestora()
    {
        return $this->codigoUnidGestora;
    }

    /**
     * Set $codigoUnidGestora
     *
     * @param  String  $codigoUnidGestora  $codigoUnidGestora
     *
     * @return  self
     */ 
    public function setCodigoUnidGestora(String $codigoUnidGestora)
    {
        $this->codigoUnidGestora = $codigoUnidGestora;

        return $this;
    }

    /**
     * Get $nomeUnidGestora
     *
     * @return  String
     */ 
    public function getNomeUnidGestora()
    {
        return $this->nomeUnidGestora;
    }

    /**
     * Set $nomeUnidGestora
     *
     * @param  String  $nomeUnidGestora  $nomeUnidGestora
     *
     * @return  self
     */ 
    public function setNomeUnidGestora(String $nomeUnidGestora)
    {
        $this->nomeUnidGestora = $nomeUnidGestora;

        return $this;
    }

    /**
     * Get $cpfContador
     *
     * @return  String
     */ 
    public function getCpfContador()
    {
        return $this->cpfContador;
    }

    /**
     * Set $cpfContador
     *
     * @param  String  $cpfContador  $cpfContador
     *
     * @return  self
     */ 
    public function setCpfContador(String $cpfContador)
    {
        $this->cpfContador = $cpfContador;

        return $this;
    }

    /**
     * Get $cpfGestor
     *
     * @return  String
     */ 
    public function getCpfGestor()
    {
        return $this->cpfGestor;
    }

    /**
     * Set $cpfGestor
     *
     * @param  String  $cpfGestor  $cpfGestor
     *
     * @return  self
     */ 
    public function setCpfGestor(String $cpfGestor)
    {
        $this->cpfGestor = $cpfGestor;

        return $this;
    }

    /**
     * Get $anoReferencia
     *
     * @return  int
     */ 
    public function getAnoReferencia()
    {
        return $this->anoReferencia;
    }

    /**
     * Set $anoReferencia
     *
     * @param  int  $anoReferencia  $anoReferencia
     *
     * @return  self
     */ 
    public function setAnoReferencia(int $anoReferencia)
    {
        $this->anoReferencia = $anoReferencia;

        return $this;
    }

    /**
     * Get $mesReferencia
     *
     * @return  int
     */ 
    public function getMesReferencia()
    {
        return $this->mesReferencia;
    }

    /**
     * Set $mesReferencia
     *
     * @param  int  $mesReferencia  $mesReferencia
     *
     * @return  self
     */ 
    public function setMesReferencia(int $mesReferencia)
    {
        $this->mesReferencia = $mesReferencia;

        return $this;
    }

    /**
     * Get $versaoXml
     *
     * @return  int
     */ 
    public function getVersaoXml()
    {
        return $this->versaoXml;
    }

    /**
     * Set $versaoXml
     *
     * @param  int  $versaoXml  $versaoXml
     *
     * @return  self
     */ 
    public function setVersaoXml(int $versaoXml)
    {
        $this->versaoXml = $versaoXml;

        return $this;
    }

    /**
     * Get $diaInicPresContas
     *
     * @return  int
     */ 
    public function getDiaInicPresContas()
    {
        return $this->diaInicPresContas;
    }

    /**
     * Set $diaInicPresContas
     *
     * @param  int  $diaInicPresContas  $diaInicPresContas
     *
     * @return  self
     */ 
    public function setDiaInicPresContas(int $diaInicPresContas)
    {
        $this->diaInicPresContas = $diaInicPresContas;

        return $this;
    }

    /**
     * Get $diaFinaPresContas
     *
     * @return  int
     */ 
    public function getDiaFinaPresContas()
    {
        return $this->diaFinaPresContas;
    }

    /**
     * Set $diaFinaPresContas
     *
     * @param  int  $diaFinaPresContas  $diaFinaPresContas
     *
     * @return  self
     */ 
    public function setDiaFinaPresContas(int $diaFinaPresContas)
    {
        $this->diaFinaPresContas = $diaFinaPresContas;

        return $this;
    }
}


