<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing EducacaoTType
 *
 *
 * XSD Type: educacao_t
 */

#[Serializer\XmlRoot(name: 'edu:educacao')]
#[Serializer\XmlNamespace(uri: 'http://www.tce.se.gov.br/sagres2025/xml/sagresEdu', prefix: 'edu')]
class EducacaoTType
{
    #[Serializer\SerializedName('edu:PrestacaoContas')]
    private ?CabecalhoTType $prestacaoContas = null;

    #[Serializer\XmlList(inline: true, entry: 'edu:escola')]
    private array $escola = [];

    #[Serializer\XmlList(inline: true, entry: 'edu:profissional')]
    private $profissional = [];

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

    /**
     * isset escola
     *
     * @param int|string $index
     * @return bool
     */
    public function issetEscola($index): bool
    {
        return isset($this->escola[$index]);
    }

    /**
     * unset escola
     *
     * @param int|string $index
     * @return void
     */
    public function unsetEscola($index): void
    {
        unset($this->escola[$index]);
    }

    public function getEscola(): array
    {
        return $this->escola;
    }

    /**
     * Sets a new escola
     *
     * @param \SagresEdu\EscolaTType[] $escola
     * @return self
     */
    public function setEscola(array $escola): self
    {
        $this->escola = $escola;
        return $this;
    }

    /**
     * Adds as profissional
     *
     * @return self
     * @param \SagresEdu\ProfissionalTType $profissional
     */
    public function addToProfissional(ProfissionalTType $profissional): self
    {
        $this->profissional[] = $profissional;
        return $this;
    }

    /**
     * isset profissional
     *
     * @param int|string $index
     * @return bool
     */
    public function issetProfissional($index): bool
    {
        return isset($this->profissional[$index]);
    }

    /**
     * unset profissional
     *
     * @param int|string $index
     * @return void
     */
    public function unsetProfissional($index): void
    {
        unset($this->profissional[$index]);
    }

    /**
     * Gets as profissional
     *
     * @return \SagresEdu\ProfissionalTType[]
     */
    public function getProfissional()
    {
        return $this->profissional;
    }

    /**
     * Sets a new profissional
     *
     * @param \SagresEdu\ProfissionalTType[] $profissional
     * @return self
     */
    public function setProfissional(array $profissional = null): self
    {
        $this->profissional = $profissional;
        return $this;
    }
}
