<?php 

class InCertidaoAntiga implements JsonSerializable
{
	public $inNumCertidao;
	public $inLivro;
	public $inFolha;
	public $inDistritoCertidao;
	public $inMunicipioComarca;
	public $inUFComarca;
	public $inDataEmissaoCertidao;

	public function __construct(
		string $inNumCertidao,
		string $inLivro,
		string $inFolha,
		string $inDistritoCertidao,
		string $inMunicipioComarca,
		string $inUFComarca,
		string $inDataEmissaoCertidao
	) {
		$this->inNumCertidao = $inNumCertidao;
		$this->inLivro = $inLivro;
		$this->inFolha = $inFolha;
		$this->inDistritoCertidao = $inDistritoCertidao;
		$this->inMunicipioComarca = $inMunicipioComarca;
		$this->inUFComarca = $inUFComarca;
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;
	}
	/**
	 * Get the value of inNumCertidao
	 */
	public function getInNumCertidao()
	{
		return $this->inNumCertidao;
	}

	/**
	 * Set the value of inNumCertidao
	 */
	public function setInNumCertidao($inNumCertidao): self
	{
		$this->inNumCertidao = $inNumCertidao;

		return $this;
	}

	/**
	 * Get the value of inLivro
	 */
	public function getInLivro()
	{
		return $this->inLivro;
	}

	/**
	 * Set the value of inLivro
	 */
	public function setInLivro($inLivro): self
	{
		$this->inLivro = $inLivro;

		return $this;
	}

	/**
	 * Get the value of inFolha
	 */
	public function getInFolha()
	{
		return $this->inFolha;
	}

	/**
	 * Set the value of inFolha
	 */
	public function setInFolha($inFolha): self
	{
		$this->inFolha = $inFolha;

		return $this;
	}

	/**
	 * Get the value of inDistritoCertidao
	 */
	public function getInDistritoCertidao()
	{
		return $this->inDistritoCertidao;
	}

	/**
	 * Set the value of inDistritoCertidao
	 */
	public function setInDistritoCertidao($inDistritoCertidao): self
	{
		$this->inDistritoCertidao = $inDistritoCertidao;

		return $this;
	}

	/**
	 * Get the value of inMunicipioComarca
	 */
	public function getInMunicipioComarca()
	{
		return $this->inMunicipioComarca;
	}

	/**
	 * Set the value of inMunicipioComarca
	 */
	public function setInMunicipioComarca($inMunicipioComarca): self
	{
		$this->inMunicipioComarca = $inMunicipioComarca;

		return $this;
	}

	/**
	 * Get the value of inUFComarca
	 */
	public function getInUFComarca()
	{
		return $this->inUFComarca;
	}

	/**
	 * Set the value of inUFComarca
	 */
	public function setInUFComarca($inUFComarca): self
	{
		$this->inUFComarca = $inUFComarca;

		return $this;
	}

	/**
	 * Get the value of inDataEmissaoCertidao
	 */
	public function getInDataEmissaoCertidao()
	{
		return $this->inDataEmissaoCertidao;
	}

	/**
	 * Set the value of inDataEmissaoCertidao
	 */
	public function setInDataEmissaoCertidao($inDataEmissaoCertidao): self
	{
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;

		return $this;
	}

	function jsonSerialize()
	{
		return get_object_vars($this);
	}
}
