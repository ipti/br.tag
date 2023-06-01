<?php

namespace SagresEdu;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlElement;

/**
 * Class representing AtendimentoTType
 *
 * 
 * XSD Type: atendimento_t
 */
class AtendimentoTType
{

    /**
     * @var \DateTime $data
     * @Type("DateTime<'Y-m-d'>")
     * @SerializedName("edu:data")
     * @XmlElement(cdata=false)
     */
    private $data = null;

    /**
     * @var string $local
     * @SerializedName("edu:local")
     * @XmlElement(cdata=false)
     */
    private $local = null;

    /**
     * Gets as data
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets a new data
     *
     * @param \DateTime $data
     * @return self
     */
    public function setData(\DateTime $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Gets as local
     *
     * @return string
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Sets a new local
     *
     * @param string $local
     * @return self
     */
    public function setLocal($local)
    {
        $this->local = $local;
        return $this;
    }


}

