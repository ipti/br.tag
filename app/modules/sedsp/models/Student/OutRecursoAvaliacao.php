<?php

class OutRecursoAvaliacao
{
    private $outGuiaInterprete;
    private $outInterpreteLibras;
    private $outLeituraLabial;
    private $outNenhum;
    private $outProvaAmpliada;
    private $outTamanhoFonte;
    private $outProvaBraile;
    private $outAuxilioTranscricao;
    private $outAuxilioLeitor;
    private $outProvaVideoLibras;
    private $outCdAudioDefVisual;
    private $outProvaLinguaPortuguesa;

	/**
	 * Summary of __construct
	 * @param OutRecursoAvaliacao $recursoAvaliacao
	 */
	public function __construct($recursoAvaliacao) {
		$this->outGuiaInterprete = $recursoAvaliacao->outGuiaInterprete;
		$this->outInterpreteLibras = $recursoAvaliacao->outInterpreteLibras;
		$this->outLeituraLabial = $recursoAvaliacao->outLeituraLabial;
		$this->outNenhum = $recursoAvaliacao->outNenhum;
		$this->outProvaAmpliada = $recursoAvaliacao->outProvaAmpliada;
		$this->outTamanhoFonte = $recursoAvaliacao->outTamanhoFonte;
		$this->outProvaBraile = $recursoAvaliacao->outProvaBraile;
		$this->outAuxilioTranscricao = $recursoAvaliacao->outAuxilioTranscricao;
		$this->outAuxilioLeitor = $recursoAvaliacao->outAuxilioLeitor;
		$this->outProvaVideoLibras = $recursoAvaliacao->outProvaVideoLibras;
		$this->outCdAudioDefVisual = $recursoAvaliacao->outCdAudioDefVisual;
		$this->outProvaLinguaPortuguesa = $recursoAvaliacao->outProvaLinguaPortuguesa;
	}
	

	public function getOutGuiaInterprete(): string
	{
		return $this->outGuiaInterprete;
	}

	public function getOutInterpreteLibras(): string
	{
		return $this->outInterpreteLibras;
	}

	public function getOutLeituraLabial(): string
	{
		return $this->outLeituraLabial;
	}

	public function getOutNenhum(): string
	{
		return $this->outNenhum;
	}

	public function getOutProvaAmpliada(): string
	{
		return $this->outProvaAmpliada;
	}

	public function getOutTamanhoFonte(): string
	{
		return $this->outTamanhoFonte;
	}

	public function getOutProvaBraile(): string
	{
		return $this->outProvaBraile;
	}

	public function getOutAuxilioTranscricao(): string
	{
		return $this->outAuxilioTranscricao;
	}

	public function getOutAuxilioLeitor(): string
	{
		return $this->outAuxilioLeitor;
	}

	public function getOutProvaVideoLibras(): string
	{
		return $this->outProvaVideoLibras;
	}

	public function getOutCdAudioDefVisual(): string
	{
		return $this->outCdAudioDefVisual;
	}

	public function getOutProvaLinguaPortuguesa(): string
	{
		return $this->outProvaLinguaPortuguesa;
	}
}