<?php

class InRecursoAvaliacao
{
	public $inNenhum;
	public $inAuxilioLeitor;
	public $inAuxilioTranscricao;
	public $inGuiaInterprete;
	public $inInterpreteLibras;
	public $inLeituraLabial;
	public $inProvaBraile;
	public $inProvaAmpliada;
	public $inFonteProva;
	public $inProvaVideoLibras;
	public $inCdAudioDefVisual;
	public $inProvaLinguaPortuguesa;

	public function __construct($inRecursoAvaliacao) {
        $inRecursoAvaliacao = (object) $inRecursoAvaliacao;
		$this->inNenhum = $inRecursoAvaliacao->inNenhum;
		$this->inAuxilioLeitor = $inRecursoAvaliacao->inAuxilioLeitor;
		$this->inAuxilioTranscricao = $inRecursoAvaliacao->inAuxilioTranscricao;
		$this->inGuiaInterprete = $inRecursoAvaliacao->inGuiaInterprete;
		$this->inInterpreteLibras = $inRecursoAvaliacao->inInterpreteLibras;
		$this->inLeituraLabial = $inRecursoAvaliacao->inLeituraLabial;
		$this->inProvaBraile = $inRecursoAvaliacao->inProvaBraile;
		$this->inProvaAmpliada = $inRecursoAvaliacao->inProvaAmpliada;
		$this->inFonteProva = $inRecursoAvaliacao->inFonteProva;
		$this->inProvaVideoLibras = $inRecursoAvaliacao->inProvaVideoLibras;
		$this->inCdAudioDefVisual = $inRecursoAvaliacao->inCdAudioDefVisual;
		$this->inProvaLinguaPortuguesa = $inRecursoAvaliacao->inProvaLinguaPortuguesa;
	}

	public function getInNenhum(): string
	{
		return $this->inNenhum;
	}

	public function getInAuxilioLeitor(): string
	{
		return $this->inAuxilioLeitor;
	}

	public function getInAuxilioTranscricao(): string
	{
		return $this->inAuxilioTranscricao;
	}

	public function getInGuiaInterprete(): string
	{
		return $this->inGuiaInterprete;
	}

	public function getInInterpreteLibras(): string
	{
		return $this->inInterpreteLibras;
	}

	public function getInLeituraLabial(): string
	{
		return $this->inLeituraLabial;
	}

	public function getInProvaBraile(): string
	{
		return $this->inProvaBraile;
	}

	public function getInProvaAmpliada(): string
	{
		return $this->inProvaAmpliada;
	}

	public function getInFonteProva(): string
	{
		return $this->inFonteProva;
	}

	public function getInProvaVideoLibras(): string
	{
		return $this->inProvaVideoLibras;
	}

	public function getInCdAudioDefVisual(): string
	{
		return $this->inCdAudioDefVisual;
	}

	public function getInProvaLinguaPortuguesa(): string
	{
		return $this->inProvaLinguaPortuguesa;
	}

    function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }
}