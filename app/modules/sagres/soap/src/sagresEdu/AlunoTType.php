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


    public function getCpfAluno(): string|null
    {
        return $this->cpfAluno;
    }


    public function setCpfAluno(string|null $cpfAluno): self
    {
        $this->cpfAluno = $cpfAluno;
        return $this;
    }

    public function getDataNascimento(): \DateTime|null
    {
        return $this->dataNascimento;
    }


    public function setDataNascimento(\DateTime $dataNascimento): self
    {
        $this->dataNascimento = $dataNascimento;
        return $this;
    }


    public function getNome(): ?string
    {
        return $this->nome;
    }


    public function setNome(string $nome)
    {
        $this->nome = $nome;
        return $this;
    }


    public function getPcd():?bool
    {
        return $this->pcd;
    }


    public function setPcd(bool $pcd): self
    {
        $this->pcd = $pcd;
        return $this;
    }


    public function getSexo(): ?int
    {
        return $this->sexo;
    }


    public function setSexo(int $sexo): self
    {
        $this->sexo = $sexo;
        return $this;
    }


    public function getJustSemCpf(): ?int
    {
        return $this->justSemCpf;
    }


    public function setJustSemCpf( int $justSemCpf)
    {
        $this->justSemCpf = $justSemCpf;
        return $this;
    }
}

