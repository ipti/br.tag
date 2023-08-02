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
		?string $outGuiaInterprete,
		?string $outInterpreteLibras,
		?string $outLeituraLabial,
		?string $outNenhum,
		?string $outProvaAmpliada,
		?string $outTamanhoFonte,
		?string $outProvaBraile,
		?string $outAuxilioTranscricao,
		?string $outAuxilioLeitor,
		?string $outProvaVideoLibras,
		?string $outCdAudioDefVisual,
		?string $outProvaLinguaPortuguesa
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

	public function getOutGuiaInterprete(): ?string
	{
		return $this->outGuiaInterprete;
	}

	public function getOutInterpreteLibras(): ?string
	{
		return $this->outInterpreteLibras;
	}

	public function getOutLeituraLabial(): ?string
	{
		return $this->outLeituraLabial;
	}

	public function getOutNenhum(): ?string
	{
		return $this->outNenhum;
	}

	public function getOutProvaAmpliada(): ?string
	{
		return $this->outProvaAmpliada;
	}

	public function getOutTamanhoFonte(): ?string
	{
		return $this->outTamanhoFonte;
	}

	public function getOutProvaBraile(): ?string
	{
		return $this->outProvaBraile;
	}

	public function getOutAuxilioTranscricao(): ?string
	{
		return $this->outAuxilioTranscricao;
	}

	public function getOutAuxilioLeitor(): ?string
	{
		return $this->outAuxilioLeitor;
	}

	public function getOutProvaVideoLibras(): ?string
	{
		return $this->outProvaVideoLibras;
	}

	public function getOutCdAudioDefVisual(): ?string
	{
		return $this->outCdAudioDefVisual;
	}

	public function getOutProvaLinguaPortuguesa(): ?string
	{
		return $this->outProvaLinguaPortuguesa;
	}

	public function setOutGuiaInterprete(?string $outGuiaInterprete): self
	{
		$this->outGuiaInterprete = $outGuiaInterprete;
		return $this;
	}

	public function setOutInterpreteLibras(?string $outInterpreteLibras): self
	{
		$this->outInterpreteLibras = $outInterpreteLibras;
		return $this;
	}

	public function setOutLeituraLabial(?string $outLeituraLabial): self
	{
		$this->outLeituraLabial = $outLeituraLabial;
		return $this;
	}

	public function setOutNenhum(?string $outNenhum): self
	{
		$this->outNenhum = $outNenhum;
		return $this;
	}

	public function setOutProvaAmpliada(?string $outProvaAmpliada): self
	{
		$this->outProvaAmpliada = $outProvaAmpliada;
		return $this;
	}

	public function setOutTamanhoFonte(?string $outTamanhoFonte): self
	{
		$this->outTamanhoFonte = $outTamanhoFonte;
		return $this;
	}

	public function setOutProvaBraile(?string $outProvaBraile): self
	{
		$this->outProvaBraile = $outProvaBraile;
		return $this;
	}

	public function setOutAuxilioTranscricao(?string $outAuxilioTranscricao): self
	{
		$this->outAuxilioTranscricao = $outAuxilioTranscricao;
		return $this;
	}

	public function setOutAuxilioLeitor(?string $outAuxilioLeitor): self
	{
		$this->outAuxilioLeitor = $outAuxilioLeitor;
		return $this;
	}

	public function setOutProvaVideoLibras(?string $outProvaVideoLibras): self
	{
		$this->outProvaVideoLibras = $outProvaVideoLibras;
		return $this;
	}

	public function setOutCdAudioDefVisual(?string $outCdAudioDefVisual): self
	{
		$this->outCdAudioDefVisual = $outCdAudioDefVisual;
		return $this;
	}

	public function setOutProvaLinguaPortuguesa(?string $outProvaLinguaPortuguesa): self
	{
		$this->outProvaLinguaPortuguesa = $outProvaLinguaPortuguesa;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			$data['outGuiaInterprete'] ?? null,
			$data['outInterpreteLibras'] ?? null,
			$data['outLeituraLabial'] ?? null,
			$data['outNenhum'] ?? null,
			$data['outProvaAmpliada'] ?? null,
			$data['outTamanhoFonte'] ?? null,
			$data['outProvaBraile'] ?? null,
			$data['outAuxilioTranscricao'] ?? null,
			$data['outAuxilioLeitor'] ?? null,
			$data['outProvaVideoLibras'] ?? null,
			$data['outCdAudioDefVisual'] ?? null,
			$data['outProvaLinguaPortuguesa'] ?? null
		);
	}
}