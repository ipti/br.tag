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
		?string $inNumCertidao,
		?string $inLivro,
		?string $inFolha,
		?string $inDistritoCertidao,
		?string $inMunicipioComarca,
		?string $inUFComarca,
		?string $inDataEmissaoCertidao
	) {
		$this->inNumCertidao = $inNumCertidao;
		$this->inLivro = $inLivro;
		$this->inFolha = $inFolha;
		$this->inDistritoCertidao = $inDistritoCertidao;
		$this->inMunicipioComarca = $inMunicipioComarca;
		$this->inUFComarca = $inUFComarca;
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;
	}

	public function getInNumCertidao(): ?string
	{
		return $this->inNumCertidao;
	}

	public function getInLivro(): ?string
	{
		return $this->inLivro;
	}

	public function getInFolha(): ?string
	{
		return $this->inFolha;
	}

	public function getInDistritoCertidao(): ?string
	{
		return $this->inDistritoCertidao;
	}

	public function getInMunicipioComarca(): ?string
	{
		return $this->inMunicipioComarca;
	}

	public function getInUfComarca(): ?string
	{
		return $this->inUFComarca;
	}

	public function getInDataEmissaoCertidao(): ?string
	{
		return $this->inDataEmissaoCertidao;
	}

	public function setInNumCertidao(?string $inNumCertidao): self
	{
		$this->inNumCertidao = $inNumCertidao;
		return $this;
	}

	public function setInLivro(?string $inLivro): self
	{
		$this->inLivro = $inLivro;
		return $this;
	}

	public function setInFolha(?string $inFolha): self
	{
		$this->inFolha = $inFolha;
		return $this;
	}

	public function setInDistritoCertidao(?string $inDistritoCertidao): self
	{
		$this->inDistritoCertidao = $inDistritoCertidao;
		return $this;
	}

	public function setInMunicipioComarca(?string $inMunicipioComarca): self
	{
		$this->inMunicipioComarca = $inMunicipioComarca;
		return $this;
	}

	public function setInUfComarca(?string $inUFComarca): self
	{
		$this->inUFComarca = $inUFComarca;
		return $this;
	}

	public function setInDataEmissaoCertidao(?string $inDataEmissaoCertidao): self
	{
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inNumCertidao'] ?? null,
			$data['inLivro'] ?? null,
			$data['inFolha'] ?? null,
			$data['inDistritoCertidao'] ?? null,
			$data['inMunicipioComarca'] ?? null,
			$data['inUFComarca'] ?? null,
			$data['inDataEmissaoCertidao'] ?? null
		);
	}
	public function jsonSerialize()
	{
		return get_object_vars($this);
	}
}
