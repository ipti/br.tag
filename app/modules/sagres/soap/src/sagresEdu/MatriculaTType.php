<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing MatriculaTType.
 *
 * XSD Type: matricula_t
 */
class MatriculaTType
{
    #[Serializer\SerializedName('edu:numero')]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $numero = null;

    #[Serializer\Type("DateTime<'Y-m-d'>")]
    #[Serializer\SerializedName('edu:data_matricula')]
    #[Serializer\XmlElement(cdata: false)]
    private ?\DateTime $dataMatricula = null;

    #[Serializer\Type("DateTime<'Y-m-d'>")]
    #[Serializer\SerializedName('edu:data_cancelamento')]
    #[Serializer\XmlElement(cdata: false)]
    private ?\DateTime $dataCancelamento = null;

    #[Serializer\SerializedName('edu:numero_faltas')]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $numeroFaltas = null;

    #[Serializer\SerializedName('edu:aprovado')]
    #[Serializer\XmlElement(cdata: false)]
    private ?bool $aprovado = null;

    #[Serializer\SerializedName('edu:aluno')]
    #[Serializer\XmlElement(cdata: false)]
    private ?AlunoTType $aluno = null;

    private ?int $enrollment_stage = null;

    public function getEnrollmentStage():?int
    {
        return $this->enrollment_stage;
    }

    public function setEnrollmentStage(?int $enrollment_stage): self
    {
        $this->enrollment_stage = $enrollment_stage;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDataMatricula(): ?\DateTime
    {
        return $this->dataMatricula;
    }

    public function setDataMatricula(\DateTime $dataMatricula): self
    {
        $this->dataMatricula = $dataMatricula;

        return $this;
    }

    public function getDataCancelamento(): ?\DateTime
    {
        return $this->dataCancelamento;
    }

    public function setDataCancelamento(?\DateTime $dataCancelamento): self
    {
        $this->dataCancelamento = $dataCancelamento;

        return $this;
    }

    public function getNumeroFaltas(): ?int
    {
        return $this->numeroFaltas;
    }

    public function setNumeroFaltas(int $numeroFaltas): self
    {
        $this->numeroFaltas = $numeroFaltas;

        return $this;
    }

    public function getAprovado(): ?bool
    {
        return $this->aprovado;
    }

    public function setAprovado(bool $aprovado): self
    {
        $this->aprovado = $aprovado;

        return $this;
    }

    public function getAluno(): ?AlunoTType
    {
        return $this->aluno;
    }

    public function setAluno(AlunoTType $aluno): self
    {
        $this->aluno = $aluno;

        return $this;
    }
}
