<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing HorarioTType
 *
 * XSD Type: horario_t
 */
class HorarioTType
{
    #[Serializer\SerializedName("edu:dia_semana")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $diaSemana = null;

    #[Serializer\SerializedName("edu:duracao")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $duracao = null;

    #[Serializer\Type("DateTime<'H:i:s'>")]
    #[Serializer\SerializedName("edu:hora_inicio")]
    #[Serializer\XmlElement(cdata: false)]
    private ?\DateTime $horaInicio = null;

    #[Serializer\SerializedName("edu:disciplina")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $disciplina = null;

    #[Serializer\XmlList(inline: true, entry: "edu:cpfProfessor")]
    #[Serializer\SerializedName("edu:cpfProfessor")]
    #[Serializer\XmlElement(cdata: false)]
    private array $cpfProfessor = [];

    // Métodos getters e setters permanecem os mesmos
    public function getDiaSemana(): ?int
    {
        return $this->diaSemana;
    }

    public function setDiaSemana(int $diaSemana): self
    {
        $this->diaSemana = $diaSemana;
        return $this;
    }

    public function getDuracao(): ?int
    {
        return $this->duracao;
    }

    public function setDuracao(int $duracao): self
    {
        $this->duracao = $duracao;
        return $this;
    }

    public function getHoraInicio(): ?\DateTime
    {
        return $this->horaInicio;
    }

    public function setHoraInicio(\DateTime $horaInicio): self
    {
        $this->horaInicio = $horaInicio;
        return $this;
    }

    public function getDisciplina(): ?string
    {
        return $this->disciplina;
    }

    public function setDisciplina(string $disciplina): self
    {
        $this->disciplina = $disciplina;
        return $this;
    }

    public function addToCpfProfessor(string $cpfProfessor): self
    {
        $this->cpfProfessor[] = $cpfProfessor;
        return $this;
    }

    public function issetCpfProfessor($index): bool
    {
        return isset($this->cpfProfessor[$index]);
    }

    public function unsetCpfProfessor($index): void
    {
        unset($this->cpfProfessor[$index]);
    }

    public function getCpfProfessor(): array
    {
        return $this->cpfProfessor;
    }

    public function setCpfProfessor(array $cpfProfessor): self
    {
        $this->cpfProfessor = $cpfProfessor;
        return $this;
    }
}
