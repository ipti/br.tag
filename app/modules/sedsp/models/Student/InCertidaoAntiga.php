<?php 

class InCertidaoAntiga implements JsonSerializable
{
	public $inNumCertidao;

	public $inLivro;

	public $inFolha;

	public $inDistritoCertidao;

	public $inMunicipioComarca;

	public $inUfComarca;

	public $inDataEmissaoCertidao;

	/**
	 * @param ?string $inNumCertidao
	 * @param ?string $inLivro
	 * @param ?string $inFolha
	 * @param ?string $inDistritoCertidao
	 * @param ?string $inMunicipioComarca
	 * @param ?string $inUfComarca
	 * @param ?string $inDataEmissaoCertidao
	 */
	public function __construct($inCertidaoAntiga) {
		$this->inNumCertidao = $inCertidaoAntiga->inNumCertidao;
		$this->inLivro = $inCertidaoAntiga->inLivro;
		$this->inFolha = $inCertidaoAntiga->inFolha;
		$this->inDistritoCertidao = $inCertidaoAntiga->inDistritoCertidao;
		$this->inMunicipioComarca = $inCertidaoAntiga->inMunicipioComarca;
		$this->inUfComarca = $inCertidaoAntiga->inUfComarca;
		$this->inDataEmissaoCertidao = $inCertidaoAntiga->inDataEmissaoCertidao;
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

	function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }
}
