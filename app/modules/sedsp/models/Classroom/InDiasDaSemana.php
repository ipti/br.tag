<?php

class InDiasDaSemana implements JsonSerializable
{
	public $inFlagSegunda;
	public $inHoraIniAulaSegunda;
	public $inHoraFimAulaSegunda;
	public $inFlagTerca;
	public $inHoraIniAulaTerca;
	public $inHoraFimAulaTerca;
	public $inFlagQuarta;
	public $inHoraIniAulaQuarta;
	public $inHoraFimAulaQuarta;
	public $inFlagQuinta;
	public $inHoraIniAulaQuinta;
	public $inHoraFimAulaQuinta;
	public $inFlagSexta;
	public $inHoraIniAulaSexta;
	public $inHoraFimAulaSexta;
	public $inFlagSabado;
	public $inHoraIniAulaSabado;
	public $inHoraFimAulaSabado;

	public function __construct(
		?string $inFlagSegunda,
		?string $inHoraIniAulaSegunda,
		?string $inHoraFimAulaSegunda,
		?string $inFlagTerca,
		?string $inHoraIniAulaTerca,
		?string $inHoraFimAulaTerca,
		?string $inFlagQuarta,
		?string $inHoraIniAulaQuarta,
		?string $inHoraFimAulaQuarta,
		?string $inFlagQuinta,
		?string $inHoraIniAulaQuinta,
		?string $inHoraFimAulaQuinta,
		?string $inFlagSexta,
		?string $inHoraIniAulaSexta,
		?string $inHoraFimAulaSexta,
		?string $inFlagSabado,
		?string $inHoraIniAulaSabado,
		?string $inHoraFimAulaSabado
	) {
		$this->inFlagSegunda = $inFlagSegunda;
		$this->inHoraIniAulaSegunda = $inHoraIniAulaSegunda;
		$this->inHoraFimAulaSegunda = $inHoraFimAulaSegunda;
		$this->inFlagTerca = $inFlagTerca;
		$this->inHoraIniAulaTerca = $inHoraIniAulaTerca;
		$this->inHoraFimAulaTerca = $inHoraFimAulaTerca;
		$this->inFlagQuarta = $inFlagQuarta;
		$this->inHoraIniAulaQuarta = $inHoraIniAulaQuarta;
		$this->inHoraFimAulaQuarta = $inHoraFimAulaQuarta;
		$this->inFlagQuinta = $inFlagQuinta;
		$this->inHoraIniAulaQuinta = $inHoraIniAulaQuinta;
		$this->inHoraFimAulaQuinta = $inHoraFimAulaQuinta;
		$this->inFlagSexta = $inFlagSexta;
		$this->inHoraIniAulaSexta = $inHoraIniAulaSexta;
		$this->inHoraFimAulaSexta = $inHoraFimAulaSexta;
		$this->inFlagSabado = $inFlagSabado;
		$this->inHoraIniAulaSabado = $inHoraIniAulaSabado;
		$this->inHoraFimAulaSabado = $inHoraFimAulaSabado;
	}

	public function getInFlagSegunda(): ?string
	{
		return $this->inFlagSegunda;
	}

	public function getInHoraIniAulaSegunda(): ?string
	{
		return $this->inHoraIniAulaSegunda;
	}

	public function getInHoraFimAulaSegunda(): ?string
	{
		return $this->inHoraFimAulaSegunda;
	}

	public function getInFlagTerca(): ?string
	{
		return $this->inFlagTerca;
	}

	public function getInHoraIniAulaTerca(): ?string
	{
		return $this->inHoraIniAulaTerca;
	}

	public function getInHoraFimAulaTerca(): ?string
	{
		return $this->inHoraFimAulaTerca;
	}

	public function getInFlagQuarta(): ?string
	{
		return $this->inFlagQuarta;
	}

	public function getInHoraIniAulaQuarta(): ?string
	{
		return $this->inHoraIniAulaQuarta;
	}

	public function getInHoraFimAulaQuarta(): ?string
	{
		return $this->inHoraFimAulaQuarta;
	}

	public function getInFlagQuinta(): ?string
	{
		return $this->inFlagQuinta;
	}

	public function getInHoraIniAulaQuinta(): ?string
	{
		return $this->inHoraIniAulaQuinta;
	}

	public function getInHoraFimAulaQuinta(): ?string
	{
		return $this->inHoraFimAulaQuinta;
	}

	public function getInFlagSexta(): ?string
	{
		return $this->inFlagSexta;
	}

	public function getInHoraIniAulaSexta(): ?string
	{
		return $this->inHoraIniAulaSexta;
	}

	public function getInHoraFimAulaSexta(): ?string
	{
		return $this->inHoraFimAulaSexta;
	}

	public function getInFlagSabado(): ?string
	{
		return $this->inFlagSabado;
	}

	public function getInHoraIniAulaSabado(): ?string
	{
		return $this->inHoraIniAulaSabado;
	}

	public function getInHoraFimAulaSabado(): ?string
	{
		return $this->inHoraFimAulaSabado;
	}

	public function setInFlagSegunda(?string $inFlagSegunda): self
	{
		$this->inFlagSegunda = $inFlagSegunda;
		return $this;
	}

	public function setInHoraIniAulaSegunda(?string $inHoraIniAulaSegunda): self
	{
		$this->inHoraIniAulaSegunda = $inHoraIniAulaSegunda;
		return $this;
	}

	public function setInHoraFimAulaSegunda(?string $inHoraFimAulaSegunda): self
	{
		$this->inHoraFimAulaSegunda = $inHoraFimAulaSegunda;
		return $this;
	}

	public function setInFlagTerca(?string $inFlagTerca): self
	{
		$this->inFlagTerca = $inFlagTerca;
		return $this;
	}

	public function setInHoraIniAulaTerca(?string $inHoraIniAulaTerca): self
	{
		$this->inHoraIniAulaTerca = $inHoraIniAulaTerca;
		return $this;
	}

	public function setInHoraFimAulaTerca(?string $inHoraFimAulaTerca): self
	{
		$this->inHoraFimAulaTerca = $inHoraFimAulaTerca;
		return $this;
	}

	public function setInFlagQuarta(?string $inFlagQuarta): self
	{
		$this->inFlagQuarta = $inFlagQuarta;
		return $this;
	}

	public function setInHoraIniAulaQuarta(?string $inHoraIniAulaQuarta): self
	{
		$this->inHoraIniAulaQuarta = $inHoraIniAulaQuarta;
		return $this;
	}

	public function setInHoraFimAulaQuarta(?string $inHoraFimAulaQuarta): self
	{
		$this->inHoraFimAulaQuarta = $inHoraFimAulaQuarta;
		return $this;
	}

	public function setInFlagQuinta(?string $inFlagQuinta): self
	{
		$this->inFlagQuinta = $inFlagQuinta;
		return $this;
	}

	public function setInHoraIniAulaQuinta(?string $inHoraIniAulaQuinta): self
	{
		$this->inHoraIniAulaQuinta = $inHoraIniAulaQuinta;
		return $this;
	}

	public function setInHoraFimAulaQuinta(?string $inHoraFimAulaQuinta): self
	{
		$this->inHoraFimAulaQuinta = $inHoraFimAulaQuinta;
		return $this;
	}

	public function setInFlagSexta(?string $inFlagSexta): self
	{
		$this->inFlagSexta = $inFlagSexta;
		return $this;
	}

	public function setInHoraIniAulaSexta(?string $inHoraIniAulaSexta): self
	{
		$this->inHoraIniAulaSexta = $inHoraIniAulaSexta;
		return $this;
	}

	public function setInHoraFimAulaSexta(?string $inHoraFimAulaSexta): self
	{
		$this->inHoraFimAulaSexta = $inHoraFimAulaSexta;
		return $this;
	}

	public function setInFlagSabado(?string $inFlagSabado): self
	{
		$this->inFlagSabado = $inFlagSabado;
		return $this;
	}

	public function setInHoraIniAulaSabado(?string $inHoraIniAulaSabado): self
	{
		$this->inHoraIniAulaSabado = $inHoraIniAulaSabado;
		return $this;
	}

	public function setInHoraFimAulaSabado(?string $inHoraFimAulaSabado): self
	{
		$this->inHoraFimAulaSabado = $inHoraFimAulaSabado;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inFlagSegunda'] ?? null,
			$data['inHoraIniAulaSegunda'] ?? null,
			$data['inHoraFimAulaSegunda'] ?? null,
			$data['inFlagTerca'] ?? null,
			$data['inHoraIniAulaTerca'] ?? null,
			$data['inHoraFimAulaTerca'] ?? null,
			$data['inFlagQuarta'] ?? null,
			$data['inHoraIniAulaQuarta'] ?? null,
			$data['inHoraFimAulaQuarta'] ?? null,
			$data['inFlagQuinta'] ?? null,
			$data['inHoraIniAulaQuinta'] ?? null,
			$data['inHoraFimAulaQuinta'] ?? null,
			$data['inFlagSexta'] ?? null,
			$data['inHoraIniAulaSexta'] ?? null,
			$data['inHoraFimAulaSexta'] ?? null,
			$data['inFlagSabado'] ?? null,
			$data['inHoraIniAulaSabado'] ?? null,
			$data['inHoraFimAulaSabado'] ?? null
		);
	}

	function jsonSerialize() {
        return get_object_vars($this);
    }
}