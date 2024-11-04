<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing EducacaoTType
 *
 * XSD Type: educacao_t
 */
#[Serializer\XmlRoot(name: "edu:educacao")]
#[Serializer\XmlNamespace(uri: "http://www.tce.se.gov.br/sagres2024/xml/sagresEdu", prefix: "edu")]
class EducacaoTType
{
    #[Serializer\SerializedName("edu:PrestacaoContas")]
    private ?CabecalhoTType $prestacaoContas = null;

    #[Serializer\XmlList(inline: true, entry: "edu:escola")]
    private array $escola = [];

    #[Serializer\XmlList(inline: true, entry: "edu:profissional")]
    private array $profissional = [];

    // Métodos getters e setters permanecem os mesmos
    public function getPrestacaoContas(): ?CabecalhoTType
    {
        return $this->prestacaoContas;
    }

    public function setPrestacaoContas(CabecalhoTType $prestacaoContas): self
    {
        $this->prestacaoContas = $prestacaoContas;
        return $this;
    }

    public function addToEscola(EscolaTType $escola): self
    {
        $this->escola[] = $escola;
        return $this;
    }

    public function issetEscola($index): bool
    {
        return isset($this->escola[$index]);
    }

    public function unsetEscola($index): void
    {
        unset($this->escola[$index]);
    }

    public function getEscola(): array
    {
        return $this->escola;
    }

    public function setEscola(array $escola): self
    {
        $this->escola = $escola;
        return $this;
    }

    public function addToProfissional(ProfissionalTType $profissional): self
    {
        $this->profissional[] = $profissional;
        return $this;
    }

    public function issetProfissional($index): bool
    {
        return isset($this->profissional[$index]);
    }

    public function unsetProfissional($index): void
    {
        unset($this->profissional[$index]);
    }

    public function getProfissional(): array
    {
        return $this->profissional;
    }

    public function setProfissional(?array $profissional): self
    {
        $this->profissional = $profissional;
        return $this;
    }
}
