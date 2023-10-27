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
		?string $inNenhum = null,
		?string $inAuxilioLeitor = null,
		?string $inAuxilioTranscricao = null,
		?string $inGuiaInterprete = null,
		?string $inInterpreteLibras = null,
		?string $inLeituraLabial = null,
		?string $inProvaBraile = null,
		?string $inProvaAmpliada = null,
		?string $inFonteProva = null,
		?string $inProvaVideoLibras = null,
		?string $inCdAudioDefVisual = null,
		?string $inProvaLinguaPortuguesa = null
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

	public function getInNenhum(): ?string
	{
		return $this->inNenhum;
	}

	public function getInAuxilioLeitor(): ?string
	{
		return $this->inAuxilioLeitor;
	}

	public function getInAuxilioTranscricao(): ?string
	{
		return $this->inAuxilioTranscricao;
	}

	public function getInGuiaInterprete(): ?string
	{
		return $this->inGuiaInterprete;
	}

	public function getInInterpreteLibras(): ?string
	{
		return $this->inInterpreteLibras;
	}

	public function getInLeituraLabial(): ?string
	{
		return $this->inLeituraLabial;
	}

	public function getInProvaBraile(): ?string
	{
		return $this->inProvaBraile;
	}

	public function getInProvaAmpliada(): ?string
	{
		return $this->inProvaAmpliada;
	}

	public function getInFonteProva(): ?string
	{
		return $this->inFonteProva;
	}

	public function getInProvaVideoLibras(): ?string
	{
		return $this->inProvaVideoLibras;
	}

	public function getInCdAudioDefVisual(): ?string
	{
		return $this->inCdAudioDefVisual;
	}

	public function getInProvaLinguaPortuguesa(): ?string
	{
		return $this->inProvaLinguaPortuguesa;
	}

	public function setInNenhum(?string $inNenhum): self
	{
		$this->inNenhum = $inNenhum;
		return $this;
	}

	public function setInAuxilioLeitor(?string $inAuxilioLeitor): self
	{
		$this->inAuxilioLeitor = $inAuxilioLeitor;
		return $this;
	}

	public function setInAuxilioTranscricao(?string $inAuxilioTranscricao): self
	{
		$this->inAuxilioTranscricao = $inAuxilioTranscricao;
		return $this;
	}

	public function setInGuiaInterprete(?string $inGuiaInterprete): self
	{
		$this->inGuiaInterprete = $inGuiaInterprete;
		return $this;
	}

	public function setInInterpreteLibras(?string $inInterpreteLibras): self
	{
		$this->inInterpreteLibras = $inInterpreteLibras;
		return $this;
	}

	public function setInLeituraLabial(?string $inLeituraLabial): self
	{
		$this->inLeituraLabial = $inLeituraLabial;
		return $this;
	}

	public function setInProvaBraile(?string $inProvaBraile): self
	{
		$this->inProvaBraile = $inProvaBraile;
		return $this;
	}

	public function setInProvaAmpliada(?string $inProvaAmpliada): self
	{
		$this->inProvaAmpliada = $inProvaAmpliada;
		return $this;
	}

	public function setInFonteProva(?string $inFonteProva): self
	{
		$this->inFonteProva = $inFonteProva;
		return $this;
	}

	public function setInProvaVideoLibras(?string $inProvaVideoLibras): self
	{
		$this->inProvaVideoLibras = $inProvaVideoLibras;
		return $this;
	}

	public function setInCdAudioDefVisual(?string $inCdAudioDefVisual): self
	{
		$this->inCdAudioDefVisual = $inCdAudioDefVisual;
		return $this;
	}

	public function setInProvaLinguaPortuguesa(?string $inProvaLinguaPortuguesa): self
	{
		$this->inProvaLinguaPortuguesa = $inProvaLinguaPortuguesa;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inNenhum'] ?? null,
			$data['inAuxilioLeitor'] ?? null,
			$data['inAuxilioTranscricao'] ?? null,
			$data['inGuiaInterprete'] ?? null,
			$data['inInterpreteLibras'] ?? null,
			$data['inLeituraLabial'] ?? null,
			$data['inProvaBraile'] ?? null,
			$data['inProvaAmpliada'] ?? null,
			$data['inFonteProva'] ?? null,
			$data['inProvaVideoLibras'] ?? null,
			$data['inCdAudioDefVisual'] ?? null,
			$data['inProvaLinguaPortuguesa'] ?? null
		);
	}

	public function jsonSerialize()
	{
		return get_object_vars($this);
	}
}