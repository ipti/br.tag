<?php

namespace Genericos;

/**
 * Class representing NumeroLeiQueAutorizouTType
 *
 * 
 * XSD Type: numeroLeiQueAutorizou_t
 */
class NumeroLeiQueAutorizouTType
{

    /**
     * @var string $numero
     */
    private $numero = null;

    /**
     * @var string $ano
     */
    private $ano = null;

    /**
     * Gets as numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Sets a new numero
     *
     * @param string $numero
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

