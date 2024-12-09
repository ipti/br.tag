<?php

namespace SagresEdu;

/**
 * Class representing SerieTType
 *
 * 
 * XSD Type: serie_t
 */
class SerieTType
{
    /**
     * @var string $idSerie
     */
    private $idSerie = null;

    /**
     * @var \SagresEdu\MatriculaTType[] $matricula
     */
    private $matricula = [
        
    ];

    /**
     * Gets as idSerie
     *
     * @return string
     */
    public function getIdSerie()
    {
        return $this->idSerie;
    }

    /**
     * Sets a new idSerie
     *
     * @param string $idSerie
     * @return self
     */
    public function setIdSerie($idSerie)
    {
        $this->idSerie = $idSerie;
        return $this;
    }

    /**
     * Adds as matricula
     *
     * @return self
     * @param \SagresEdu\MatriculaTType $matricula
     */
    public function addToMatricula(\SagresEdu\MatriculaTType $matricula)
    {
        $this->matricula[] = $matricula;
        return $this;
    }

    /**
     * isset matricula
     *
     * @param int|string $index
     * @return bool
     */
    public function issetMatricula($index)
    {
        return isset($this->matricula[$index]);
    }

    /**
     * unset matricula
     *
     * @param int|string $index
     * @return void
     */
    public function unsetMatricula($index)
    {
        unset($this->matricula[$index]);
    }

    /**
     * Gets as matricula
     *
     * @return \SagresEdu\MatriculaTType[]
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Sets a new matricula
     *
     * @param \SagresEdu\MatriculaTType[] $matricula
     * @return self
     */
    public function setMatricula(array $matricula)
    {
        $this->matricula = $matricula;
        return $this;
    }
}

