<?php

class InNivelEnsino implements JsonSerializable
{
    public $inCodTipoEnsino;
	public $inCodSerieAno;

	public function __construct(?string $inCodTipoEnsino, ?string $inCodSerieAno)
	{
		$this->inCodTipoEnsino = $inCodTipoEnsino;
		$this->inCodSerieAno = $inCodSerieAno;
	}

	public function get_inCodTipoEnsino(): ?string
	{
		return $this->inCodTipoEnsino;
	}

	public function get_inCodSerieAno(): ?string
	{
		return $this->inCodSerieAno;
	}

	public function setInCodTipoEnsino(?string $inCodTipoEnsino): self
	{
		$this->inCodTipoEnsino = $inCodTipoEnsino;
		return $this;
	}

	public function setInCodSerieAno(?string $inCodSerieAno): self
	{
		$this->inCodSerieAno = $inCodSerieAno;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inCodTipoEnsino'] ?? null,
			$data['inCodSerieAno'] ?? null
		);
	}
    function jsonSerialize(){
        return get_object_vars($this);
    }
}
