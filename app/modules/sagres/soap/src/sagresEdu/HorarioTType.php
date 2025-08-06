<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing HorarioTType.
 *
 * XSD Type: horario_t
 */
class HorarioTType
{
    #[Serializer\SerializedName('edu:dia_semana')]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $diaSemana = null;

    #[Serializer\SerializedName('edu:duracao')]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $duracao = null;

    #[Serializer\Type("DateTime<'H:i:s'>")]
    #[Serializer\SerializedName('edu:hora_inicio')]
    #[Serializer\XmlElement(cdata: false)]
    private ?\DateTime $horaInicio = null;

    #[Serializer\SerializedName('edu:disciplina')]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $disciplina = null;

    #[Serializer\XmlList(inline: true, entry: 'edu:cpfProfessor')]
    #[Serializer\SerializedName('edu:cpfProfessor')]
    #[Serializer\XmlElement(cdata: false)]
    private array $cpfProfessor = [];

    /**
     * Gets as diaSemana.
     *
     */
    public function getDiaSemana(): ?int
    {
        return $this->diaSemana;
    }

    /**
     * Sets a new diaSemana.
     *
     */
    public function setDiaSemana(int $diaSemana): self
    {
        $this->diaSemana = $diaSemana;

        return $this;
    }

    /**
     * Gets as duracao.
     *
     */
    public function getDuracao(): ?int
    {
        return $this->duracao;
    }

    /**
     * Sets a new duracao.
     *
     */
    public function setDuracao(int $duracao): self
    {
        $this->duracao = $duracao;

        return $this;
    }

    /**
     * Gets as horaInicio.
     *
     */
    public function getHoraInicio(): ?\DateTime
    {
        return $this->horaInicio;
    }

    /**
     * Sets a new horaInicio.
     *
     */
    public function setHoraInicio(\DateTime $horaInicio): self
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }

    /**
     * Gets as disciplina.
     *
     */
    public function getDisciplina(): ?string
    {
        return $this->disciplina;
    }

    /**
     * Sets a new disciplina.
     *
     */
    public function setDisciplina(string $disciplina): self
    {
        $this->disciplina = $disciplina;

        return $this;
    }

    /**
     * Adds as cpfProfessor.
     *
     */
    public function addToCpfProfessor(string $cpfProfessor): self
    {
        $this->cpfProfessor[] = $cpfProfessor;

        return $this;
    }

    /**
     * isset cpfProfessor.
     *
     * @param int|string $index
     */
    public function issetCpfProfessor($index): bool
    {
        return isset($this->cpfProfessor[$index]);
    }

    /**
     * unset cpfProfessor.
     *
     * @param int|string $index
     */
    public function unsetCpfProfessor($index): void
    {
        unset($this->cpfProfessor[$index]);
    }

    /**
     * Gets as cpfProfessor.
     *
     * @return string[]
     */
    public function getCpfProfessor(): array
    {
        return $this->cpfProfessor;
    }

    /**
     * Sets a new cpfProfessor.
     *
     * @param string $cpfProfessor
     */
    public function setCpfProfessor(array $cpfProfessor): self
    {
        $this->cpfProfessor = $cpfProfessor;

        return $this;
    }
}
