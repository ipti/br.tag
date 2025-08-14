<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing SerieTType.
 *
 * XSD Type: serie_t
 */
class SerieTType
{
    #[Serializer\SerializedName('edu:idSerie')]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $idSerie = null;

    /**
     * @var \SagresEdu\MatriculaTType[] $matricula
     */
    #[Serializer\XmlList(inline: true, entry: 'edu:matricula')]
    private $matricula = [];

    /**
     * Gets as idSerie.
     *
     */
    public function getIdSerie():?string
    {
        return $this->idSerie;
    }

    /**
     * Sets a new idSerie.
     *
     */
    public function setIdSerie(string $idSerie):self
    {
        $this->idSerie = $idSerie;

        return $this;
    }

    /**
     * Adds as matricula.
     *
     */
    public function addToMatricula(MatriculaTType $matricula):self
    {
        $this->matricula[] = $matricula;

        return $this;
    }

    /**
     * isset matricula.
     *
     * @param int|string $index
     */
    public function issetMatricula($index):bool
    {
        return isset($this->matricula[$index]);
    }

    /**
     * unset matricula.
     *
     * @param int|string $index
     */
    public function unsetMatricula($index):void
    {
        unset($this->matricula[$index]);
    }

    /**
     * Gets as matricula.
     *
     * @return \SagresEdu\MatriculaTType[]
     */
    public function getMatricula():array
    {
        return $this->matricula;
    }

    /**
     * Sets a new matricula.
     *
     * @param \SagresEdu\MatriculaTType[] $matricula
     */
    public function setMatricula(?array $matricula):self
    {
        $this->matricula = $matricula;

        return $this;
    }
}
