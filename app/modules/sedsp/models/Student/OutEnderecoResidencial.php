<?php

class OutEnderecoResidencial
{
	public $outLogradouro;
	public $outNumero;
	public $outCodArea;
	public $outAreaLogradouro;
	public $outComplemento;
	public $outBairro;
	public $outNomeCidade;
	public $outUFCidade;
	public $outCodMunicipioDNE;
	public $outLatitude;
	public $outLongitude;
	public $outCep;
	public $outCodLocalizacao;
	public $outLocalizacaoDiferenciada;

	public function __construct(
		?string $outLogradouro,
		?string $outNumero,
		?string $outCodArea,
		?string $outAreaLogradouro,
		?string $outComplemento,
		?string $outBairro,
		?string $outNomeCidade,
		?string $outUFCidade,
		?string $outCodMunicipioDNE,
		?string $outLatitude,
		?string $outLongitude,
		?string $outCep,
		?string $outCodLocalizacao,
		?string $outLocalizacaoDiferenciada
	) {
		$this->outLogradouro = $outLogradouro;
		$this->outNumero = $outNumero;
		$this->outCodArea = $outCodArea;
		$this->outAreaLogradouro = $outAreaLogradouro;
		$this->outComplemento = $outComplemento;
		$this->outBairro = $outBairro;
		$this->outNomeCidade = $outNomeCidade;
		$this->outUFCidade = $outUFCidade;
		$this->outCodMunicipioDNE = $outCodMunicipioDNE;
		$this->outLatitude = $outLatitude;
		$this->outLongitude = $outLongitude;
		$this->outCep = $outCep;
		$this->outCodLocalizacao = $outCodLocalizacao;
		$this->outLocalizacaoDiferenciada = $outLocalizacaoDiferenciada;
	}

	public function getOutLogradouro(): ?string
	{
		return $this->outLogradouro;
	}

	public function getOutNumero(): ?string
	{
		return $this->outNumero;
	}

	public function getOutCodArea(): ?string
	{
		return $this->outCodArea;
	}

	public function getOutAreaLogradouro(): ?string
	{
		return $this->outAreaLogradouro;
	}

	public function getOutComplemento(): ?string
	{
		return $this->outComplemento;
	}

	public function getOutBairro(): ?string
	{
		return $this->outBairro;
	}

	public function getOutNomeCidade(): ?string
	{
		return $this->outNomeCidade;
	}

	public function getOutUfCidade(): ?string
	{
		return $this->outUFCidade;
	}

	public function getOutCodMunicipioDne(): ?string
	{
		return $this->outCodMunicipioDNE;
	}

	public function getOutLatitude(): ?string
	{
		return $this->outLatitude;
	}

	public function getOutLongitude(): ?string
	{
		return $this->outLongitude;
	}

	public function getOutCep(): ?string
	{
		return $this->outCep;
	}

	public function getOutCodLocalizacao(): ?string
	{
		return $this->outCodLocalizacao;
	}

	public function getOutLocalizacaoDiferenciada(): ?string
	{
		return $this->outLocalizacaoDiferenciada;
	}

	public function setOutLogradouro(?string $outLogradouro): self
	{
		$this->outLogradouro = $outLogradouro;
		return $this;
	}

	public function setOutNumero(?string $outNumero): self
	{
		$this->outNumero = $outNumero;
		return $this;
	}

	public function setOutCodArea(?string $outCodArea): self
	{
		$this->outCodArea = $outCodArea;
		return $this;
	}

	public function setOutAreaLogradouro(?string $outAreaLogradouro): self
	{
		$this->outAreaLogradouro = $outAreaLogradouro;
		return $this;
	}

	public function setOutComplemento(?string $outComplemento): self
	{
		$this->outComplemento = $outComplemento;
		return $this;
	}

	public function setOutBairro(?string $outBairro): self
	{
		$this->outBairro = $outBairro;
		return $this;
	}

	public function setOutNomeCidade(?string $outNomeCidade): self
	{
		$this->outNomeCidade = $outNomeCidade;
		return $this;
	}

	public function setOutUfCidade(?string $outUFCidade): self
	{
		$this->outUFCidade = $outUFCidade;
		return $this;
	}

	public function setOutCodMunicipioDne(?string $outCodMunicipioDNE): self
	{
		$this->outCodMunicipioDNE = $outCodMunicipioDNE;
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

	public function setOutCep(?string $outCep): self
	{
		$this->outCep = $outCep;
		return $this;
	}

	public function setOutCodLocalizacao(?string $outCodLocalizacao): self
	{
		$this->outCodLocalizacao = $outCodLocalizacao;
		return $this;
	}

	public function setOutLocalizacaoDiferenciada(?string $outLocalizacaoDiferenciada): self
	{
		$this->outLocalizacaoDiferenciada = $outLocalizacaoDiferenciada;
		return $this;
	}

		/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
            $data['outLogradouro'] ?? null,
            $data['outNumero'] ?? null,
            $data['outCodArea'] ?? null,
            $data['outAreaLogradouro'] ?? null,
            $data['outComplemento'] ?? null,
            $data['outBairro'] ?? null,
            $data['outNomeCidade'] ?? null,
            $data['outUFCidade'] ?? null,
            $data['outCodMunicipioDNE'] ?? null,
            $data['outLatitude'] ?? null,
            $data['outLongitude'] ?? null,
            $data['outCep'] ?? null,
            $data['outCodLocalizacao'] ?? null,
            $data['outLocalizacaoDiferenciada'] ?? null
        );
	}
}