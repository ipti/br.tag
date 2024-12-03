<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing SerieTType
 *
 * XSD Type: serie_t
 */
class SerieTType
{
    #[Serializer\SerializedName("edu:descricao")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $descricao = null;

    #[Serializer\SerializedName("edu:modalidade")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $modalidade = null;

    // Métodos getters e setters permanecem os mesmos
    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getModalidade(): ?int
    {
        return $this->modalidade;
    }

    public function setModalidade(int $modalidade): self
    {
        $this->modalidade = $modalidade;
        return $this;
    }
}
