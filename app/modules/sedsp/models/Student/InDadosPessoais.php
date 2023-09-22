<?php


class InDadosPessoais implements JsonSerializable
{
	public $inNomeAluno;
	public $inNomeMae;
	public $inNomePai;
	public $inNomeSocial;
	public $inNomeAfetivo;
	public $inDataNascimento;
	public $inCorRaca;
	public $inSexo;
	public $inBolsaFamilia;
	public $inQuilombola;
	public $inPossuiInternet;
	public $inPossuiNotebookSmartphoneTablet;
	public $inTipoSanguineo;
	public $inDoadorOrgaos;
	public $inNumeroCNS;
	public $inEmail;
	public $inNacionalidade;
	public $inNomeMunNascto;
	public $inUFMunNascto;
	public $inCodMunNasctoDNE;
	public $inDataEntradaPais;
	public $inCodPaisOrigem;
	public $inPaisOrigem;

	public function __construct(
		?string $inNomeAluno = null,
		?string $inNomeMae = null,
		?string $inNomePai = null,
		?string $inNomeSocial = null,
		?string $inNomeAfetivo = null,
		?string $inDataNascimento = null,
		?string $inCorRaca = null,
		?string $inSexo = null,
		?string $inBolsaFamilia = null,
		?string $inQuilombola = null,
		?string $inPossuiInternet = null,
		?string $inPossuiNotebookSmartphoneTablet = null,
		?string $inTipoSanguineo = null,
		?string $inDoadorOrgaos = null,
		?string $inNumeroCNS = null,
		?string $inEmail = null,
		?string $inNacionalidade = null,
		?string $inNomeMunNascto = null,
		?string $inUFMunNascto = null,
		?string $inCodMunNasctoDNE = null,
		?string $inDataEntradaPais = null,
		?string $inCodPaisOrigem = null,
		?string $inPaisOrigem = null
	) {
		$this->inNomeAluno = $inNomeAluno;
		$this->inNomeMae = $inNomeMae;
		$this->inNomePai = $inNomePai;
		$this->inNomeSocial = $inNomeSocial;
		$this->inNomeAfetivo = $inNomeAfetivo;
		$this->inDataNascimento = $inDataNascimento;
		$this->inCorRaca = $inCorRaca;
		$this->inSexo = $inSexo;
		$this->inBolsaFamilia = $inBolsaFamilia;
		$this->inQuilombola = $inQuilombola;
		$this->inPossuiInternet = $inPossuiInternet;
		$this->inPossuiNotebookSmartphoneTablet = $inPossuiNotebookSmartphoneTablet;
		$this->inTipoSanguineo = $inTipoSanguineo;
		$this->inDoadorOrgaos = $inDoadorOrgaos;
		$this->inNumeroCNS = $inNumeroCNS;
		$this->inEmail = $inEmail;
		$this->inNacionalidade = $inNacionalidade;
		$this->inNomeMunNascto = $inNomeMunNascto;
		$this->inUFMunNascto = $inUFMunNascto;
		$this->inCodMunNasctoDNE = $inCodMunNasctoDNE;
		$this->inDataEntradaPais = $inDataEntradaPais;
		$this->inCodPaisOrigem = $inCodPaisOrigem;
		$this->inPaisOrigem = $inPaisOrigem;
	}

	public function getInNomeAluno(): ?string
	{
		return $this->inNomeAluno;
	}

	public function getInNomeMae(): ?string
	{
		return $this->inNomeMae;
	}

	public function getInNomePai(): ?string
	{
		return $this->inNomePai;
	}

	public function getInNomeSocial(): ?string
	{
		return $this->inNomeSocial;
	}

	public function getInNomeAfetivo(): ?string
	{
		return $this->inNomeAfetivo;
	}

	public function getInDataNascimento(): ?string
	{
		return $this->inDataNascimento;
	}

	public function getInCorRaca(): ?string
	{
		return $this->inCorRaca;
	}

	public function getInSexo(): ?string
	{
		return $this->inSexo;
	}

	public function getInBolsaFamilia(): ?string
	{
		return $this->inBolsaFamilia;
	}

	public function getInQuilombola(): ?string
	{
		return $this->inQuilombola;
	}

	public function getInPossuiInternet(): ?string
	{
		return $this->inPossuiInternet;
	}

	public function getInPossuiNotebookSmartphoneTablet(): ?string
	{
		return $this->inPossuiNotebookSmartphoneTablet;
	}

	public function getInTipoSanguineo(): ?string
	{
		return $this->inTipoSanguineo;
	}

	public function getInDoadorOrgaos(): ?string
	{
		return $this->inDoadorOrgaos;
	}

	public function getInNumeroCns(): ?string
	{
		return $this->inNumeroCNS;
	}

	public function getInEmail(): ?string
	{
		return $this->inEmail;
	}

	public function getInNacionalidade(): ?string
	{
		return $this->inNacionalidade;
	}

	public function getInNomeMunNascto(): ?string
	{
		return $this->inNomeMunNascto;
	}

	public function getInUfMunNascto(): ?string
	{
		return $this->inUFMunNascto;
	}

	public function getInCodMunNasctoDne(): ?string
	{
		return $this->inCodMunNasctoDNE;
	}

	public function getInDataEntradaPais(): ?string
	{
		return $this->inDataEntradaPais;
	}

	public function getInCodPaisOrigem(): ?string
	{
		return $this->inCodPaisOrigem;
	}

	public function getInPaisOrigem(): ?string
	{
		return $this->inPaisOrigem;
	}

	public function setInNomeAluno(?string $inNomeAluno): self
	{
		$this->inNomeAluno = $inNomeAluno;
		return $this;
	}

	public function setInNomeMae(?string $inNomeMae): self
	{
		$this->inNomeMae = $inNomeMae;
		return $this;
	}

	public function setInNomePai(?string $inNomePai): self
	{
		$this->inNomePai = $inNomePai;
		return $this;
	}

	public function setInNomeSocial(?string $inNomeSocial): self
	{
		$this->inNomeSocial = $inNomeSocial;
		return $this;
	}

	public function setInNomeAfetivo(?string $inNomeAfetivo): self
	{
		$this->inNomeAfetivo = $inNomeAfetivo;
		return $this;
	}

	public function setInDataNascimento(?string $inDataNascimento): self
	{
		$this->inDataNascimento = $inDataNascimento;
		return $this;
	}

	public function setInCorRaca(?string $inCorRaca): self
	{
		$this->inCorRaca = $inCorRaca;
		return $this;
	}

	public function setInSexo(?string $inSexo): self
	{
		$this->inSexo = $inSexo;
		return $this;
	}

	public function setInBolsaFamilia(?string $inBolsaFamilia): self
	{
		$this->inBolsaFamilia = $inBolsaFamilia;
		return $this;
	}

	public function setInQuilombola(?string $inQuilombola): self
	{
		$this->inQuilombola = $inQuilombola;
		return $this;
	}

	public function setInPossuiInternet(?string $inPossuiInternet): self
	{
		$this->inPossuiInternet = $inPossuiInternet;
		return $this;
	}

	public function setInPossuiNotebookSmartphoneTablet(?string $inPossuiNotebookSmartphoneTablet): self
	{
		$this->inPossuiNotebookSmartphoneTablet = $inPossuiNotebookSmartphoneTablet;
		return $this;
	}

	public function setInTipoSanguineo(?string $inTipoSanguineo): self
	{
		$this->inTipoSanguineo = $inTipoSanguineo;
		return $this;
	}

	public function setInDoadorOrgaos(?string $inDoadorOrgaos): self
	{
		$this->inDoadorOrgaos = $inDoadorOrgaos;
		return $this;
	}

	public function setInNumeroCns(?string $inNumeroCNS): self
	{
		$this->inNumeroCNS = $inNumeroCNS;
		return $this;
	}

	public function setInEmail(?string $inEmail): self
	{
		$this->inEmail = $inEmail;
		return $this;
	}

	public function setInNacionalidade(?string $inNacionalidade): self
	{
		$this->inNacionalidade = $inNacionalidade;
		return $this;
	}

	public function setInNomeMunNascto(?string $inNomeMunNascto): self
	{
		$this->inNomeMunNascto = $inNomeMunNascto;
		return $this;
	}

	public function setInUfMunNascto(?string $inUFMunNascto): self
	{
		$this->inUFMunNascto = $inUFMunNascto;
		return $this;
	}

	public function setInCodMunNasctoDne(?string $inCodMunNasctoDNE): self
	{
		$this->inCodMunNasctoDNE = $inCodMunNasctoDNE;
		return $this;
	}

	public function setInDataEntradaPais(?string $inDataEntradaPais): self
	{
		$this->inDataEntradaPais = $inDataEntradaPais;
		return $this;
	}

	public function setInCodPaisOrigem(?string $inCodPaisOrigem): self
	{
		$this->inCodPaisOrigem = $inCodPaisOrigem;
		return $this;
	}

	public function setInPaisOrigem(?string $inPaisOrigem): self
	{
		$this->inPaisOrigem = $inPaisOrigem;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inNomeAluno'] ?? null,
			$data['inNomeMae'] ?? null,
			$data['inNomePai'] ?? null,
			$data['inNomeSocial'] ?? null,
			$data['inNomeAfetivo'] ?? null,
			$data['inDataNascimento'] ?? null,
			$data['inCorRaca'] ?? null,
			$data['inSexo'] ?? null,
			$data['inBolsaFamilia'] ?? null,
			$data['inQuilombola'] ?? null,
			$data['inPossuiInternet'] ?? null,
			$data['inPossuiNotebookSmartphoneTablet'] ?? null,
			$data['inTipoSanguineo'] ?? null,
			$data['inDoadorOrgaos'] ?? null,
			$data['inNumeroCNS'] ?? null,
			$data['inEmail'] ?? null,
			$data['inNacionalidade'] ?? null,
			$data['inNomeMunNascto'] ?? null,
			$data['inUFMunNascto'] ?? null,
			$data['inCodMunNasctoDNE'] ?? null,
			$data['inDataEntradaPais'] ?? null,
			$data['inCodPaisOrigem'] ?? null,
			$data['inPaisOrigem'] ?? null
		);
	}

	function jsonSerialize()
	{
		return get_object_vars($this);
	}
}