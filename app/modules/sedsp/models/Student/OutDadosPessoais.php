<?php

class OutDadosPessoais implements JsonSerializable
{
	public $outNumRA;
	public $outDigitoRA;
	public $outSiglaUFRA;
	public $outNomeAluno;
	public $outDataNascimento;
	public $outCorRaca;
	public $outDescCorRaca;
	public $outSexo;
	public $outNomeMae;
	public $outNomePai;
	public $outNomeSocial;
	public $outNomeAfetivo;
	public $outEmail;
	public $outNacionalidade;
	public $outDescNacionalidade;
	public $outDataEntradaPais;
	public $outCodPaisOrigem;
	public $outNomePaisOrigem;
	public $outCodBolsaFamilia;
	public $outPossuiInternet;
	public $outPossuiNotebookSmartphoneTablet ;
	public $outBolsaFamilia;
	public $outQuilombola;
	public $outTipoSanguineo;
	public $outDoadorOrgaos;
	public $outNumeroCNS;
	public $outNomeMunNascto;
	public $outUFMunNascto;
	public $outCodMunNasctoDNE;

	public function __construct(
		string $outNumRA,
		string $outDigitoRA,
		string $outSiglaUFRA,
		string $outNomeAluno,
		string $outDataNascimento,
		string $outCorRaca,
		string $outDescCorRaca,
		string $outSexo,
		string $outNomeMae,
		string $outNomePai,
		string $outNomeSocial,
		string $outNomeAfetivo,
		string $outEmail,
		string $outNacionalidade,
		string $outDescNacionalidade,
		string $outDataEntradaPais,
		string $outCodPaisOrigem,
		string $outNomePaisOrigem,
		string $outCodBolsaFamilia,
		string $outPossuiInternet,
		string $outPossuiNotebookSmartphoneTablet,
		string $outBolsaFamilia,
		string $outQuilombola,
		string $outTipoSanguineo,
		string $outDoadorOrgaos,
		string $outNumeroCNS,
		string $outNomeMunNascto,
		string $outUFMunNascto,
		string $outCodMunNasctoDNE
	) {
		$this->outNumRA = $outNumRA;
		$this->outDigitoRA = $outDigitoRA;
		$this->outSiglaUFRA = $outSiglaUFRA;
		$this->outNomeAluno = $outNomeAluno;
		$this->outDataNascimento = $outDataNascimento;
		$this->outCorRaca = $outCorRaca;
		$this->outDescCorRaca = $outDescCorRaca;
		$this->outSexo = $outSexo;
		$this->outNomeMae = $outNomeMae;
		$this->outNomePai = $outNomePai;
		$this->outNomeSocial = $outNomeSocial;
		$this->outNomeAfetivo = $outNomeAfetivo;
		$this->outEmail = $outEmail;
		$this->outNacionalidade = $outNacionalidade;
		$this->outDescNacionalidade = $outDescNacionalidade;
		$this->outDataEntradaPais = $outDataEntradaPais;
		$this->outCodPaisOrigem = $outCodPaisOrigem;
		$this->outNomePaisOrigem = $outNomePaisOrigem;
		$this->outCodBolsaFamilia = $outCodBolsaFamilia;
		$this->outPossuiInternet = $outPossuiInternet;
		$this->outPossuiNotebookSmartphoneTablet  = $outPossuiNotebookSmartphoneTablet ;
		$this->outBolsaFamilia = $outBolsaFamilia;
		$this->outQuilombola = $outQuilombola;
		$this->outTipoSanguineo = $outTipoSanguineo;
		$this->outDoadorOrgaos = $outDoadorOrgaos;
		$this->outNumeroCNS = $outNumeroCNS;
		$this->outNomeMunNascto = $outNomeMunNascto;
		$this->outUFMunNascto = $outUFMunNascto;
		$this->outCodMunNasctoDNE = $outCodMunNasctoDNE;
	}

	public function jsonSerialize()
    {
		$filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }

	/**
	 * Get the value of outNumRA
	 */
	public function getOutNumRA()
	{
		return $this->outNumRA;
	}

	/**
	 * Set the value of outNumRA
	 */
	public function setOutNumRA($outNumRA): self
	{
		$this->outNumRA = $outNumRA;

		return $this;
	}

	/**
	 * Get the value of outDigitoRA
	 */
	public function getOutDigitoRA()
	{
		return $this->outDigitoRA;
	}

	/**
	 * Set the value of outDigitoRA
	 */
	public function setOutDigitoRA($outDigitoRA): self
	{
		$this->outDigitoRA = $outDigitoRA;

		return $this;
	}

	/**
	 * Get the value of outSiglaUFRA
	 */
	public function getOutSiglaUFRA()
	{
		return $this->outSiglaUFRA;
	}

	/**
	 * Set the value of outSiglaUFRA
	 */
	public function setOutSiglaUFRA($outSiglaUFRA): self
	{
		$this->outSiglaUFRA = $outSiglaUFRA;

		return $this;
	}

	/**
	 * Get the value of outNomeAluno
	 */
	public function getOutNomeAluno()
	{
		return $this->outNomeAluno;
	}

	/**
	 * Set the value of outNomeAluno
	 */
	public function setOutNomeAluno($outNomeAluno): self
	{
		$this->outNomeAluno = $outNomeAluno;

		return $this;
	}

	/**
	 * Get the value of outDataNascimento
	 */
	public function getOutDataNascimento()
	{
		return $this->outDataNascimento;
	}

	/**
	 * Set the value of outDataNascimento
	 */
	public function setOutDataNascimento($outDataNascimento): self
	{
		$this->outDataNascimento = $outDataNascimento;

		return $this;
	}

	/**
	 * Get the value of outCorRaca
	 */
	public function getOutCorRaca()
	{
		return $this->outCorRaca;
	}

	/**
	 * Set the value of outCorRaca
	 */
	public function setOutCorRaca($outCorRaca): self
	{
		$this->outCorRaca = $outCorRaca;

		return $this;
	}

	/**
	 * Get the value of outDescCorRaca
	 */
	public function getOutDescCorRaca()
	{
		return $this->outDescCorRaca;
	}

	/**
	 * Set the value of outDescCorRaca
	 */
	public function setOutDescCorRaca($outDescCorRaca): self
	{
		$this->outDescCorRaca = $outDescCorRaca;

		return $this;
	}

	/**
	 * Get the value of outSexo
	 */
	public function getOutSexo()
	{
		return $this->outSexo;
	}

	/**
	 * Set the value of outSexo
	 */
	public function setOutSexo($outSexo): self
	{
		$this->outSexo = $outSexo;

		return $this;
	}

	/**
	 * Get the value of outNomeMae
	 */
	public function getOutNomeMae()
	{
		return $this->outNomeMae;
	}

	/**
	 * Set the value of outNomeMae
	 */
	public function setOutNomeMae($outNomeMae): self
	{
		$this->outNomeMae = $outNomeMae;

		return $this;
	}

	/**
	 * Get the value of outNomePai
	 */
	public function getOutNomePai()
	{
		return $this->outNomePai;
	}

	/**
	 * Set the value of outNomePai
	 */
	public function setOutNomePai($outNomePai): self
	{
		$this->outNomePai = $outNomePai;

		return $this;
	}

	/**
	 * Get the value of outNomeSocial
	 */
	public function getOutNomeSocial()
	{
		return $this->outNomeSocial;
	}

	/**
	 * Set the value of outNomeSocial
	 */
	public function setOutNomeSocial($outNomeSocial): self
	{
		$this->outNomeSocial = $outNomeSocial;

		return $this;
	}

	/**
	 * Get the value of outNomeAfetivo
	 */
	public function getOutNomeAfetivo()
	{
		return $this->outNomeAfetivo;
	}

	/**
	 * Set the value of outNomeAfetivo
	 */
	public function setOutNomeAfetivo($outNomeAfetivo): self
	{
		$this->outNomeAfetivo = $outNomeAfetivo;

		return $this;
	}

	/**
	 * Get the value of outEmail
	 */
	public function getOutEmail()
	{
		return $this->outEmail;
	}

	/**
	 * Set the value of outEmail
	 */
	public function setOutEmail($outEmail): self
	{
		$this->outEmail = $outEmail;

		return $this;
	}

	/**
	 * Get the value of outNacionalidade
	 */
	public function getOutNacionalidade()
	{
		return $this->outNacionalidade;
	}

	/**
	 * Set the value of outNacionalidade
	 */
	public function setOutNacionalidade($outNacionalidade): self
	{
		$this->outNacionalidade = $outNacionalidade;

		return $this;
	}

	/**
	 * Get the value of outDescNacionalidade
	 */
	public function getOutDescNacionalidade()
	{
		return $this->outDescNacionalidade;
	}

	/**
	 * Set the value of outDescNacionalidade
	 */
	public function setOutDescNacionalidade($outDescNacionalidade): self
	{
		$this->outDescNacionalidade = $outDescNacionalidade;

		return $this;
	}

	/**
	 * Get the value of outDataEntradaPais
	 */
	public function getOutDataEntradaPais()
	{
		return $this->outDataEntradaPais;
	}

	/**
	 * Set the value of outDataEntradaPais
	 */
	public function setOutDataEntradaPais($outDataEntradaPais): self
	{
		$this->outDataEntradaPais = $outDataEntradaPais;

		return $this;
	}

	/**
	 * Get the value of outCodPaisOrigem
	 */
	public function getOutCodPaisOrigem()
	{
		return $this->outCodPaisOrigem;
	}

	/**
	 * Set the value of outCodPaisOrigem
	 */
	public function setOutCodPaisOrigem($outCodPaisOrigem): self
	{
		$this->outCodPaisOrigem = $outCodPaisOrigem;

		return $this;
	}

	/**
	 * Get the value of outNomePaisOrigem
	 */
	public function getOutNomePaisOrigem()
	{
		return $this->outNomePaisOrigem;
	}

	/**
	 * Set the value of outNomePaisOrigem
	 */
	public function setOutNomePaisOrigem($outNomePaisOrigem): self
	{
		$this->outNomePaisOrigem = $outNomePaisOrigem;

		return $this;
	}

	/**
	 * Get the value of outCodBolsaFamilia
	 */
	public function getOutCodBolsaFamilia()
	{
		return $this->outCodBolsaFamilia;
	}

	/**
	 * Set the value of outCodBolsaFamilia
	 */
	public function setOutCodBolsaFamilia($outCodBolsaFamilia): self
	{
		$this->outCodBolsaFamilia = $outCodBolsaFamilia;

		return $this;
	}

	/**
	 * Get the value of outPossuiInternet
	 */
	public function getOutPossuiInternet()
	{
		return $this->outPossuiInternet;
	}

	/**
	 * Set the value of outPossuiInternet
	 */
	public function setOutPossuiInternet($outPossuiInternet): self
	{
		$this->outPossuiInternet = $outPossuiInternet;

		return $this;
	}

	/**
	 * Get the value of outPossuiNotebookSmartphoneTablet
	 */
	public function getOutPossuiNotebookSmartphoneTablet()
	{
		return $this->outPossuiNotebookSmartphoneTablet;
	}

	/**
	 * Set the value of outPossuiNotebookSmartphoneTablet
	 */
	public function setOutPossuiNotebookSmartphoneTablet($outPossuiNotebookSmartphoneTablet): self
	{
		$this->outPossuiNotebookSmartphoneTablet = $outPossuiNotebookSmartphoneTablet;

		return $this;
	}

	/**
	 * Get the value of outBolsaFamilia
	 */
	public function getOutBolsaFamilia()
	{
		return $this->outBolsaFamilia;
	}

	/**
	 * Set the value of outBolsaFamilia
	 */
	public function setOutBolsaFamilia($outBolsaFamilia): self
	{
		$this->outBolsaFamilia = $outBolsaFamilia;

		return $this;
	}

	/**
	 * Get the value of outQuilombola
	 */
	public function getOutQuilombola()
	{
		return $this->outQuilombola;
	}

	/**
	 * Set the value of outQuilombola
	 */
	public function setOutQuilombola($outQuilombola): self
	{
		$this->outQuilombola = $outQuilombola;

		return $this;
	}

	/**
	 * Get the value of outTipoSanguineo
	 */
	public function getOutTipoSanguineo()
	{
		return $this->outTipoSanguineo;
	}

	/**
	 * Set the value of outTipoSanguineo
	 */
	public function setOutTipoSanguineo($outTipoSanguineo): self
	{
		$this->outTipoSanguineo = $outTipoSanguineo;

		return $this;
	}

	/**
	 * Get the value of outDoadorOrgaos
	 */
	public function getOutDoadorOrgaos()
	{
		return $this->outDoadorOrgaos;
	}

	/**
	 * Set the value of outDoadorOrgaos
	 */
	public function setOutDoadorOrgaos($outDoadorOrgaos): self
	{
		$this->outDoadorOrgaos = $outDoadorOrgaos;

		return $this;
	}

	/**
	 * Get the value of outNumeroCNS
	 */
	public function getOutNumeroCNS()
	{
		return $this->outNumeroCNS;
	}

	/**
	 * Set the value of outNumeroCNS
	 */
	public function setOutNumeroCNS($outNumeroCNS): self
	{
		$this->outNumeroCNS = $outNumeroCNS;

		return $this;
	}

	/**
	 * Get the value of outNomeMunNascto
	 */
	public function getOutNomeMunNascto()
	{
		return $this->outNomeMunNascto;
	}

	/**
	 * Set the value of outNomeMunNascto
	 */
	public function setOutNomeMunNascto($outNomeMunNascto): self
	{
		$this->outNomeMunNascto = $outNomeMunNascto;

		return $this;
	}

	/**
	 * Get the value of outUFMunNascto
	 */
	public function getOutUFMunNascto()
	{
		return $this->outUFMunNascto;
	}

	/**
	 * Set the value of outUFMunNascto
	 */
	public function setOutUFMunNascto($outUFMunNascto): self
	{
		$this->outUFMunNascto = $outUFMunNascto;

		return $this;
	}

	/**
	 * Get the value of outCodMunNasctoDNE
	 */
	public function getOutCodMunNasctoDNE()
	{
		return $this->outCodMunNasctoDNE;
	}

	/**
	 * Set the value of outCodMunNasctoDNE
	 */
	public function setOutCodMunNasctoDNE($outCodMunNasctoDNE): self
	{
		$this->outCodMunNasctoDNE = $outCodMunNasctoDNE;

		return $this;
	}
}