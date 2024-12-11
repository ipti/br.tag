<?php

namespace Genericos;

/**
 * Class representing OrgaoRespAtaRegPrecosTType
 *
 * 
 * XSD Type: OrgaoRespAtaRegPrecos_t
 */
class OrgaoRespAtaRegPrecosTType
{

    /**
     * @var string $cnpj
     */
    private $cnpj = null;

    /**
     * @var string $nome
     */
    private $nome = null;

    /**
     * Gets as cnpj
     *
     * @return string
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * Sets a new cnpj
     *
     * @param string $cnpj
     * @return self
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    /**
     * Gets as nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Sets a new nome
     *
     * @param string $nome
     * @return self
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }


}

