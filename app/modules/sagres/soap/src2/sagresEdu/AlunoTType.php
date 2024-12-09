<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;
/**
 * Class representing AlunoTType
 *
 *
 * XSD Type: aluno_t
 */
class AlunoTType
{
    #[Serializer\SerializedName("edu:cpfAluno")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $cpfAluno = null;

    #[Serializer\Type("DateTime<'Y-m-d'>")]
    #[Serializer\SerializedName("edu:data_nascimento")]
    #[Serializer\XmlElement(cdata: false)]
    private ?\DateTime $dataNascimento = null;

    #[Serializer\SerializedName("edu:nome")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $nome = null;

    #[Serializer\SerializedName("edu:pcd")]
    #[Serializer\XmlElement(cdata: false)]
    private ?bool $pcd = null;

    #[Serializer\SerializedName("edu:sexo")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $sexo = null;

    #[Serializer\SerializedName("edu:justSemCpf")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $justSemCpf = null;

    /**
     * Gets as cpfAluno
     *
     * @return string
     */
    public function getCpfAluno(): string|null
    {
        return $this->cpfAluno;
    }

    /**
     * Sets a new cpfAluno
     *
     * @param string $cpfAluno
     * @return self
     */
    public function setCpfAluno(string|null $cpfAluno): self
    {
        $this->cpfAluno = $cpfAluno;
        return $this;
    }

    /**
     * Gets as dataNascimento
     *
     * @return \DateTime
     */
    public function getDataNascimento(): \DateTime|null
    {
        return $this->dataNascimento;
    }

    /**
     * Sets a new dataNascimento
     *
     * @param \DateTime $dataNascimento
     * @return self
     */
    public function setDataNascimento(\DateTime $dataNascimento): self
    {
        $this->dataNascimento = $dataNascimento;
        return $this;
    }

    /**
     * Gets as nome
     *
     * @return string
     */
    public function getNome():string|null
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

    /**
     * Gets as justSemCpf
     *
     * @return int
     */
    public function getJustSemCpf()
    {
        return $this->justSemCpf;
    }

    /**
     * Sets a new justSemCpf
     *
     * @param int $justSemCpf
     * @return self
     */
    public function setJustSemCpf($justSemCpf)
    {
        $this->justSemCpf = $justSemCpf;
        return $this;
    }
}

