<?php

namespace SagresEdu;
use JMS\Serializer\Annotation as Serializer;


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
    #[Serializer\SerializedName("edu:idSerie")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $idSerie = null;

    /**
     * @var \SagresEdu\MatriculaTType[] $matricula
     */
    #[Serializer\XmlList(inline: true, entry: "edu:matricula")]
    private $matricula = [];

    /**
     * Gets as idSerie
     *
     * @return string
     */
    public function getIdSerie():?string
    {
        return $this->idSerie;
    }

    /**
     * Sets a new idSerie
     *
     * @param string $idSerie
     * @return self
     */
    public function setIdSerie(string $idSerie):self
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
    public function addToMatricula(MatriculaTType $matricula):self
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
    public function issetMatricula($index):bool
    {
        return isset($this->matricula[$index]);
    }

    /**
     * unset matricula
     *
     * @param int|string $index
     * @return void
     */
    public function unsetMatricula($index):void
    {
        unset($this->matricula[$index]);
    }

    /**
     * Gets as matricula
     *
     * @return \SagresEdu\MatriculaTType[]
     */
    public function getMatricula():array
    {
        return $this->matricula;
    }

    /**
     * Sets a new matricula
     *
     * @param \SagresEdu\MatriculaTType[] $matricula
     * @return self
     */
    public function setMatricula(?array $matricula):self
    {
        $this->matricula = $matricula;
        return $this;
    }
}

