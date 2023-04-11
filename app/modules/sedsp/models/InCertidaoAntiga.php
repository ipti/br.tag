<?php 

class InCertidaoAntiga implements JsonSerializable
{
	private $inNumCertidao;

	private $inLivro;

	private $inFolha;

	private $inDistritoCertidao;

	private $inMunicipioComarca;

	private $inUfComarca;

	private $inDataEmissaoCertidao;

	/**
	 * @param string|null $inNumCertidao
	 * @param string|null $inLivro
	 * @param string|null $inFolha
	 * @param string|null $inDistritoCertidao
	 * @param string|null $inMunicipioComarca
	 * @param string|null $inUfComarca
	 * @param string|null $inDataEmissaoCertidao
	 */
	public function __construct(
		?string $inNumCertidao,
		?string $inLivro,
		?string $inFolha,
		?string $inDistritoCertidao,
		?string $inMunicipioComarca,
		?string $inUfComarca,
		?string $inDataEmissaoCertidao
	) {
		$this->inNumCertidao = $inNumCertidao;
		$this->inLivro = $inLivro;
		$this->inFolha = $inFolha;
		$this->inDistritoCertidao = $inDistritoCertidao;
		$this->inMunicipioComarca = $inMunicipioComarca;
		$this->inUfComarca = $inUfComarca;
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;
	}

	/**
	 * @param string|null $inNumCertidao
	 * @return self
	 */
	public function setInNumCertidao(?string $inNumCertidao): self
	{
		$this->inNumCertidao = $inNumCertidao;
		return $this;
	}

	/**
	 * @param string|null $inLivro
	 * @return self
	 */
	public function setInLivro(?string $inLivro): self
	{
		$this->inLivro = $inLivro;
		return $this;
	}

	/**
	 * @param string|null $inFolha
	 * @return self
	 */
	public function setInFolha(?string $inFolha): self
	{
		$this->inFolha = $inFolha;
		return $this;
	}

	/**
	 * @param string|null $inDistritoCertidao
	 * @return self
	 */
	public function setInDistritoCertidao(?string $inDistritoCertidao): self
	{
		$this->inDistritoCertidao = $inDistritoCertidao;
		return $this;
	}

	/**
	 * @param string|null $inMunicipioComarca
	 * @return self
	 */
	public function setInMunicipioComarca(?string $inMunicipioComarca): self
	{
		$this->inMunicipioComarca = $inMunicipioComarca;
		return $this;
	}

	/**
	 * @param string|null $inUfComarca
	 * @return self
	 */
	public function setInUfComarca(?string $inUfComarca): self
	{
		$this->inUfComarca = $inUfComarca;
		return $this;
	}

	/**
	 * @param string|null $inDataEmissaoCertidao
	 * @return self
	 */
	public function setInDataEmissaoCertidao(?string $inDataEmissaoCertidao): self
	{
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			$data['outNumCertidao'] ?? null,
			$data['outLivro'] ?? null,
			$data['outFolha'] ?? null,
			$data['outDistritoCertidao'] ?? null,
			$data['outMunicipioComarca'] ?? null,
			$data['outUFComarca'] ?? null,
			$data['outDataEmissaoCertidao'] ?? null
		);
	}

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}

?>