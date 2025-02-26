<?php

namespace SagresEdu;
use JMS\Serializer\Annotation as Serializer;


/**
 * Class representing EscolaTType
 *
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

    /**
     * Gets as idEscola
     *
     * @return int
     */
    public function getIdEscola():?int
    {
        return $this->idEscola;
    }

    /**
     * Sets a new idEscola
     *
     * @param int $idEscola
     * @return self
     */
    public function setIdEscola(int $idEscola): self
    {
        $this->idEscola = $idEscola;
        return $this;
    }

    /**
     * Adds as turma
     *
     * @return self
     * @param \SagresEdu\TurmaTType $turma
     */
    public function addToTurma(TurmaTType $turma): self
    {
        $this->turma[] = $turma;
        return $this;
    }

    /**
     * isset turma
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTurma($index): bool
    {
        return isset($this->turma[$index]);
    }

    /**
     * unset turma
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTurma($index): void
    {
        unset($this->turma[$index]);
    }

    /**
     * Gets as turma
     *
     * @return \SagresEdu\TurmaTType[]
     */
    public function getTurma(): array
    {
        return $this->turma;
    }

    /**
     * Sets a new turma
     *
     * @param \SagresEdu\TurmaTType[] $turma
     * @return self
     */
    public function setTurma(array $turma): self
    {
        $this->turma = $turma;
        return $this;
    }

    /**
     * Gets as diretor
     *
     * @return \SagresEdu\DiretorTType
     */
    public function getDiretor(): ?DiretorTType
    {
        return $this->diretor;
    }

    /**
     * Sets a new diretor
     *
     * @param \SagresEdu\DiretorTType $diretor
     * @return self
     */
    public function setDiretor(DiretorTType $diretor): self
    {
        $this->diretor = $diretor;
        return $this;
    }

    /**
     * Adds as cardapio
     *
     * @return self
     * @param \SagresEdu\CardapioTType $cardapio
     */
    public function addToCardapio(CardapioTType $cardapio): self

    {
        $this->cardapio[] = $cardapio;
        return $this;
    }

    /**
     * isset cardapio
     *
     * @param int|string $index
     * @return bool
     */
    public function issetCardapio($index): bool
    {
        return isset($this->cardapio[$index]);
    }

    /**
     * unset cardapio
     *
     * @param int|string $index
     * @return void
     */
    public function unsetCardapio($index): void
    {
        unset($this->cardapio[$index]);
    }

    /**
     * Gets as cardapio
     *
     * @return \SagresEdu\CardapioTType[]
     */
    public function getCardapio(): array
    {
        return $this->cardapio;
    }

    /**
     * Sets a new cardapio
     *
     * @param \SagresEdu\CardapioTType[] $cardapio
     * @return self
     */
    public function setCardapio(?array $cardapio): self
    {
        $this->cardapio = $cardapio;
        return $this;
    }
}

