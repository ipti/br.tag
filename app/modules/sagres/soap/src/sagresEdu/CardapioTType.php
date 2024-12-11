<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;


/**
 * Class representing CardapioTType
 *
 * 
 * XSD Type: cardapio_t
 */
class CardapioTType
{
    #[Serializer\Type("DateTime<'Y-m-d'>")]
    #[Serializer\SerializedName("edu:data")]
    #[Serializer\XmlElement(cdata: false)]
    private $data = null;

    #[Serializer\SerializedName("edu:turno")]
    #[Serializer\XmlElement(cdata: false)]
    private ?int $turno = null;

    #[Serializer\SerializedName("edu:descricao_merenda")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $descricaoMerenda = null;

    #[Serializer\SerializedName("edu:ajustado")]
    #[Serializer\XmlElement(cdata: false)]
    private ?bool $ajustado = null;

  
    public function getData(): ?\DateTime
    {
        return $this->data;
    }

    public function setData(\DateTime $data): self
    {
        $this->data = $data;
        return $this;
    }
  
    public function getTurno(): ?int
    {
        return $this->turno;
    }
    
    public function setTurno(int $turno): self
    {
        $this->turno = $turno;
        return $this;
    }
 
    public function getDescricaoMerenda(): ?string
    {
        return $this->descricaoMerenda;
    }
    
    public function setDescricaoMerenda(string $descricaoMerenda): self
    {
        $this->descricaoMerenda = $descricaoMerenda;
        return $this;
    }

    public function getAjustado(): ?bool
    {
        return $this->ajustado;
    }

    public function setAjustado(bool $ajustado): self
    {
        $this->ajustado = $ajustado;
        return $this;
    }
}

