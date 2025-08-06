<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing TurmaTType.
 *
 * XSD Type: turma_t
 */
class TurmaTType
{
    #[Serializer\SerializedName('edu:periodo')]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $periodo = null;

    #[Serializer\SerializedName('edu:descricao')]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $descricao = null;

    #[Serializer\SerializedName('edu:turno')]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $turno = null;

    #[Serializer\XmlList(inline: true, entry: 'edu:serie')]
    private array $serie = [];

    /**
     * @var \SagresEdu\HorarioTType[] $horario
     */
    #[Serializer\XmlList(inline: true, entry: 'edu:horario')]
    private array $horario = [];

    #[Serializer\SerializedName('edu:finalTurma')]
    #[Serializer\XmlElement(cdata: false)]
    private $finalTurma = null;

    #[Serializer\SerializedName('edu:multiseriada')]
    #[Serializer\XmlElement(cdata: false)]
    private ?bool $multiseriada = null;

    /**
     * Gets as periodo.
     *
     */
    public function getPeriodo(): ?int
    {
        return $this->periodo;
    }

    /**
     * Sets a new periodo.
     *
     */
    public function setPeriodo(int $periodo): self
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Gets as descricao.
     *
     */
    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    /**
     * Sets a new descricao.
     *
     */
    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Gets as turno.
     *
     */
    public function getTurno(): ?int
    {
        return $this->turno;
    }

    /**
     * Sets a new turno.
     *
     */
    public function setTurno(int $turno): self
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Adds as serie.
     *
     */
    public function addToSerie(SerieTType $serie): self
    {
        $this->serie[] = $serie;

        return $this;
    }

    /**
     * isset serie.
     *
     * @param int|string $index
     */
    public function issetSerie($index): bool
    {
        return isset($this->serie[$index]);
    }

    /**
     * unset serie.
     *
     * @param int|string $index
     */
    public function unsetSerie($index): void
    {
        unset($this->serie[$index]);
    }

    /**
     * Gets as serie.
     *
     * @return \SagresEdu\SerieTType[]
     */
    public function getSerie(): array
    {
        return $this->serie;
    }

    /**
     * Sets a new serie.
     *
     * @param \SagresEdu\SerieTType[] $serie
     */
    public function setSerie(array $serie): self
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Adds as horario.
     *
     */
    public function addToHorario(HorarioTType $horario): self
    {
        $this->horario[] = $horario;

        return $this;
    }

    /**
     * isset horario.
     *
     * @param int|string $index
     */
    public function issetHorario($index): bool
    {
        return isset($this->horario[$index]);
    }

    /**
     * unset horario.
     *
     * @param int|string $index
     */
    public function unsetHorario($index): void
    {
        unset($this->horario[$index]);
    }

    /**
     * Gets as horario.
     *
     * @return \SagresEdu\HorarioTType[]
     */
    public function getHorario(): array
    {
        return $this->horario;
    }

    /**
     * Sets a new horario.
     *
     * @param \SagresEdu\HorarioTType[] $horario
     */
    public function setHorario(array $horario): self
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Gets as finalTurma.
     *
     */
    public function getFinalTurma(): ?bool
    {
        return $this->finalTurma;
    }

    /**
     * Sets a new finalTurma.
     *
     */
    public function setFinalTurma(bool $finalTurma): self
    {
        $this->finalTurma = $finalTurma;

        return $this;
    }

    /**
     * Gets as multiseriada.
     *
     */
    public function getMultiseriada(): bool
    {
        return $this->multiseriada;
    }

    /**
     * Sets a new multiseriada.
     *
     */
    public function setMultiseriada(bool $multiseriada): self
    {
        $this->multiseriada = $multiseriada;

        return $this;
    }
}
