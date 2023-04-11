<?php 
class InRecursoAvaliacao implements JsonSerializable
{
	private $inNenhum;

	private $inAuxilioLeitor;

	private $inAuxilioTranscricao;

	private $inGuiaInterprete;

	private $inInterpreteLibras;

	private $inLeituraLabial;

	private $inProvaBraile;

	private $inProvaAmpliada;

	private $inFonteProva;

	private $inProvaVideoLibras;

	private $inCdAudioDefVisual;

	private $inProvaLinguaPortuguesa;

	/**
	 * @param string|null $inNenhum
	 * @param string|null $inAuxilioLeitor
	 * @param string|null $inAuxilioTranscricao
	 * @param string|null $inGuiaInterprete
	 * @param string|null $inInterpreteLibras
	 * @param string|null $inLeituraLabial
	 * @param string|null $inProvaBraile
	 * @param string|null $inProvaAmpliada
	 * @param string|null $inFonteProva
	 * @param string|null $inProvaVideoLibras
	 * @param string|null $inCdAudioDefVisual
	 * @param string|null $inProvaLinguaPortuguesa
	 */
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

	/**
	 * @param string|null $inNenhum
	 * @return self
	 */
	public function setInNenhum(?string $inNenhum): self
	{
		$this->inNenhum = $inNenhum;
		return $this;
	}

	/**
	 * @param string|null $inAuxilioLeitor
	 * @return self
	 */
	public function setInAuxilioLeitor(?string $inAuxilioLeitor): self
	{
		$this->inAuxilioLeitor = $inAuxilioLeitor;
		return $this;
	}

	/**
	 * @param string|null $inAuxilioTranscricao
	 * @return self
	 */
	public function setInAuxilioTranscricao(?string $inAuxilioTranscricao): self
	{
		$this->inAuxilioTranscricao = $inAuxilioTranscricao;
		return $this;
	}

	/**
	 * @param string|null $inGuiaInterprete
	 * @return self
	 */
	public function setInGuiaInterprete(?string $inGuiaInterprete): self
	{
		$this->inGuiaInterprete = $inGuiaInterprete;
		return $this;
	}

	/**
	 * @param string|null $inInterpreteLibras
	 * @return self
	 */
	public function setInInterpreteLibras(?string $inInterpreteLibras): self
	{
		$this->inInterpreteLibras = $inInterpreteLibras;
		return $this;
	}

	/**
	 * @param string|null $inLeituraLabial
	 * @return self
	 */
	public function setInLeituraLabial(?string $inLeituraLabial): self
	{
		$this->inLeituraLabial = $inLeituraLabial;
		return $this;
	}

	/**
	 * @param string|null $inProvaBraile
	 * @return self
	 */
	public function setInProvaBraile(?string $inProvaBraile): self
	{
		$this->inProvaBraile = $inProvaBraile;
		return $this;
	}

	/**
	 * @param string|null $inProvaAmpliada
	 * @return self
	 */
	public function setInProvaAmpliada(?string $inProvaAmpliada): self
	{
		$this->inProvaAmpliada = $inProvaAmpliada;
		return $this;
	}

	/**
	 * @param string|null $inFonteProva
	 * @return self
	 */
	public function setInFonteProva(?string $inFonteProva): self
	{
		$this->inFonteProva = $inFonteProva;
		return $this;
	}

	/**
	 * @param string|null $inProvaVideoLibras
	 * @return self
	 */
	public function setInProvaVideoLibras(?string $inProvaVideoLibras): self
	{
		$this->inProvaVideoLibras = $inProvaVideoLibras;
		return $this;
	}

	/**
	 * @param string|null $inCdAudioDefVisual
	 * @return self
	 */
	public function setInCdAudioDefVisual(?string $inCdAudioDefVisual): self
	{
		$this->inCdAudioDefVisual = $inCdAudioDefVisual;
		return $this;
	}

	/**
	 * @param string|null $inProvaLinguaPortuguesa
	 * @return self
	 */
	public function setInProvaLinguaPortuguesa(?string $inProvaLinguaPortuguesa): self
	{
		$this->inProvaLinguaPortuguesa = $inProvaLinguaPortuguesa;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			$data['outNenhum'] ?? null,
			$data['outAuxilioLeitor'] ?? null,
			$data['outAuxilioTranscricao'] ?? null,
			$data['outGuiaInterprete'] ?? null,
			$data['outInterpreteLibras'] ?? null,
			$data['outLeituraLabial'] ?? null,
			$data['outProvaBraile'] ?? null,
			$data['outProvaAmpliada'] ?? null,
			$data['outFonteProva'] ?? null,
			$data['outProvaVideoLibras'] ?? null,
			$data['outCdAudioDefVisual'] ?? null,
			$data['outProvaLinguaPortuguesa'] ?? null
		);
	}

	public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}

?>