<?php


class InTelefone implements JsonSerializable
{
	public ?string $inTipoTelefone;
	public ?string $inDDDNumero;
	public ?string $inNumero;
	public ?string $inComplemento;
	public ?int $inSMS;

	public function __construct(
		?string $inTipoTelefone,
		?string $inDDDNumero,
		?string $inNumero,
		?string $inComplemento,
		?int $inSMS
	) {
		$this->inTipoTelefone = $inTipoTelefone;
		$this->inDDDNumero = $inDDDNumero;
		$this->inNumero = $inNumero;
		$this->inComplemento = $inComplemento;
		$this->inSMS = $inSMS;
	}

	public function getInTipoTelefone(): ?string
	{
		return $this->inTipoTelefone;
	}

	public function getInDddNumero(): ?string
	{
		return $this->inDDDNumero;
	}

	public function getInNumero(): ?string
	{
		return $this->inNumero;
	}

	public function getInComplemento(): ?string
	{
		return $this->inComplemento;
	}

	public function getInSms(): ?int
	{
		return $this->inSMS;
	}

	public function setInTipoTelefone(?string $inTipoTelefone): self
	{
		$this->inTipoTelefone = $inTipoTelefone;
		return $this;
	}

	public function setInDddNumero(?string $inDDDNumero): self
	{
		$this->inDDDNumero = $inDDDNumero;
		return $this;
	}

	public function setInNumero(?string $inNumero): self
	{
		$this->inNumero = $inNumero;
		return $this;
	}

	public function setInComplemento(?string $inComplemento): self
	{
		$this->inComplemento = $inComplemento;
		return $this;
	}

	public function setInSms(?int $inSMS): self
	{
		$this->inSMS = $inSMS;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inTipoTelefone'] ?? null,
			$data['inDDDNumero'] ?? null,
			$data['inNumero'] ?? null,
			$data['inComplemento'] ?? null,
			$data['inSMS'] ?? null
		);
	}

	public function jsonSerialize()
	{
		return get_object_vars($this);
	}
}


