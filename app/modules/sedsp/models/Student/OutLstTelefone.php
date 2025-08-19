<?php

class OutLstTelefone
{
	public $outDDDNumero;
	public $outNumero;
	public $outTipoTelefone;
	public $outDescTipoTelefone;
	public $outComplemento;
	public $outSMS;

	public function __construct(
		?string $outDDDNumero,
		?string $outNumero,
		?string $outTipoTelefone,
		?string $outDescTipoTelefone,
		?string $outComplemento,
		?string $outSMS
	) {
		$this->outDDDNumero = $outDDDNumero;
		$this->outNumero = $outNumero;
		$this->outTipoTelefone = $outTipoTelefone;
		$this->outDescTipoTelefone = $outDescTipoTelefone;
		$this->outComplemento = $outComplemento;
		$this->outSMS = $outSMS;
	}

	public function getOutDddNumero(): ?string
	{
		return $this->outDDDNumero;
	}

	public function getOutNumero(): ?string
	{
		return $this->outNumero;
	}

	public function getOutTipoTelefone(): ?string
	{
		return $this->outTipoTelefone;
	}

	public function getOutDescTipoTelefone(): ?string
	{
		return $this->outDescTipoTelefone;
	}

	public function getOutComplemento(): ?string
	{
		return $this->outComplemento;
	}

	public function getOutSms(): ?string
	{
		return $this->outSMS;
	}

	public function setOutDddNumero(?string $outDDDNumero): self
	{
		$this->outDDDNumero = $outDDDNumero;
		return $this;
	}

	public function setOutNumero(?string $outNumero): self
	{
		$this->outNumero = $outNumero;
		return $this;
	}

	public function setOutTipoTelefone(?string $outTipoTelefone): self
	{
		$this->outTipoTelefone = $outTipoTelefone;
		return $this;
	}

	public function setOutDescTipoTelefone(?string $outDescTipoTelefone): self
	{
		$this->outDescTipoTelefone = $outDescTipoTelefone;
		return $this;
	}

	public function setOutComplemento(?string $outComplemento): self
	{
		$this->outComplemento = $outComplemento;
		return $this;
	}

	public function setOutSms(?string $outSMS): self
	{
		$this->outSMS = $outSMS;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outDDDNumero'] ?? null,
			$data['outNumero'] ?? null,
			$data['outTipoTelefone'] ?? null,
			$data['outDescTipoTelefone'] ?? null,
			$data['outComplemento'] ?? null,
			$data['outSMS'] ?? null
		);
	}
}