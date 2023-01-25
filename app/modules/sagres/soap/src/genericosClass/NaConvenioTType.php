<?php

namespace Genericos;

/**
 * Class representing NaConvenioTType
 *
 * 
 * XSD Type: naConvenio_t
 */
class NaConvenioTType
{

    /**
     * @var string $tipoConvenio
     */
    private $tipoConvenio = null;

    /**
     * @var int $nrConvenio
     */
    private $nrConvenio = null;

    /**
     * @var string $anoConvenio
     */
    private $anoConvenio = null;

    /**
     * Gets as tipoConvenio
     *
     * @return string
     */
    public function getTipoConvenio()
    {
        return $this->tipoConvenio;
    }

    /**
     * Sets a new tipoConvenio
     *
     * @param string $tipoConvenio
     * @return self
     */
    public function setTipoConvenio($tipoConvenio)
    {
        $this->tipoConvenio = $tipoConvenio;
        return $this;
    }

    /**
     * Gets as nrConvenio
     *
     * @return int
     */
    public function getNrConvenio()
    {
        return $this->nrConvenio;
    }

    /**
     * Sets a new nrConvenio
     *
     * @param int $nrConvenio
     * @return self
     */
    public function setNrConvenio($nrConvenio)
    {
        $this->nrConvenio = $nrConvenio;
        return $this;
    }

    /**
     * Gets as anoConvenio
     *
     * @return string
     */
    public function getAnoConvenio()
    {
        return $this->anoConvenio;
    }

    /**
     * Sets a new anoConvenio
     *
     * @param string $anoConvenio
     * @return self
     */
    public function setAnoConvenio($anoConvenio)
    {
        $this->anoConvenio = $anoConvenio;
        return $this;
    }


}

