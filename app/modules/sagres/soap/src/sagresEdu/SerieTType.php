<?php

namespace SagresEdu;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlElement;

/**
 * Class representing SerieTType
 *
 * 
 * XSD Type: serie_t
 */
class SerieTType
{

    /**
     * @var string $descricao
     * @SerializedName("edu:descricao")
     * @XmlElement(cdata=false)
     */
    private $descricao = null;

    /**
     * @var int $modalidade
     * @SerializedName("edu:modalidade")
     * @XmlElement(cdata=false)
     */
    private $modalidade = null;

    /**
     * Gets as descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Sets a new descricao
     *
     * @param string $descricao
     * @return self
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Gets as modalidade
     *
     * @return int
     */
    public function getModalidade()
    {
        return $this->modalidade;
    }

    /**
     * Sets a new modalidade
     *
     * @param int $modalidade
     * @return self
     */
    public function setModalidade($modalidade)
    {
        $this->modalidade = $modalidade;
        return $this;
    }


}

