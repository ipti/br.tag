<?php

namespace Genericos;

/**
 * Class representing NaProcLicitatorioTType
 *
 * 
 * XSD Type: naProcLicitatorio_t
 */
class NaProcLicitatorioTType
{

    /**
     * @var int $numero
     */
    private $numero = null;

    /**
     * @var string $ano
     */
    private $ano = null;

    /**
     * Gets as numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Sets a new numero
     *
     * @param int $numero
     * @return self
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Gets as ano
     *
     * @return string
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * Sets a new ano
     *
     * @param string $ano
     * @return self
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
        return $this;
    }


}

