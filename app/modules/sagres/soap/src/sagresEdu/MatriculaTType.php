<?php

namespace SagresEdu;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class representing MatriculaTType
 *
 * 
 * XSD Type: matricula_t
 */
class MatriculaTType
{

    /**
     * @var string $numero
     * @SerializedName("edu:numero")
     */
    private $numero = null;

    /**
     * @var \DateTime $dataMatricula
     * @type("DateTime<'Y-m-d'>")
     * @SerializedName("edu:data_matricula")
     */
    private $dataMatricula = null;

    /**
     * @var \DateTime $dataCancelamento
     * @type("DateTime<'Y-m-d'>")
     * @SerializedName("edu:dataCancelamento")
     */
    private $dataCancelamento = null;

    /**
     * @var int $numeroFaltas
     * @SerializedName("edu:numero_faltas")
     */
    private $numeroFaltas = null;

    /**
     * @var bool $aprovado
     * @SerializedName("edu:aprovado")
     */
    private $aprovado = null;

    /**
     * @var \SagresEdu\AlunoTType $aluno
     * @SerializedName("edu:aluno")
     */
    private $aluno = null;

    /**
     * Gets as numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Sets a new numero
     *
     * @param string $numero
     * @return self
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Gets as dataMatricula
     *
     * @return \DateTime
     */
    public function getDataMatricula()
    {
        return $this->dataMatricula;
    }

    /**
     * Sets a new dataMatricula
     *
     * @param \DateTime $dataMatricula
     * @return self
     */
    public function setDataMatricula(\DateTime $dataMatricula)
    {
        $this->dataMatricula = $dataMatricula;
        return $this;
    }

    /**
     * Gets as dataCancelamento
     *
     * @return \DateTime
     */
    public function getDataCancelamento()
    {
        return $this->dataCancelamento;
    }

    /**
     * Sets a new dataCancelamento
     *
     * @param \DateTime $dataCancelamento
     * @return self
     */
    public function setDataCancelamento(\DateTime $dataCancelamento)
    {
        $this->dataCancelamento = $dataCancelamento;
        return $this;
    }

    /**
     * Gets as numeroFaltas
     *
     * @return int
     */
    public function getNumeroFaltas()
    {
        return $this->numeroFaltas;
    }

    /**
     * Sets a new numeroFaltas
     *
     * @param int $numeroFaltas
     * @return self
     */
    public function setNumeroFaltas($numeroFaltas)
    {
        $this->numeroFaltas = $numeroFaltas;
        return $this;
    }

    /**
     * Gets as aprovado
     *
     * @return bool
     */
    public function getAprovado()
    {
        return $this->aprovado;
    }

    /**
     * Sets a new aprovado
     *
     * @param bool $aprovado
     * @return self
     */
    public function setAprovado($aprovado)
    {
        $this->aprovado = $aprovado;
        return $this;
    }

    /**
     * Gets as aluno
     *
     * @return \SagresEdu\AlunoTType
     */
    public function getAluno()
    {
        return $this->aluno;
    }

    /**
     * Sets a new aluno
     *
     * @param \SagresEdu\AlunoTType $aluno
     * @return self
     */
    public function setAluno(\SagresEdu\AlunoTType $aluno)
    {
        $this->aluno = $aluno;
        return $this;
    }


}

