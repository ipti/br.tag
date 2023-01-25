<?php

namespace Genericos;

/**
 * Class representing NaContratoTType
 *
 * 
 * XSD Type: naContrato_t
 */
class NaContratoTType
{

    /**
     * @var string $tipoContrato
     */
    private $tipoContrato = null;

    /**
     * @var int $nrContrato
     */
    private $nrContrato = null;

    /**
     * @var string $anoContrato
     */
    private $anoContrato = null;

    /**
     * Gets as tipoContrato
     *
     * @return string
     */
    public function getTipoContrato()
    {
        return $this->tipoContrato;
    }

    /**
     * Sets a new tipoContrato
     *
     * @param string $tipoContrato
     * @return self
     */
    public function setTipoContrato($tipoContrato)
    {
        $this->tipoContrato = $tipoContrato;
        return $this;
    }

    /**
     * Gets as nrContrato
     *
     * @return int
     */
    public function getNrContrato()
    {
        return $this->nrContrato;
    }

    /**
     * Sets a new nrContrato
     *
     * @param int $nrContrato
     * @return self
     */
    public function setNrContrato($nrContrato)
    {
        $this->nrContrato = $nrContrato;
        return $this;
    }

    /**
     * Gets as anoContrato
     *
     * @return string
     */
    public function getAnoContrato()
    {
        return $this->anoContrato;
    }

    /**
     * Sets a new anoContrato
     *
     * @param string $anoContrato
     * @return self
     */
    public function setAnoContrato($anoContrato)
    {
        $this->anoContrato = $anoContrato;
        return $this;
    }


}

