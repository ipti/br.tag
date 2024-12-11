<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing TurmaTType
 *
 * XSD Type: turma_t
 */
class TurmaTType
{
    #[Serializer\SerializedName("edu:periodo")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $periodo = null;

    #[Serializer\SerializedName("edu:descricao")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $descricao = null;

    #[Serializer\SerializedName("edu:turno")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $turno = null;

    #[Serializer\XmlList(inline: true, entry: "edu:serie")]
    private array $serie = [];

    #[Serializer\XmlList(inline: true, entry: "edu:matricula")]
    private array $matricula = [];

    #[Serializer\XmlList(inline: true, entry: "edu:horario")]
    private array $horario = [];

    #[Serializer\SerializedName("edu:finalTurma")]
    #[Serializer\XmlElement(cdata: false)]
    private ?bool $finalTurma = null;

    // Métodos getters e setters permanecem os mesmos
    public function getPeriodo(): ?int
    {
        return $this->periodo;
    }

    public function setPeriodo(int $periodo): self
    {
        $this->periodo = $periodo;
        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getTurno(): ?int
    {
        return $this->turno;
    }

    public function setTurno(int $turno): self
    {
        $this->turno = $turno;
        return $this;
    }

    public function addToSerie(SerieTType $serie): self
    {
        $this->serie[] = $serie;
        return $this;
    }

    public function issetSerie($index): bool
    {
        return isset($this->serie[$index]);
    }

    public function unsetSerie($index): void
    {
        unset($this->serie[$index]);
    }

    public function getSerie(): array
    {
        return $this->serie;
    }

    public function setSerie(array $serie): self
    {
        $this->serie = $serie;
        return $this;
    }

    public function addToMatricula(MatriculaTType $matricula): self
    {
        $this->matricula[] = $matricula;
        return $this;
    }

    public function issetMatricula($index): bool
    {
        return isset($this->matricula[$index]);
    }

    public function unsetMatricula($index): void
    {
        unset($this->matricula[$index]);
    }

    public function getMatricula(): array
    {
        return $this->matricula;
    }

    public function setMatricula(array $matricula): self
    {
        $this->matricula = $matricula;
        return $this;
    }

    public function addToHorario(HorarioTType $horario): self
    {
        $this->horario[] = $horario;
        return $this;
    }

    public function issetHorario($index): bool
    {
        return isset($this->horario[$index]);
    }

    public function unsetHorario($index): void
    {
        unset($this->horario[$index]);
    }

    public function getHorario(): array
    {
        return $this->horario;
    }

    public function setHorario(array $horario): self
    {
        $this->horario = $horario;
        return $this;
    }

    public function getFinalTurma(): ?bool
    {
        return $this->finalTurma;
    }

    public function setFinalTurma(bool $finalTurma): self
    {
        $this->finalTurma = $finalTurma;
        return $this;
    }
}
