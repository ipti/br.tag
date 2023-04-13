<?php

class InEnderecoResidencial implements JsonSerializable
{
	private $inLogradouro;

	private $inNumero;

	private $inBairro;

	private $inNomeCidade;

	private $inUfCidade;

	private $inComplemento;

	private $inCep;

	private $inAreaLogradouro;

	private $inCodLocalizacaoDiferenciada;

	private $inCodMunicipioDne;

	private $inLatitude;

	private $inLongitude;

	/**
	 * @param string|null $inLogradouro
	 * @param string|null $inNumero
	 * @param string|null $inBairro
	 * @param string|null $inNomeCidade
	 * @param string|null $inUfCidade
	 * @param string|null $inComplemento
	 * @param string|null $inCep
	 * @param string|null $inAreaLogradouro
	 * @param string|null $inCodLocalizacaoDiferenciada
	 * @param string|null $inCodMunicipioDne
	 * @param string|null $inLatitude
	 * @param string|null $inLongitude
	 */
	public function __construct(
		?string $inLogradouro = null,
		?string $inNumero = null,
		?string $inBairro = null,
		?string $inNomeCidade = null,
		?string $inUfCidade = null,
		?string $inComplemento = null,
		?string $inCep = null,
		?string $inAreaLogradouro = null,
		?string $inCodLocalizacaoDiferenciada = null,
		?string $inCodMunicipioDne = null,
		?string $inLatitude = null,
		?string $inLongitude = null
	) {
		$this->inLogradouro = $inLogradouro;
		$this->inNumero = $inNumero;
		$this->inBairro = $inBairro;
		$this->inNomeCidade = $inNomeCidade;
		$this->inUfCidade = $inUfCidade;
		$this->inComplemento = $inComplemento;
		$this->inCep = $inCep;
		$this->inAreaLogradouro = $inAreaLogradouro;
		$this->inCodLocalizacaoDiferenciada = $inCodLocalizacaoDiferenciada;
		$this->inCodMunicipioDne = $inCodMunicipioDne;
		$this->inLatitude = $inLatitude;
		$this->inLongitude = $inLongitude;
	}

	/**
	 * @param string|null $inLogradouro
	 * @return self
	 */
	public function setInLogradouro(?string $inLogradouro): self
	{
		$this->inLogradouro = $inLogradouro;
		return $this;
	}

	/**
	 * @param string|null $inNumero
	 * @return self
	 */
	public function setInNumero(?string $inNumero): self
	{
		$this->inNumero = $inNumero;
		return $this;
	}

	/**
	 * @param string|null $inBairro
	 * @return self
	 */
	public function setInBairro(?string $inBairro): self
	{
		$this->inBairro = $inBairro;
		return $this;
	}

	/**
	 * @param string|null $inNomeCidade
	 * @return self
	 */
	public function setInNomeCidade(?string $inNomeCidade): self
	{
		$this->inNomeCidade = $inNomeCidade;
		return $this;
	}

	/**
	 * @param string|null $inUfCidade
	 * @return self
	 */
	public function setInUfCidade(?string $inUfCidade): self
	{
		$this->inUfCidade = $inUfCidade;
		return $this;
	}

	/**
	 * @param string|null $inComplemento
	 * @return self
	 */
	public function setInComplemento(?string $inComplemento): self
	{
		$this->inComplemento = $inComplemento;
		return $this;
	}

	/**
	 * @param string|null $inCep
	 * @return self
	 */
	public function setInCep(?string $inCep): self
	{
		$this->inCep = $inCep;
		return $this;
	}

	/**
	 * @param string|null $inAreaLogradouro
	 * @return self
	 */
	public function setInAreaLogradouro(?string $inAreaLogradouro): self
	{
		$this->inAreaLogradouro = $inAreaLogradouro;
		return $this;
	}

	/**
	 * @param string|null $inCodLocalizacaoDiferenciada
	 * @return self
	 */
	public function setInCodLocalizacaoDiferenciada(?string $inCodLocalizacaoDiferenciada): self
	{
		$this->inCodLocalizacaoDiferenciada = $inCodLocalizacaoDiferenciada;
		return $this;
	}

	/**
	 * @param string|null $inCodMunicipioDne
	 * @return self
	 */
	public function setInCodMunicipioDne(?string $inCodMunicipioDne): self
	{
		$this->inCodMunicipioDne = $inCodMunicipioDne;
		return $this;
	}

	/**
	 * @param string|null $inLatitude
	 * @return self
	 */
	public function setInLatitude(?string $inLatitude): self
	{
		$this->inLatitude = $inLatitude;
		return $this;
	}

	/**
	 * @param string|null $inLongitude
	 * @return self
	 */
	public function setInLongitude(?string $inLongitude): self
	{
		$this->inLongitude = $inLongitude;
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
			$data['outBairro'] ?? null,
			$data['outNomeCidade'] ?? null,
			$data['outUFCidade'] ?? null,
			$data['outComplemento'] ?? null,
			$data['outCep'] ?? null,
			$data['outAreaLogradouro'] ?? null,
			$data['outCodLocalizacaoDiferenciada'] ?? null,
			$data['outCodMunicipioDNE'] ?? null,
			$data['outLatitude'] ?? null,
			$data['outLongitude'] ?? null
		);
	}
	public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}

?>