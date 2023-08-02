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

	public function __construct(
		string $inNenhum,
		string $inAuxilioLeitor,
		string $inAuxilioTranscricao,
		string $inGuiaInterprete,
		string $inInterpreteLibras,
		string $inLeituraLabial,
		string $inProvaBraile,
		string $inProvaAmpliada,
		string $inFonteProva,
		string $inProvaVideoLibras,
		string $inCdAudioDefVisual,
		string $inProvaLinguaPortuguesa
	) {
		$this->inNenhum = $inNenhum;
		$this->inAuxilioLeitor = $inAuxilioLeitor;
		$this->inAuxilioTranscricao = $inAuxilioTranscricao;
		$this->inGuiaInterprete = $inGuiaInterprete;
		$this->inInterpreteLibras = $inInterpreteLibras;
		$this->inLeituraLabial = $inLeituraLabial;
		$this->inProvaBraile = $inProvaBraile;
		$this->inProvaAmpliada = $inProvaAmpliada;
		$this->inFonteProva = $inFonteProva;
		$this->inProvaVideoLibras = $inProvaVideoLibras;
		$this->inCdAudioDefVisual = $inCdAudioDefVisual;
		$this->inProvaLinguaPortuguesa = $inProvaLinguaPortuguesa;
	}

    function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }

	/**
	 * Get the value of inNenhum
	 */
	public function getInNenhum()
	{
		return $this->inNenhum;
	}

	/**
	 * Set the value of inNenhum
	 */
	public function setInNenhum($inNenhum): self
	{
		$this->inNenhum = $inNenhum;

		return $this;
	}

	/**
	 * Get the value of inAuxilioLeitor
	 */
	public function getInAuxilioLeitor()
	{
		return $this->inAuxilioLeitor;
	}

	/**
	 * Set the value of inAuxilioLeitor
	 */
	public function setInAuxilioLeitor($inAuxilioLeitor): self
	{
		$this->inAuxilioLeitor = $inAuxilioLeitor;

		return $this;
	}

	/**
	 * Get the value of inAuxilioTranscricao
	 */
	public function getInAuxilioTranscricao()
	{
		return $this->inAuxilioTranscricao;
	}

	/**
	 * Set the value of inAuxilioTranscricao
	 */
	public function setInAuxilioTranscricao($inAuxilioTranscricao): self
	{
		$this->inAuxilioTranscricao = $inAuxilioTranscricao;

		return $this;
	}

	/**
	 * Get the value of inGuiaInterprete
	 */
	public function getInGuiaInterprete()
	{
		return $this->inGuiaInterprete;
	}

	/**
	 * Set the value of inGuiaInterprete
	 */
	public function setInGuiaInterprete($inGuiaInterprete): self
	{
		$this->inGuiaInterprete = $inGuiaInterprete;

		return $this;
	}

	/**
	 * Get the value of inInterpreteLibras
	 */
	public function getInInterpreteLibras()
	{
		return $this->inInterpreteLibras;
	}

	/**
	 * Set the value of inInterpreteLibras
	 */
	public function setInInterpreteLibras($inInterpreteLibras): self
	{
		$this->inInterpreteLibras = $inInterpreteLibras;

		return $this;
	}

	/**
	 * Get the value of inLeituraLabial
	 */
	public function getInLeituraLabial()
	{
		return $this->inLeituraLabial;
	}

	/**
	 * Set the value of inLeituraLabial
	 */
	public function setInLeituraLabial($inLeituraLabial): self
	{
		$this->inLeituraLabial = $inLeituraLabial;

		return $this;
	}

	/**
	 * Get the value of inProvaBraile
	 */
	public function getInProvaBraile()
	{
		return $this->inProvaBraile;
	}

	/**
	 * Set the value of inProvaBraile
	 */
	public function setInProvaBraile($inProvaBraile): self
	{
		$this->inProvaBraile = $inProvaBraile;

		return $this;
	}

	/**
	 * Get the value of inProvaAmpliada
	 */
	public function getInProvaAmpliada()
	{
		return $this->inProvaAmpliada;
	}

	/**
	 * Set the value of inProvaAmpliada
	 */
	public function setInProvaAmpliada($inProvaAmpliada): self
	{
		$this->inProvaAmpliada = $inProvaAmpliada;

		return $this;
	}

	/**
	 * Get the value of inFonteProva
	 */
	public function getInFonteProva()
	{
		return $this->inFonteProva;
	}

	/**
	 * Set the value of inFonteProva
	 */
	public function setInFonteProva($inFonteProva): self
	{
		$this->inFonteProva = $inFonteProva;

		return $this;
	}

	/**
	 * Get the value of inProvaVideoLibras
	 */
	public function getInProvaVideoLibras()
	{
		return $this->inProvaVideoLibras;
	}

	/**
	 * Set the value of inProvaVideoLibras
	 */
	public function setInProvaVideoLibras($inProvaVideoLibras): self
	{
		$this->inProvaVideoLibras = $inProvaVideoLibras;

		return $this;
	}

	/**
	 * Get the value of inCdAudioDefVisual
	 */
	public function getInCdAudioDefVisual()
	{
		return $this->inCdAudioDefVisual;
	}

	/**
	 * Set the value of inCdAudioDefVisual
	 */
	public function setInCdAudioDefVisual($inCdAudioDefVisual): self
	{
		$this->inCdAudioDefVisual = $inCdAudioDefVisual;

		return $this;
	}

	/**
	 * Get the value of inProvaLinguaPortuguesa
	 */
	public function getInProvaLinguaPortuguesa()
	{
		return $this->inProvaLinguaPortuguesa;
	}

	/**
	 * Set the value of inProvaLinguaPortuguesa
	 */
	public function setInProvaLinguaPortuguesa($inProvaLinguaPortuguesa): self
	{
		$this->inProvaLinguaPortuguesa = $inProvaLinguaPortuguesa;

		return $this;
	}
}