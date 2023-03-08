<?php

namespace SagresEdu;

use JMS\Serializer\Annotation\XmlList;

/**
 * Class representing EscolaTType
 *
 * 
 * XSD Type: escola_t
 */
class EscolaTType
{

    /**
     * @var int $idEscola
     */
    private $idEscola = null;

    /**
     * @var \SagresEdu\TurmaTType[] $turma
     */
    private $turma = [
        
    ];

    /**
     * @var \SagresEdu\DiretorTType $diretor
     */
    private $diretor = null;

    /**
     * @var \SagresEdu\CardapioTType[] $cardapio
     * @XmlList(inline = true, entry = "edu:cardapio")
     */
    private $cardapio = [
        
    ];

    /**
     * Gets as idEscola
     *
     * @return int
     */
    public function getIdEscola()
    {
        return $this->idEscola;
    }

    /**
     * Sets a new idEscola
     *
     * @param int $idEscola
     * @return self
     */
    public function setIdEscola($idEscola)
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
    public function addToTurma(\SagresEdu\TurmaTType $turma)
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
    public function issetTurma($index)
    {
        return isset($this->turma[$index]);
    }

    /**
     * unset turma
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTurma($index)
    {
        unset($this->turma[$index]);
    }

    /**
     * Gets as turma
     *
     * @return \SagresEdu\TurmaTType[]
     */
    public function getTurma()
    {
        return $this->turma;
    }

    /**
     * Sets a new turma
     *
     * @param \SagresEdu\TurmaTType[] $turma
     * @return self
     */
    public function setTurma(array $turma)
    {
        $this->turma = $turma;
        return $this;
    }

    /**
     * Gets as diretor
     *
     * @return \SagresEdu\DiretorTType
     */
    public function getDiretor()
    {
        return $this->diretor;
    }

    /**
     * Sets a new diretor
     *
     * @param \SagresEdu\DiretorTType $diretor
     * @return self
     */
    public function setDiretor(\SagresEdu\DiretorTType $diretor)
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
    public function addToCardapio(\SagresEdu\CardapioTType $cardapio)
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
    public function issetCardapio($index)
    {
        return isset($this->cardapio[$index]);
    }

    /**
     * unset cardapio
     *
     * @param int|string $index
     * @return void
     */
    public function unsetCardapio($index)
    {
        unset($this->cardapio[$index]);
    }

    /**
     * Gets as cardapio
     *
     * @return \SagresEdu\CardapioTType[]
     */
    public function getCardapio()
    {
        return $this->cardapio;
    }

    /**
     * Sets a new cardapio
     *
     * @param \SagresEdu\CardapioTType[] $cardapio
     * @return self
     */
    public function setCardapio(array $cardapio)
    {
        $this->cardapio = $cardapio;
        return $this;
    }


}

