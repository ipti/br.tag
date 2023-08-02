<?php

class OutRecursoAvaliacao
{
    public $outGuiaInterprete;
	public $outInterpreteLibras;
	public $outLeituraLabial;
	public $outNenhum;
	public $outProvaAmpliada;
	public $outTamanhoFonte;
	public $outProvaBraile;
	public $outAuxilioTranscricao;
	public $outAuxilioLeitor;
	public $outProvaVideoLibras;
	public $outCdAudioDefVisual;
	public $outProvaLinguaPortuguesa;

	public function __construct(
		string $outGuiaInterprete,
		string $outInterpreteLibras,
		string $outLeituraLabial,
		string $outNenhum,
		string $outProvaAmpliada,
		string $outTamanhoFonte,
		string $outProvaBraile,
		string $outAuxilioTranscricao,
		string $outAuxilioLeitor,
		string $outProvaVideoLibras,
		string $outCdAudioDefVisual,
		string $outProvaLinguaPortuguesa
	) {
		$this->outGuiaInterprete = $outGuiaInterprete;
		$this->outInterpreteLibras = $outInterpreteLibras;
		$this->outLeituraLabial = $outLeituraLabial;
		$this->outNenhum = $outNenhum;
		$this->outProvaAmpliada = $outProvaAmpliada;
		$this->outTamanhoFonte = $outTamanhoFonte;
		$this->outProvaBraile = $outProvaBraile;
		$this->outAuxilioTranscricao = $outAuxilioTranscricao;
		$this->outAuxilioLeitor = $outAuxilioLeitor;
		$this->outProvaVideoLibras = $outProvaVideoLibras;
		$this->outCdAudioDefVisual = $outCdAudioDefVisual;
		$this->outProvaLinguaPortuguesa = $outProvaLinguaPortuguesa;
	}

    /**
     * Get the value of outGuiaInterprete
     */
    public function getOutGuiaInterprete()
    {
        return $this->outGuiaInterprete;
    }

    /**
     * Set the value of outGuiaInterprete
     */
    public function setOutGuiaInterprete($outGuiaInterprete): self
    {
        $this->outGuiaInterprete = $outGuiaInterprete;

        return $this;
    }

	/**
	 * Get the value of outInterpreteLibras
	 */
	public function getOutInterpreteLibras()
	{
		return $this->outInterpreteLibras;
	}

	/**
	 * Set the value of outInterpreteLibras
	 */
	public function setOutInterpreteLibras($outInterpreteLibras): self
	{
		$this->outInterpreteLibras = $outInterpreteLibras;

		return $this;
	}

	/**
	 * Get the value of outLeituraLabial
	 */
	public function getOutLeituraLabial()
	{
		return $this->outLeituraLabial;
	}

	/**
	 * Set the value of outLeituraLabial
	 */
	public function setOutLeituraLabial($outLeituraLabial): self
	{
		$this->outLeituraLabial = $outLeituraLabial;

		return $this;
	}

	/**
	 * Get the value of outNenhum
	 */
	public function getOutNenhum()
	{
		return $this->outNenhum;
	}

	/**
	 * Set the value of outNenhum
	 */
	public function setOutNenhum($outNenhum): self
	{
		$this->outNenhum = $outNenhum;

		return $this;
	}

	/**
	 * Get the value of outProvaAmpliada
	 */
	public function getOutProvaAmpliada()
	{
		return $this->outProvaAmpliada;
	}

	/**
	 * Set the value of outProvaAmpliada
	 */
	public function setOutProvaAmpliada($outProvaAmpliada): self
	{
		$this->outProvaAmpliada = $outProvaAmpliada;

		return $this;
	}

	/**
	 * Get the value of outTamanhoFonte
	 */
	public function getOutTamanhoFonte()
	{
		return $this->outTamanhoFonte;
	}

	/**
	 * Set the value of outTamanhoFonte
	 */
	public function setOutTamanhoFonte($outTamanhoFonte): self
	{
		$this->outTamanhoFonte = $outTamanhoFonte;

		return $this;
	}

	/**
	 * Get the value of outProvaBraile
	 */
	public function getOutProvaBraile()
	{
		return $this->outProvaBraile;
	}

	/**
	 * Set the value of outProvaBraile
	 */
	public function setOutProvaBraile($outProvaBraile): self
	{
		$this->outProvaBraile = $outProvaBraile;

		return $this;
	}

	/**
	 * Get the value of outAuxilioTranscricao
	 */
	public function getOutAuxilioTranscricao()
	{
		return $this->outAuxilioTranscricao;
	}

	/**
	 * Set the value of outAuxilioTranscricao
	 */
	public function setOutAuxilioTranscricao($outAuxilioTranscricao): self
	{
		$this->outAuxilioTranscricao = $outAuxilioTranscricao;

		return $this;
	}

	/**
	 * Get the value of outAuxilioLeitor
	 */
	public function getOutAuxilioLeitor()
	{
		return $this->outAuxilioLeitor;
	}

	/**
	 * Set the value of outAuxilioLeitor
	 */
	public function setOutAuxilioLeitor($outAuxilioLeitor): self
	{
		$this->outAuxilioLeitor = $outAuxilioLeitor;

		return $this;
	}

	/**
	 * Get the value of outProvaVideoLibras
	 */
	public function getOutProvaVideoLibras()
	{
		return $this->outProvaVideoLibras;
	}

	/**
	 * Set the value of outProvaVideoLibras
	 */
	public function setOutProvaVideoLibras($outProvaVideoLibras): self
	{
		$this->outProvaVideoLibras = $outProvaVideoLibras;

		return $this;
	}

	/**
	 * Get the value of outCdAudioDefVisual
	 */
	public function getOutCdAudioDefVisual()
	{
		return $this->outCdAudioDefVisual;
	}

	/**
	 * Set the value of outCdAudioDefVisual
	 */
	public function setOutCdAudioDefVisual($outCdAudioDefVisual): self
	{
		$this->outCdAudioDefVisual = $outCdAudioDefVisual;

		return $this;
	}

	/**
	 * Get the value of outProvaLinguaPortuguesa
	 */
	public function getOutProvaLinguaPortuguesa()
	{
		return $this->outProvaLinguaPortuguesa;
	}

	/**
	 * Set the value of outProvaLinguaPortuguesa
	 */
	public function setOutProvaLinguaPortuguesa($outProvaLinguaPortuguesa): self
	{
		$this->outProvaLinguaPortuguesa = $outProvaLinguaPortuguesa;

		return $this;
	}
}