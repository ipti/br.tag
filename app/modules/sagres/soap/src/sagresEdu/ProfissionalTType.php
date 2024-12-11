<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing ProfissionalTType
 *
 * 
 * XSD Type: profissional_t
 */
class ProfissionalTType
{
    #[Serializer\SerializedName("edu:cpfProfissional")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $cpfProfissional = null;

    #[Serializer\SerializedName("edu:especialidade")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $especialidade = null;

    #[Serializer\SerializedName("edu:idEscola")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $idEscola = null;

    #[Serializer\SerializedName("edu:fundeb")]
    #[Serializer\XmlElement(cdata: false)]
    private ?bool $fundeb = null;

    #[Serializer\XmlList(inline: true, entry: "edu:atendimento")]
    private array $atendimento = [];

    public function getCpfProfissional(): ?string
    {
        return $this->cpfProfissional;
    }

    public function setCpfProfissional(string $cpfProfissional): self
    {
        $this->cpfProfissional = $cpfProfissional;
        return $this;
    }

    public function getEspecialidade(): ?string
    {
        return $this->especialidade;
    }

    public function setEspecialidade(string $especialidade): self
    {
        $this->especialidade = $especialidade;
        return $this;
    }

    public function getIdEscola(): ?int
    {
        return $this->idEscola;
    }

    public function setIdEscola(int $idEscola): self
    {
        $this->idEscola = $idEscola;
        return $this;
    }

    public function getFundeb(): ?bool
    {
        return $this->fundeb;
    }

    public function setFundeb(bool $fundeb): self
    {
        $this->fundeb = $fundeb;
        return $this;
    }

    public function addToAtendimento(AtendimentoTType $atendimento): self
    {
        $this->atendimento[] = $atendimento;
        return $this;
    }

    public function issetAtendimento($index): bool
    {
        return isset($this->atendimento[$index]);
    }

    public function unsetAtendimento($index): void
    {
        unset($this->atendimento[$index]);
    }

    public function getAtendimento(): array
    {
        return $this->atendimento;
    }

    public function setAtendimento(?array $atendimento): self
    {
        $this->atendimento = $atendimento;
        return $this;
    }
}

