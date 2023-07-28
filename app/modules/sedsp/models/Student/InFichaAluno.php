<?php

class InFichaAluno implements JsonSerializable
{
    /** @var InDadosPessoais */
    public $inDadosPessoais;

    /** @var InDocumentos */
    public $inDocumentos;

    /** @var InCertidaoNova */
    public $inCertidaoNova;

    /** @var InCertidaoAntiga */
    public $inCertidaoAntiga;

    /** @var InEnderecoResidencial */
    public $inEnderecoResidencial;

    /** @var InDeficiencia */
    public $inDeficiencia;

    /** @var InRecursoAvaliacao */
    public $inRecursoAvaliacao;

    /** @var InRastreio */
    public $inRastreio;


	public function jsonSerialize()
    {

        function __construct($fichaAluno) {
           
        }

		$filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }
}
