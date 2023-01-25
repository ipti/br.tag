<?php

namespace SagresEdu;

/**
 * Class representing HorarioTType
 *
 * 
 * XSD Type: horario_t
 */
class HorarioTType
{

    /**
     * @var int $diaSemana
     */
    private $diaSemana = null;

    /**
     * @var int $duracao
     */
    private $duracao = null;

    /**
     * @var \DateTime $horaInicio
     */
    private $horaInicio = null;

    /**
     * @var string $disciplina
     */
    private $disciplina = null;

    /**
     * @var string[] $cpfProfessor
     */
    private $cpfProfessor = [
        
    ];

    /**
     * Gets as diaSemana
     *
     * @return int
     */
    public function getDiaSemana()
    {
        return $this->diaSemana;
    }

    /**
     * Sets a new diaSemana
     *
     * @param int $diaSemana
     * @return self
     */
    public function setDiaSemana($diaSemana)
    {
        $this->diaSemana = $diaSemana;
        return $this;
    }

    /**
     * Gets as duracao
     *
     * @return int
     */
    public function getDuracao()
    {
        return $this->duracao;
    }

    /**
     * Sets a new duracao
     *
     * @param int $duracao
     * @return self
     */
    public function setDuracao($duracao)
    {
        $this->duracao = $duracao;
        return $this;
    }

    /**
     * Gets as horaInicio
     *
     * @return \DateTime
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * Sets a new horaInicio
     *
     * @param \DateTime $horaInicio
     * @return self
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;
        return $this;
    }

    /**
     * Gets as disciplina
     *
     * @return string
     */
    public function getDisciplina()
    {
        return $this->disciplina;
    }

    /**
     * Sets a new disciplina
     *
     * @param string $disciplina
     * @return self
     */
    public function setDisciplina($disciplina)
    {
        $this->disciplina = $disciplina;
        return $this;
    }

    /**
     * Adds as cpfProfessor
     *
     * @return self
     * @param string $cpfProfessor
     */
    public function addToCpfProfessor($cpfProfessor)
    {
        $this->cpfProfessor[] = $cpfProfessor;
        return $this;
    }

    /**
     * isset cpfProfessor
     *
     * @param int|string $index
     * @return bool
     */
    public function issetCpfProfessor($index)
    {
        return isset($this->cpfProfessor[$index]);
    }

    /**
     * unset cpfProfessor
     *
     * @param int|string $index
     * @return void
     */
    public function unsetCpfProfessor($index)
    {
        unset($this->cpfProfessor[$index]);
    }

    /**
     * Gets as cpfProfessor
     *
     * @return string[]
     */
    public function getCpfProfessor()
    {
        return $this->cpfProfessor;
    }

    /**
     * Sets a new cpfProfessor
     *
     * @param string $cpfProfessor
     * @return self
     */
    public function setCpfProfessor(string $cpfProfessor)
    {
        $this->cpfProfessor = $cpfProfessor;
        return $this;
    }


}

