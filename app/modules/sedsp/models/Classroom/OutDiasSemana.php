<?php

class OutDiasSemana
{
	public $outFlagSegunda;
	public $outHoraIniAulaSegunda;
	public $outHoraFimAulaSegunda;
	public $outFlagTerca;
	public $outHoraIniAulaTerca;
	public $outHoraFimAulaTerca;
	public $outFlagQuarta;
	public $outHoraIniAulaQuarta;
	public $outHoraFimAulaQuarta;
	public $outFlagQuinta;
	public $outHoraIniAulaQuinta;
	public $outHoraFimAulaQuinta;
	public $outFlagSexta;
	public $outHoraIniAulaSexta;
	public $outHoraFimAulaSexta;
	public $outFlagSabado;

	public function __construct(
		?bool $outFlagSegunda,
		?string $outHoraIniAulaSegunda,
		?string $outHoraFimAulaSegunda,
		?bool $outFlagTerca,
		?string $outHoraIniAulaTerca,
		?string $outHoraFimAulaTerca,
		?bool $outFlagQuarta,
		?string $outHoraIniAulaQuarta,
		?string $outHoraFimAulaQuarta,
		?bool $outFlagQuinta,
		?string $outHoraIniAulaQuinta,
		?string $outHoraFimAulaQuinta,
		?bool $outFlagSexta,
		?string $outHoraIniAulaSexta,
		?string $outHoraFimAulaSexta,
		?bool $outFlagSabado
	) {
		$this->outFlagSegunda = $outFlagSegunda;
		$this->outHoraIniAulaSegunda = $outHoraIniAulaSegunda;
		$this->outHoraFimAulaSegunda = $outHoraFimAulaSegunda;
		$this->outFlagTerca = $outFlagTerca;
		$this->outHoraIniAulaTerca = $outHoraIniAulaTerca;
		$this->outHoraFimAulaTerca = $outHoraFimAulaTerca;
		$this->outFlagQuarta = $outFlagQuarta;
		$this->outHoraIniAulaQuarta = $outHoraIniAulaQuarta;
		$this->outHoraFimAulaQuarta = $outHoraFimAulaQuarta;
		$this->outFlagQuinta = $outFlagQuinta;
		$this->outHoraIniAulaQuinta = $outHoraIniAulaQuinta;
		$this->outHoraFimAulaQuinta = $outHoraFimAulaQuinta;
		$this->outFlagSexta = $outFlagSexta;
		$this->outHoraIniAulaSexta = $outHoraIniAulaSexta;
		$this->outHoraFimAulaSexta = $outHoraFimAulaSexta;
		$this->outFlagSabado = $outFlagSabado;
	}

	public function getOutFlagSegunda(): ?bool
	{
		return $this->outFlagSegunda;
	}

	public function getOutHoraIniAulaSegunda(): ?string
	{
		return $this->outHoraIniAulaSegunda;
	}

	public function getOutHoraFimAulaSegunda(): ?string
	{
		return $this->outHoraFimAulaSegunda;
	}

	public function getOutFlagTerca(): ?bool
	{
		return $this->outFlagTerca;
	}

	public function getOutHoraIniAulaTerca(): ?string
	{
		return $this->outHoraIniAulaTerca;
	}

	public function getOutHoraFimAulaTerca(): ?string
	{
		return $this->outHoraFimAulaTerca;
	}

	public function getOutFlagQuarta(): ?bool
	{
		return $this->outFlagQuarta;
	}

	public function getOutHoraIniAulaQuarta(): ?string
	{
		return $this->outHoraIniAulaQuarta;
	}

	public function getOutHoraFimAulaQuarta(): ?string
	{
		return $this->outHoraFimAulaQuarta;
	}

	public function getOutFlagQuinta(): ?bool
	{
		return $this->outFlagQuinta;
	}

	public function getOutHoraIniAulaQuinta(): ?string
	{
		return $this->outHoraIniAulaQuinta;
	}

	public function getOutHoraFimAulaQuinta(): ?string
	{
		return $this->outHoraFimAulaQuinta;
	}

	public function getOutFlagSexta(): ?bool
	{
		return $this->outFlagSexta;
	}

	public function getOutHoraIniAulaSexta(): ?string
	{
		return $this->outHoraIniAulaSexta;
	}

	public function getOutHoraFimAulaSexta(): ?string
	{
		return $this->outHoraFimAulaSexta;
	}

	public function getOutFlagSabado(): ?bool
	{
		return $this->outFlagSabado;
	}

	public function setOutFlagSegunda(?bool $outFlagSegunda): self
	{
		$this->outFlagSegunda = $outFlagSegunda;
		return $this;
	}

	public function setOutHoraIniAulaSegunda(?string $outHoraIniAulaSegunda): self
	{
		$this->outHoraIniAulaSegunda = $outHoraIniAulaSegunda;
		return $this;
	}

	public function setOutHoraFimAulaSegunda(?string $outHoraFimAulaSegunda): self
	{
		$this->outHoraFimAulaSegunda = $outHoraFimAulaSegunda;
		return $this;
	}

	public function setOutFlagTerca(?bool $outFlagTerca): self
	{
		$this->outFlagTerca = $outFlagTerca;
		return $this;
	}

	public function setOutHoraIniAulaTerca(?string $outHoraIniAulaTerca): self
	{
		$this->outHoraIniAulaTerca = $outHoraIniAulaTerca;
		return $this;
	}

	public function setOutHoraFimAulaTerca(?string $outHoraFimAulaTerca): self
	{
		$this->outHoraFimAulaTerca = $outHoraFimAulaTerca;
		return $this;
	}

	public function setOutFlagQuarta(?bool $outFlagQuarta): self
	{
		$this->outFlagQuarta = $outFlagQuarta;
		return $this;
	}

	public function setOutHoraIniAulaQuarta(?string $outHoraIniAulaQuarta): self
	{
		$this->outHoraIniAulaQuarta = $outHoraIniAulaQuarta;
		return $this;
	}

	public function setOutHoraFimAulaQuarta(?string $outHoraFimAulaQuarta): self
	{
		$this->outHoraFimAulaQuarta = $outHoraFimAulaQuarta;
		return $this;
	}

	public function setOutFlagQuinta(?bool $outFlagQuinta): self
	{
		$this->outFlagQuinta = $outFlagQuinta;
		return $this;
	}

	public function setOutHoraIniAulaQuinta(?string $outHoraIniAulaQuinta): self
	{
		$this->outHoraIniAulaQuinta = $outHoraIniAulaQuinta;
		return $this;
	}

	public function setOutHoraFimAulaQuinta(?string $outHoraFimAulaQuinta): self
	{
		$this->outHoraFimAulaQuinta = $outHoraFimAulaQuinta;
		return $this;
	}

	public function setOutFlagSexta(?bool $outFlagSexta): self
	{
		$this->outFlagSexta = $outFlagSexta;
		return $this;
	}

	public function setOutHoraIniAulaSexta(?string $outHoraIniAulaSexta): self
	{
		$this->outHoraIniAulaSexta = $outHoraIniAulaSexta;
		return $this;
	}

	public function setOutHoraFimAulaSexta(?string $outHoraFimAulaSexta): self
	{
		$this->outHoraFimAulaSexta = $outHoraFimAulaSexta;
		return $this;
	}

	public function setOutFlagSabado(?bool $outFlagSabado): self
	{
		$this->outFlagSabado = $outFlagSabado;
		return $this;
	}

    public static function fromJson(array $data): self
    {
        return new self(
            $data['outFlagSegunda'] ?? null,
            $data['outHoraIniAulaSegunda'] ?? null,
            $data['outHoraFimAulaSegunda'] ?? null,
            $data['outFlagTerca'] ?? null,
            $data['outHoraIniAulaTerca'] ?? null,
            $data['outHoraFimAulaTerca'] ?? null,
            $data['outFlagQuarta'] ?? null,
            $data['outHoraIniAulaQuarta'] ?? null,
            $data['outHoraFimAulaQuarta'] ?? null,
            $data['outFlagQuinta'] ?? null,
            $data['outHoraIniAulaQuinta'] ?? null,
            $data['outHoraFimAulaQuinta'] ?? null,
            $data['outFlagSexta'] ?? null,
            $data['outHoraIniAulaSexta'] ?? null,
            $data['outHoraFimAulaSexta'] ?? null,
            $data['outFlagSabado'] ?? null
        );
    }
}