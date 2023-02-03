<?php

namespace SagresEdu;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Class representing AlunoTType
 *
 * 
 * XSD Type: aluno_t
 */
class AlunoTType
{

    /**
     * @var string $cpfAluno
     */
    private $cpfAluno = null;

    /**
     * @var date $dataNascimento
     */
    private $dataNascimento = null;

    /**
     * @var string $nome
     */
    private $nome = null;

    /**
     * @var bool $pcd
     */
    private $pcd = null;

    /**
     * @var int $sexo
     */
    private $sexo = null;

    /**
     * Gets as cpfAluno
     *
     * @return string
     */
    public function getCpfAluno()
    {
        return $this->cpfAluno;
    }

    /**
     * Sets a new cpfAluno
     *
     * @param string $cpfAluno
     * @return self
     */
    public function setCpfAluno($cpfAluno)
    {
        $this->cpfAluno = $cpfAluno;
        return $this;
    }

    /**
     * Gets as dataNascimento
     *
     * @return date
     */
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    /**
     * Sets a new dataNascimento
     *
     * @param $dataNascimento
     * @return self
     */
    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
        return $this;
    }

    /**
     * Gets as nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Sets a new nome
     *
     * @param string $nome
     * @return self
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Gets as pcd
     *
     * @return bool
     */
    public function getPcd()
    {
        return $this->pcd;
    }

    /**
     * Sets a new pcd
     *
     * @param bool $pcd
     * @return self
     */
    public function setPcd($pcd)
    {
        $this->pcd = $pcd;
        return $this;
    }

    /**
     * Gets as sexo
     *
     * @return int
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Sets a new sexo
     *
     * @param int $sexo
     * @return self
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
        return $this;
    }


}

