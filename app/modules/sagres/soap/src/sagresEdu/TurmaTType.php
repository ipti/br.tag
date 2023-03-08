<?php

namespace SagresEdu;

/**
 * Class representing TurmaTType
 *
 * 
 * XSD Type: turma_t
 */
class TurmaTType
{

    /**
     * @var int $periodo
     */
    private $periodo = null;

    /**
     * @var string $descricao
     */
    private $descricao = null;

    /**
     * @var int $turno
     */
    private $turno = null;

    /**
     * @var \SagresEdu\SerieTType[] $serie
     */
    private $serie = [
        
    ];

    /**
     * @var \SagresEdu\MatriculaTType[] $matricula
     */
    private $matricula = [
        
    ];

    /**
     * @var \SagresEdu\HorarioTType[] $horario
     */
    private $horario = [
        
    ];

    /**
     * @var bool $finalTurma
     */
    private $finalTurma = null;

    /**
     * Gets as periodo
     *
     * @return int
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Sets a new periodo
     *
     * @param int $periodo
     * @return self
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
        return $this;
    }

    /**
     * Gets as descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Sets a new descricao
     *
     * @param string $descricao
     * @return self
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Gets as turno
     *
     * @return int
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Sets a new turno
     *
     * @param int $turno
     * @return self
     */
    public function setTurno($turno)
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
    public function addToSerie(\SagresEdu\SerieTType $serie)
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
    public function issetSerie($index)
    {
        return isset($this->serie[$index]);
    }

    /**
     * unset serie
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSerie($index)
    {
        unset($this->serie[$index]);
    }

    /**
     * Gets as serie
     *
     * @return \SagresEdu\SerieTType[]
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Sets a new serie
     *
     * @param \SagresEdu\SerieTType[] $serie
     * @return self
     */
    public function setSerie(array $serie)
    {
        $this->serie = $serie;
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

    /**
     * Adds as horario
     *
     * @return self
     * @param \SagresEdu\HorarioTType $horario
     */
    public function addToHorario(\SagresEdu\HorarioTType $horario)
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
    public function issetHorario($index)
    {
        return isset($this->horario[$index]);
    }

    /**
     * unset horario
     *
     * @param int|string $index
     * @return void
     */
    public function unsetHorario($index)
    {
        unset($this->horario[$index]);
    }

    /**
     * Gets as horario
     *
     * @return \SagresEdu\HorarioTType[]
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Sets a new horario
     *
     * @param \SagresEdu\HorarioTType[] $horario
     * @return self
     */
    public function setHorario(array $horario)
    {
        $this->horario = $horario;
        return $this;
    }

    /**
     * Gets as finalTurma
     *
     * @return bool
     */
    public function getFinalTurma()
    {
        return $this->finalTurma;
    }

    /**
     * Sets a new finalTurma
     *
     * @param bool $finalTurma
     * @return self
     */
    public function setFinalTurma($finalTurma)
    {
        $this->finalTurma = $finalTurma;
        return $this;
    }


}

