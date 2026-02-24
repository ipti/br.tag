<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing TurmaTType
 *
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

    /**
     * @var bool $multiseriada
     */
    #[Serializer\SerializedName('edu:multiseriada')]
    #[Serializer\XmlElement(cdata: false)]
    private ?bool $multiseriada = null;

    #[Serializer\SerializedName('edu:nrSala')]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $nrSala = null;

    #[Serializer\SerializedName('edu:prosic')]
    #[Serializer\XmlElement(cdata: false)]
    private ?bool $prosic = null;

    /**
     * Gets as periodo
     *
     * @return int
     */
    public function getPeriodo(): ?int
    {
        return $this->periodo;
    }

    /**
     * Sets a new periodo
     *
     * @param int $periodo
     * @return self
     */
    public function setPeriodo(int $periodo): self
    {
        $this->periodo = $periodo;
        return $this;
    }

    /**
     * Gets as descricao
     *
     * @return string
     */
    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    /**
     * Sets a new descricao
     *
     * @param string $descricao
     * @return self
     */
    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Gets as turno
     *
     * @return int
     */
    public function getTurno(): ?int
    {
        return $this->turno;
    }

    /**
     * Sets a new turno
     *
     * @param int $turno
     * @return self
     */
    public function setTurno(int $turno): self
    {
        $this->turno = $turno;
        return $this;
    }

    /**
     * Adds as serie
     *
     * @return self
     * @param \SagresEdu\SerieTType $serie
     */
    public function addToSerie(SerieTType $serie): self
    {
        $this->serie[] = $serie;
        return $this;
    }

    /**
     * isset serie
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSerie($index): bool
    {
        return isset($this->serie[$index]);
    }

    /**
     * unset serie
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSerie($index): void
    {
        unset($this->serie[$index]);
    }

    /**
     * Gets as serie
     *
     * @return \SagresEdu\SerieTType[]
     */
    public function getSerie(): array
    {
        return $this->serie;
    }

    /**
     * Sets a new serie
     *
     * @param \SagresEdu\SerieTType[] $serie
     * @return self
     */
    public function setSerie(array $serie): self
    {
        $this->serie = $serie;
        return $this;
    }

    /**
     * Adds as horario
     *
     * @return self
     * @param \SagresEdu\HorarioTType $horario
     */
    public function addToHorario(HorarioTType $horario): self
    {
        $this->horario[] = $horario;
        return $this;
    }

    /**
     * isset horario
     *
     * @param int|string $index
     * @return bool
     */
    public function issetHorario($index): bool
    {
        return isset($this->horario[$index]);
    }

    /**
     * unset horario
     *
     * @param int|string $index
     * @return void
     */
    public function unsetHorario($index): void
    {
        unset($this->horario[$index]);
    }

    /**
     * Gets as horario
     *
     * @return \SagresEdu\HorarioTType[]
     */
    public function getHorario(): array
    {
        return $this->horario;
    }

    /**
     * Sets a new horario
     *
     * @param \SagresEdu\HorarioTType[] $horario
     * @return self
     */
    public function setHorario(array $horario): self
    {
        $this->horario = $horario;
        return $this;
    }

    /**
     * Gets as finalTurma
     *
     * @return bool
     */
    public function getFinalTurma(): ?bool
    {
        return $this->finalTurma;
    }

    /**
     * Sets a new finalTurma
     *
     * @param bool $finalTurma
     * @return self
     */
    public function setFinalTurma(bool $finalTurma): self
    {
        $this->finalTurma = $finalTurma;
        return $this;
    }

    /**
     * Gets as multiseriada
     *
     * @return bool
     */
    public function getMultiseriada(): bool
    {
        return $this->multiseriada;
    }

    /**
     * Sets a new multiseriada
     *
     * @param bool $multiseriada
     * @return self
     */
    public function setMultiseriada(bool $multiseriada): self
    {
        $this->multiseriada = $multiseriada;
        return $this;
    }

    /**
     * Gets as nrSala
     *
     * @return int
     */
    public function getNrSala(): ?int
    {
        return $this->nrSala;
    }

    /**
     * Sets a new nrSala
     *
     * @param int $nrSala
     * @return self
     */
    public function setNrSala(?int $nrSala): self
    {
        $this->nrSala = $nrSala;
        return $this;
    }

    /**
     * Gets as prosic
     *
     * @return bool
     */
    public function getProsic(): ?bool
    {
        return $this->prosic;
    }

    /**
     * Sets a new prosic
     *
     * @param bool $prosic
     * @return self
     */
    public function setProsic(?bool $prosic): self
    {
        $this->prosic = $prosic;
        return $this;
    }
}
