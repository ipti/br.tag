<?php

class OutEscolas
{
	public $outCodEscola;
	public $outDescNomeEscola;
	public $outCodDiretoria;
	public $outDescNomeDiretoria;
	public $outTipoLogradouro;
	public $outDescEndereco;
	public $outNumero;
	public $outDescBairro;
	public $outDescComplemento;
	public $outCodMunicipio;
	public $outDescMunicipio;
	public $outLatitude;
	public $outLongitude;
	public $outCodRedeEnsino;
	public $outNomeRedeEnsino;
	public $outCodDistrito;
	public $outCEP;
	public $outNomeDistrito;
	/** @var OutUnidades[]|null */
	public $outUnidades;

	/**
	 * @param OutUnidades[]|null $outUnidades
	 */
	public function __construct(
		?string $outCodEscola,
		?string $outDescNomeEscola,
		?string $outCodDiretoria,
		?string $outDescNomeDiretoria,
		?string $outTipoLogradouro,
		?string $outDescEndereco,
		?string $outNumero,
		?string $outDescBairro,
		?string $outDescComplemento,
		?string $outCodMunicipio,
		?string $outDescMunicipio,
		?string $outLatitude,
		?string $outLongitude,
		?string $outCodRedeEnsino,
		?string $outNomeRedeEnsino,
		?string $outCodDistrito,
		?string $outCEP,
		?string $outNomeDistrito,
		?array $outUnidades
	) {
		$this->outCodEscola = $outCodEscola;
		$this->outDescNomeEscola = $outDescNomeEscola;
		$this->outCodDiretoria = $outCodDiretoria;
		$this->outDescNomeDiretoria = $outDescNomeDiretoria;
		$this->outTipoLogradouro = $outTipoLogradouro;
		$this->outDescEndereco = $outDescEndereco;
		$this->outNumero = $outNumero;
		$this->outDescBairro = $outDescBairro;
		$this->outDescComplemento = $outDescComplemento;
		$this->outCodMunicipio = $outCodMunicipio;
		$this->outDescMunicipio = $outDescMunicipio;
		$this->outLatitude = $outLatitude;
		$this->outLongitude = $outLongitude;
		$this->outCodRedeEnsino = $outCodRedeEnsino;
		$this->outNomeRedeEnsino = $outNomeRedeEnsino;
		$this->outCodDistrito = $outCodDistrito;
		$this->outCEP = $outCEP;
		$this->outNomeDistrito = $outNomeDistrito;
		$this->outUnidades = $outUnidades;
	}

	public function getOutCodEscola(): ?string
	{
		return $this->outCodEscola;
	}

	public function getOutDescNomeEscola(): ?string
	{
		return $this->outDescNomeEscola;
	}

	public function getOutCodDiretoria(): ?string
	{
		return $this->outCodDiretoria;
	}

	public function getOutDescNomeDiretoria(): ?string
	{
		return $this->outDescNomeDiretoria;
	}

	public function getOutTipoLogradouro(): ?string
	{
		return $this->outTipoLogradouro;
	}

	public function getOutDescEndereco(): ?string
	{
		return $this->outDescEndereco;
	}

	public function getOutNumero(): ?string
	{
		return $this->outNumero;
	}

	public function getOutDescBairro(): ?string
	{
		return $this->outDescBairro;
	}

	public function getOutDescComplemento(): ?string
	{
		return $this->outDescComplemento;
	}

	public function getOutCodMunicipio(): ?string
	{
		return $this->outCodMunicipio;
	}

	public function getOutDescMunicipio(): ?string
	{
		return $this->outDescMunicipio;
	}

	public function getOutLatitude(): ?string
	{
		return $this->outLatitude;
	}

	public function getOutLongitude(): ?string
	{
		return $this->outLongitude;
	}

	public function getOutCodRedeEnsino(): ?string
	{
		return $this->outCodRedeEnsino;
	}

	public function getOutNomeRedeEnsino(): ?string
	{
		return $this->outNomeRedeEnsino;
	}

	public function getOutCodDistrito(): ?string
	{
		return $this->outCodDistrito;
	}

	public function getOutCep(): ?string
	{
		return $this->outCEP;
	}

	public function getOutNomeDistrito(): ?string
	{
		return $this->outNomeDistrito;
	}

	/**
	 * @return OutUnidades[]|null
	 */
	public function getOutUnidades(): ?array
	{
		return $this->outUnidades;
	}

	public function setOutCodEscola(?string $outCodEscola): self
	{
		$this->outCodEscola = $outCodEscola;
		return $this;
	}

	public function setOutDescNomeEscola(?string $outDescNomeEscola): self
	{
		$this->outDescNomeEscola = $outDescNomeEscola;
		return $this;
	}

	public function setOutCodDiretoria(?string $outCodDiretoria): self
	{
		$this->outCodDiretoria = $outCodDiretoria;
		return $this;
	}

	public function setOutDescNomeDiretoria(?string $outDescNomeDiretoria): self
	{
		$this->outDescNomeDiretoria = $outDescNomeDiretoria;
		return $this;
	}

	public function setOutTipoLogradouro(?string $outTipoLogradouro): self
	{
		$this->outTipoLogradouro = $outTipoLogradouro;
		return $this;
	}

	public function setOutDescEndereco(?string $outDescEndereco): self
	{
		$this->outDescEndereco = $outDescEndereco;
		return $this;
	}

	public function setOutNumero(?string $outNumero): self
	{
		$this->outNumero = $outNumero;
		return $this;
	}

	public function setOutDescBairro(?string $outDescBairro): self
	{
		$this->outDescBairro = $outDescBairro;
		return $this;
	}

	public function setOutDescComplemento(?string $outDescComplemento): self
	{
		$this->outDescComplemento = $outDescComplemento;
		return $this;
	}

	public function setOutCodMunicipio(?string $outCodMunicipio): self
	{
		$this->outCodMunicipio = $outCodMunicipio;
		return $this;
	}

	public function setOutDescMunicipio(?string $outDescMunicipio): self
	{
		$this->outDescMunicipio = $outDescMunicipio;
		return $this;
	}

	public function setOutLatitude(?string $outLatitude): self
	{
		$this->outLatitude = $outLatitude;
		return $this;
	}

	public function setOutLongitude(?string $outLongitude): self
	{
		$this->outLongitude = $outLongitude;
		return $this;
	}

	public function setOutCodRedeEnsino(?string $outCodRedeEnsino): self
	{
		$this->outCodRedeEnsino = $outCodRedeEnsino;
		return $this;
	}

	public function setOutNomeRedeEnsino(?string $outNomeRedeEnsino): self
	{
		$this->outNomeRedeEnsino = $outNomeRedeEnsino;
		return $this;
	}

	public function setOutCodDistrito(?string $outCodDistrito): self
	{
		$this->outCodDistrito = $outCodDistrito;
		return $this;
	}

	public function setOutCep(?string $outCEP): self
	{
		$this->outCEP = $outCEP;
		return $this;
	}

	public function setOutNomeDistrito(?string $outNomeDistrito): self
	{
		$this->outNomeDistrito = $outNomeDistrito;
		return $this;
	}

	/**
	 * @param OutUnidades[]|null $outUnidades
	 */
	public function setOutUnidades(?array $outUnidades): self
	{
		$this->outUnidades = $outUnidades;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outCodEscola'] ?? null,
			$data['outDescNomeEscola'] ?? null,
			$data['outCodDiretoria'] ?? null,
			$data['outDescNomeDiretoria'] ?? null,
			$data['outTipoLogradouro'] ?? null,
			$data['outDescEndereco'] ?? null,
			$data['outNumero'] ?? null,
			$data['outDescBairro'] ?? null,
			$data['outDescComplemento'] ?? null,
			$data['outCodMunicipio'] ?? null,
			$data['outDescMunicipio'] ?? null,
			$data['outLatitude'] ?? null,
			$data['outLongitude'] ?? null,
			$data['outCodRedeEnsino'] ?? null,
			$data['outNomeRedeEnsino'] ?? null,
			$data['outCodDistrito'] ?? null,
			$data['outCEP'] ?? null,
			$data['outNomeDistrito'] ?? null,
			($data['outUnidades'] ?? null) !== null ? array_map(static function($data) {
				return OutUnidades::fromJson($data);
			}, $data['outUnidades']) : null
		);
	}
}
