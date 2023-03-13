<?php

namespace SagresEdu;

use JMS\Serializer\Annotation\SerializedName;

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
     */
    private $descricao = null;

    /**
     * @var int $modalidade
     * @SerializedName("edu:modalidade")
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

