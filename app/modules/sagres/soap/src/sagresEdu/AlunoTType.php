<?php

namespace SagresEdu;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlElement;

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
     * @SerializedName("edu:cpfAluno")
     * @XmlElement(cdata=false)
     */
    private $cpfAluno = null;

    /**
     * @var \DateTime $dataNascimento
     * @Type("DateTime<'Y-m-d'>")
     * @SerializedName("edu:data_nascimento")
     * @XmlElement(cdata=false)
     */
    private $dataNascimento = null;

    /**
     * @var string $nome
     * @SerializedName("edu:nome")
     * @XmlElement(cdata=false)
     */
    private $nome = null;

    /**
     * @var bool $pcd
     * @SerializedName("edu:pcd")
     * @XmlElement(cdata=false)
     */
    private $pcd = null;

    /**
     * @var int $sexo
     * @SerializedName("edu:sexo")
     * @XmlElement(cdata=false)
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
     * @return \DateTime
     */
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    /**
     * Sets a new dataNascimento
     *
     * @param \DateTime $dataNascimento
     * @return self
     */
    public function setDataNascimento(\DateTime $dataNascimento)
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

