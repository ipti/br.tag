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
		string $inNomeAluno,
		string $inNomeMae,
		string $inNomePai,
		string $inNomeSocial,
		string $inNomeAfetivo,
		string $inDataNascimento,
		string $inCorRaca,
		string $inSexo,
		string $inBolsaFamilia,
		string $inQuilombola,
		string $inPossuiInternet,
		string $inPossuiNotebookSmartphoneTablet,
		string $inTipoSanguineo,
		string $inDoadorOrgaos,
		string $inNumeroCNS,
		string $inEmail,
		string $inNacionalidade,
		string $inNomeMunNascto,
		string $inUFMunNascto,
		string $inCodMunNasctoDNE,
		string $inDataEntradaPais,
		string $inCodPaisOrigem,
		string $inPaisOrigem
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
    
    public function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }

    /**
     * Get the value of inNomeAluno
     */
    public function getInNomeAluno()
    {
        return $this->inNomeAluno;
    }

    /**
     * Set the value of inNomeAluno
     */
    public function setInNomeAluno($inNomeAluno): self
    {
        $this->inNomeAluno = $inNomeAluno;

        return $this;
    }

	/**
	 * Get the value of inNomeMae
	 */
	public function getInNomeMae()
	{
		return $this->inNomeMae;
	}

	/**
	 * Set the value of inNomeMae
	 */
	public function setInNomeMae($inNomeMae): self
	{
		$this->inNomeMae = $inNomeMae;

		return $this;
	}

	/**
	 * Get the value of inNomePai
	 */
	public function getInNomePai()
	{
		return $this->inNomePai;
	}

	/**
	 * Set the value of inNomePai
	 */
	public function setInNomePai($inNomePai): self
	{
		$this->inNomePai = $inNomePai;

		return $this;
	}

	/**
	 * Get the value of inNomeSocial
	 */
	public function getInNomeSocial()
	{
		return $this->inNomeSocial;
	}

	/**
	 * Set the value of inNomeSocial
	 */
	public function setInNomeSocial($inNomeSocial): self
	{
		$this->inNomeSocial = $inNomeSocial;

		return $this;
	}

	/**
	 * Get the value of inNomeAfetivo
	 */
	public function getInNomeAfetivo()
	{
		return $this->inNomeAfetivo;
	}

	/**
	 * Set the value of inNomeAfetivo
	 */
	public function setInNomeAfetivo($inNomeAfetivo): self
	{
		$this->inNomeAfetivo = $inNomeAfetivo;

		return $this;
	}

	/**
	 * Get the value of inDataNascimento
	 */
	public function getInDataNascimento()
	{
		return $this->inDataNascimento;
	}

	/**
	 * Set the value of inDataNascimento
	 */
	public function setInDataNascimento($inDataNascimento): self
	{
		$this->inDataNascimento = $inDataNascimento;

		return $this;
	}

	/**
	 * Get the value of inCorRaca
	 */
	public function getInCorRaca()
	{
		return $this->inCorRaca;
	}

	/**
	 * Set the value of inCorRaca
	 */
	public function setInCorRaca($inCorRaca): self
	{
		$this->inCorRaca = $inCorRaca;

		return $this;
	}

	/**
	 * Get the value of inSexo
	 */
	public function getInSexo()
	{
		return $this->inSexo;
	}

	/**
	 * Set the value of inSexo
	 */
	public function setInSexo($inSexo): self
	{
		$this->inSexo = $inSexo;

		return $this;
	}

	/**
	 * Get the value of inBolsaFamilia
	 */
	public function getInBolsaFamilia()
	{
		return $this->inBolsaFamilia;
	}

	/**
	 * Set the value of inBolsaFamilia
	 */
	public function setInBolsaFamilia($inBolsaFamilia): self
	{
		$this->inBolsaFamilia = $inBolsaFamilia;

		return $this;
	}

	/**
	 * Get the value of inQuilombola
	 */
	public function getInQuilombola()
	{
		return $this->inQuilombola;
	}

	/**
	 * Set the value of inQuilombola
	 */
	public function setInQuilombola($inQuilombola): self
	{
		$this->inQuilombola = $inQuilombola;

		return $this;
	}

	/**
	 * Get the value of inPossuiInternet
	 */
	public function getInPossuiInternet()
	{
		return $this->inPossuiInternet;
	}

	/**
	 * Set the value of inPossuiInternet
	 */
	public function setInPossuiInternet($inPossuiInternet): self
	{
		$this->inPossuiInternet = $inPossuiInternet;

		return $this;
	}

	/**
	 * Get the value of inPossuiNotebookSmartphoneTablet
	 */
	public function getInPossuiNotebookSmartphoneTablet()
	{
		return $this->inPossuiNotebookSmartphoneTablet;
	}

	/**
	 * Set the value of inPossuiNotebookSmartphoneTablet
	 */
	public function setInPossuiNotebookSmartphoneTablet($inPossuiNotebookSmartphoneTablet): self
	{
		$this->inPossuiNotebookSmartphoneTablet = $inPossuiNotebookSmartphoneTablet;

		return $this;
	}

	/**
	 * Get the value of inTipoSanguineo
	 */
	public function getInTipoSanguineo()
	{
		return $this->inTipoSanguineo;
	}

	/**
	 * Set the value of inTipoSanguineo
	 */
	public function setInTipoSanguineo($inTipoSanguineo): self
	{
		$this->inTipoSanguineo = $inTipoSanguineo;

		return $this;
	}

	/**
	 * Get the value of inDoadorOrgaos
	 */
	public function getInDoadorOrgaos()
	{
		return $this->inDoadorOrgaos;
	}

	/**
	 * Set the value of inDoadorOrgaos
	 */
	public function setInDoadorOrgaos($inDoadorOrgaos): self
	{
		$this->inDoadorOrgaos = $inDoadorOrgaos;

		return $this;
	}

	/**
	 * Get the value of inNumeroCNS
	 */
	public function getInNumeroCNS()
	{
		return $this->inNumeroCNS;
	}

	/**
	 * Set the value of inNumeroCNS
	 */
	public function setInNumeroCNS($inNumeroCNS): self
	{
		$this->inNumeroCNS = $inNumeroCNS;

		return $this;
	}

	/**
	 * Get the value of inEmail
	 */
	public function getInEmail()
	{
		return $this->inEmail;
	}

	/**
	 * Set the value of inEmail
	 */
	public function setInEmail($inEmail): self
	{
		$this->inEmail = $inEmail;

		return $this;
	}

	/**
	 * Get the value of inNacionalidade
	 */
	public function getInNacionalidade()
	{
		return $this->inNacionalidade;
	}

	/**
	 * Set the value of inNacionalidade
	 */
	public function setInNacionalidade($inNacionalidade): self
	{
		$this->inNacionalidade = $inNacionalidade;

		return $this;
	}

	/**
	 * Get the value of inNomeMunNascto
	 */
	public function getInNomeMunNascto()
	{
		return $this->inNomeMunNascto;
	}

	/**
	 * Set the value of inNomeMunNascto
	 */
	public function setInNomeMunNascto($inNomeMunNascto): self
	{
		$this->inNomeMunNascto = $inNomeMunNascto;

		return $this;
	}

	/**
	 * Get the value of inUFMunNascto
	 */
	public function getInUFMunNascto()
	{
		return $this->inUFMunNascto;
	}

	/**
	 * Set the value of inUFMunNascto
	 */
	public function setInUFMunNascto($inUFMunNascto): self
	{
		$this->inUFMunNascto = $inUFMunNascto;

		return $this;
	}

	/**
	 * Get the value of inCodMunNasctoDNE
	 */
	public function getInCodMunNasctoDNE()
	{
		return $this->inCodMunNasctoDNE;
	}

	/**
	 * Set the value of inCodMunNasctoDNE
	 */
	public function setInCodMunNasctoDNE($inCodMunNasctoDNE): self
	{
		$this->inCodMunNasctoDNE = $inCodMunNasctoDNE;

		return $this;
	}

	/**
	 * Get the value of inDataEntradaPais
	 */
	public function getInDataEntradaPais()
	{
		return $this->inDataEntradaPais;
	}

	/**
	 * Set the value of inDataEntradaPais
	 */
	public function setInDataEntradaPais($inDataEntradaPais): self
	{
		$this->inDataEntradaPais = $inDataEntradaPais;

		return $this;
	}

	/**
	 * Get the value of inCodPaisOrigem
	 */
	public function getInCodPaisOrigem()
	{
		return $this->inCodPaisOrigem;
	}

	/**
	 * Set the value of inCodPaisOrigem
	 */
	public function setInCodPaisOrigem($inCodPaisOrigem): self
	{
		$this->inCodPaisOrigem = $inCodPaisOrigem;

		return $this;
	}

	/**
	 * Get the value of inPaisOrigem
	 */
	public function getInPaisOrigem()
	{
		return $this->inPaisOrigem;
	}

	/**
	 * Set the value of inPaisOrigem
	 */
	public function setInPaisOrigem($inPaisOrigem): self
	{
		$this->inPaisOrigem = $inPaisOrigem;

		return $this;
	}
}