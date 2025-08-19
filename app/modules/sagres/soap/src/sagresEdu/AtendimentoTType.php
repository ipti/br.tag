<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class representing AtendimentoTType
 *
 * 
 * XSD Type: atendimento_t
 */
class AtendimentoTType
{
    #[Serializer\Type("DateTime<'Y-m-d'>")]
    #[Serializer\SerializedName("edu:data")]
    #[Serializer\XmlElement(cdata: false)]
    private ?\DateTime $data = null;

    #[Serializer\SerializedName("edu:local")]
    #[Serializer\XmlElement(cdata: false)]
    private ?string $local = null;

    // GETTERS
    public function getData(): ?\DateTime
    {
        return $this->data;
    }

    public function setData(\DateTime $data):self
    {
        $this->data = $data;
        return $this;
    }

  
    public function getLocal():?string
    {
        return $this->local;
    }
    
    public function setLocal(string $local):self
    {
        $this->local = $local;
        return $this;
    }
}

