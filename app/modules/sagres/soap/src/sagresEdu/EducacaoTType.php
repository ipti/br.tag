<?php

namespace SagresEdu;


use JMS\Serializer\Annotation\XmlList;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlNamespace;
use JMS\Serializer\Annotation\XmlElement;


/**
 * Class representing EducacaoTType
 *
 * 
 * XSD Type: educacao_t
 * @XmlRoot("edu:educacao")
 * @XmlNamespace(uri="http://www.tce.se.gov.br/sagres2023/xml/sagresEdu", prefix="edu")
 */
class EducacaoTType
{

    /**
     * @var \SagresEdu\CabecalhoTType $prestacaoContas
     * @SerializedName("edu:PrestacaoContas")
     */
    private $prestacaoContas = null;

    /**
     * @var \SagresEdu\EscolaTType[] $escola
     * @XmlList(inline = true, entry = "edu:escola")
     */
    private $escola = [

    ];

    /**
     * @var \SagresEdu\ProfissionalTType[] $profissional
     * @XmlList(inline = true, entry = "edu:profissional")
     */
    private $profissional = [

    ];

    /**
     * Gets as prestacaoContas
     *
     * @return \SagresEdu\CabecalhoTType
     */
    public function getPrestacaoContas()
    {
        return $this->prestacaoContas;
    }

    /**
     * Sets a new prestacaoContas
     *
     * @param \SagresEdu\CabecalhoTType $prestacaoContas
     * @return self
     */
    public function setPrestacaoContas(\SagresEdu\CabecalhoTType $prestacaoContas)
    {
        $this->prestacaoContas = $prestacaoContas;
        return $this;
    }

    /**
     * Adds as escola
     *
     * @return self
     * @param \SagresEdu\EscolaTType $escola
     */
    public function addToEscola(\SagresEdu\EscolaTType $escola)
    {
        $this->escola[] = $escola;
        return $this;
    }

    /**
     * isset escola
     *
     * @param int|string $index
     * @return bool
     */
    public function issetEscola($index)
    {
        return isset($this->escola[$index]);
    }

    /**
     * unset escola
     *
     * @param int|string $index
     * @return void
     */
    public function unsetEscola($index)
    {
        unset($this->escola[$index]);
    }

    /**
     * Gets as escola
     *
     * @return \SagresEdu\EscolaTType[]
     */
    public function getEscola()
    {
        return $this->escola;
    }

    /**
     * Sets a new escola
     *
     * @param \SagresEdu\EscolaTType[] $escola
     * @return self
     */
    public function setEscola(array $escola)
    {
        $this->escola = $escola;
        return $this;
    }

    /**
     * Adds as profissional
     *
     * @return self
     * @param \SagresEdu\ProfissionalTType $profissional
     */
    public function addToProfissional(\SagresEdu\ProfissionalTType $profissional)
    {
        $this->profissional[] = $profissional;
        return $this;
    }

    /**
     * isset profissional
     *
     * @param int|string $index
     * @return bool
     */
    public function issetProfissional($index)
    {
        return isset($this->profissional[$index]);
    }

    /**
     * unset profissional
     *
     * @param int|string $index
     * @return void
     */
    public function unsetProfissional($index)
    {
        unset($this->profissional[$index]);
    }

    /**
     * Gets as profissional
     *
     * @return \SagresEdu\ProfissionalTType[]
     */
    public function getProfissional()
    {
        return $this->profissional;
    }

    /**
     * Sets a new profissional
     *
     * @param \SagresEdu\ProfissionalTType[] $profissional
     * @return self
     */
    public function setProfissional(array $profissional)
    {
        $this->profissional = $profissional;
        return $this;
    }


}