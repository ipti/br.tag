<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing EscolaTType
 *
 * XSD Type: escola_t
 */
class EscolaTType
{
    #[Serializer\SerializedName("edu:idEscola")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $idEscola = null;

    #[Serializer\XmlList(inline: true, entry: "edu:turma")]
    private array $turma = [];

    #[Serializer\SerializedName("edu:diretor")]
    private ?DiretorTType $diretor = null;

    #[Serializer\XmlList(inline: true, entry: "edu:cardapio")]
    private array $cardapio = [];

    // Métodos getters e setters permanecem os mesmos
    public function getIdEscola(): ?int
    {
        return $this->idEscola;
    }

    public function setIdEscola(int $idEscola): self
    {
        $this->idEscola = $idEscola;
        return $this;
    }

    public function addToTurma(TurmaTType $turma): self
    {
        $this->turma[] = $turma;
        return $this;
    }

    public function issetTurma($index): bool
    {
        return isset($this->turma[$index]);
    }

    public function unsetTurma($index): void
    {
        unset($this->turma[$index]);
    }

    public function getTurma(): array
    {
        return $this->turma;
    }

    public function setTurma(array $turma): self
    {
        $this->turma = $turma;
        return $this;
    }

    public function getDiretor(): ?DiretorTType
    {
        return $this->diretor;
    }

    public function setDiretor(DiretorTType $diretor): self
    {
        $this->diretor = $diretor;
        return $this;
    }

    public function addToCardapio(CardapioTType $cardapio): self
    {
        $this->cardapio[] = $cardapio;
        return $this;
    }

    public function issetCardapio($index): bool
    {
        return isset($this->cardapio[$index]);
    }

    public function unsetCardapio($index): void
    {
        unset($this->cardapio[$index]);
    }

    public function getCardapio(): array
    {
        return $this->cardapio;
    }

    public function setCardapio(?array $cardapio): self
    {
        $this->cardapio = $cardapio;
        return $this;
    }
}
