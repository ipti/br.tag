<?php

namespace SagresEdu;

/**
 * Class representing EducacaoTType
 *
 * 
 * XSD Type: educacao_t
 */
class EducacaoTType
{

    /**
     * @var \SagresEdu\EscolaTType[] $escola
     */
    private $escola = [
        
    ];

    /**
     * @var \SagresEdu\AtendimentoTType[] $atendimento
     */
    private $atendimento = [
        
    ];

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
     * Adds as atendimento
     *
     * @return self
     * @param \SagresEdu\AtendimentoTType $atendimento
     */
    public function addToAtendimento(\SagresEdu\AtendimentoTType $atendimento)
    {
        $this->atendimento[] = $atendimento;
        return $this;
    }

    /**
     * isset atendimento
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAtendimento($index)
    {
        return isset($this->atendimento[$index]);
    }

    /**
     * unset atendimento
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAtendimento($index)
    {
        unset($this->atendimento[$index]);
    }

    /**
     * Gets as atendimento
     *
     * @return \SagresEdu\AtendimentoTType[]
     */
    public function getAtendimento()
    {
        return $this->atendimento;
    }

    /**
     * Sets a new atendimento
     *
     * @param \SagresEdu\AtendimentoTType[] $atendimento
     * @return self
     */
    public function setAtendimento(array $atendimento)
    {
        $this->atendimento = $atendimento;
        return $this;
    }


}

