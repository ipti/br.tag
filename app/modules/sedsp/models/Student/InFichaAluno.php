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

    function __construct($fichaAluno) {
        $fichaAluno = (object) $fichaAluno;
        $this->inDadosPessoais = new InDadosPessoais($fichaAluno->inDadosPessoais);
        $this->inDocumentos = new InDocumentos($fichaAluno->inDocumentos);
        $this->inCertidaoNova = new InCertidaoNova($fichaAluno->inCertidaoNova);
        $this->inCertidaoAntiga = new InCertidaoAntiga($fichaAluno->InCertidaoAntiga);
        $this->inEnderecoResidencial = new InEnderecoResidencial($fichaAluno->InEnderecoResidencial);
        $this->inDeficiencia = new InDeficiencia($fichaAluno->InDeficiencia);
        $this->inRecursoAvaliacao = new InRecursoAvaliacao($fichaAluno->InRecursoAvaliacao);
        $this->inRastreio = new InRastreio($fichaAluno->InRastreio);
    }

	public function jsonSerialize()
    {
		$filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }
}
