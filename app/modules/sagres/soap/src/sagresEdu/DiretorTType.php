<?php

namespace SagresEdu;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class representing DiretorTType
 *
 * 
 * XSD Type: diretor_t
 */
class DiretorTType
{

    /**
     * @var string $cpfDiretor
     * @SerializedName("edu:cpfDiretor")
     */
    private $cpfDiretor = null;

    /**
     * @var string $nrAto
     * @SerializedName("edu:nrAto")
     */
    private $nrAto = null;

    /**
     * Gets as cpfDiretor
     *
     * @return string
     */
    public function getCpfDiretor()
    {
        return $this->cpfDiretor;
    }

    /**
     * Sets a new cpfDiretor
     *
     * @param string $cpfDiretor
     * @return self
     */
    public function setCpfDiretor($cpfDiretor)
    {
        $this->cpfDiretor = $cpfDiretor;
        return $this;
    }

    /**
     * Gets as nrAto
     *
     * @return string
     */
    public function getNrAto()
    {
        return $this->nrAto;
    }

    /**
     * Sets a new nrAto
     *
     * @param string $nrAto
     * @return self
     */
    public function setNrAto($nrAto)
    {
        $this->nrAto = $nrAto;
        return $this;
    }


}

