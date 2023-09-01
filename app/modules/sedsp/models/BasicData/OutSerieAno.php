<?php

class OutSerieAno
{
	public $outCodSerieAno;

	public function __construct(?string $outCodSerieAno)
	{
		$this->outCodSerieAno = $outCodSerieAno;
	}

	public function getOutCodSerieAno(): ?string
	{
		return $this->outCodSerieAno;
	}

	public function setOutCodSerieAno(?string $outCodSerieAno): self
	{
		$this->outCodSerieAno = $outCodSerieAno;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outCodSerieAno'] ?? null
		);
	}
}