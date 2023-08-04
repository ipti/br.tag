<?php

class OutDeficiencia
{
	public $outMobilidadeReduzida;
	public $outDescMobilidadeReduzida;
	public $outCuidador;
	public $outDescCuidador;
	public $outProfSaude;
	public $outDescProfSaude;

	public function __construct(
		?string $outMobilidadeReduzida,
		?string $outDescMobilidadeReduzida,
		?string $outCuidador,
		?string $outDescCuidador,
		?string $outProfSaude,
		?string $outDescProfSaude
	) {
		$this->outMobilidadeReduzida = $outMobilidadeReduzida;
		$this->outDescMobilidadeReduzida = $outDescMobilidadeReduzida;
		$this->outCuidador = $outCuidador;
		$this->outDescCuidador = $outDescCuidador;
		$this->outProfSaude = $outProfSaude;
		$this->outDescProfSaude = $outDescProfSaude;
	}

	public function getOutMobilidadeReduzida(): ?string
	{
		return $this->outMobilidadeReduzida;
	}

	public function getOutDescMobilidadeReduzida(): ?string
	{
		return $this->outDescMobilidadeReduzida;
	}

	public function getOutCuidador(): ?string
	{
		return $this->outCuidador;
	}

	public function getOutDescCuidador(): ?string
	{
		return $this->outDescCuidador;
	}

	public function getOutProfSaude(): ?string
	{
		return $this->outProfSaude;
	}

	public function getOutDescProfSaude(): ?string
	{
		return $this->outDescProfSaude;
	}

	public function setOutMobilidadeReduzida(?string $outMobilidadeReduzida): self
	{
		$this->outMobilidadeReduzida = $outMobilidadeReduzida;
		return $this;
	}

	public function setOutDescMobilidadeReduzida(?string $outDescMobilidadeReduzida): self
	{
		$this->outDescMobilidadeReduzida = $outDescMobilidadeReduzida;
		return $this;
	}

	public function setOutCuidador(?string $outCuidador): self
	{
		$this->outCuidador = $outCuidador;
		return $this;
	}

	public function setOutDescCuidador(?string $outDescCuidador): self
	{
		$this->outDescCuidador = $outDescCuidador;
		return $this;
	}

	public function setOutProfSaude(?string $outProfSaude): self
	{
		$this->outProfSaude = $outProfSaude;
		return $this;
	}

	public function setOutDescProfSaude(?string $outDescProfSaude): self
	{
		$this->outDescProfSaude = $outDescProfSaude;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			$data['outMobilidadeReduzida'] ?? null,
			$data['outDescMobilidadeReduzida'] ?? null,
			$data['outCuidador'] ?? null,
			$data['outDescCuidador'] ?? null,
			$data['outProfSaude'] ?? null,
			$data['outDescProfSaude'] ?? null
		);
	}
}