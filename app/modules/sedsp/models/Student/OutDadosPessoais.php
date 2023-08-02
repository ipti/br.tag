<?php

class OutDadosPessoais
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
		?string $outNumRA,
		?string $outDigitoRA,
		?string $outSiglaUFRA,
		?string $outNomeAluno,
		?string $outDataNascimento,
		?string $outCorRaca,
		?string $outDescCorRaca,
		?string $outSexo,
		?string $outNomeMae,
		?string $outNomePai,
		?string $outNomeSocial,
		?string $outNomeAfetivo,
		?string $outEmail,
		?string $outNacionalidade,
		?string $outDescNacionalidade,
		?string $outDataEntradaPais,
		?string $outCodPaisOrigem,
		?string $outNomePaisOrigem,
		?string $outCodBolsaFamilia,
		?string $outPossuiInternet,
		?string $outPossuiNotebookSmartphoneTablet,
		?string $outBolsaFamilia,
		?string $outQuilombola,
		?string $outTipoSanguineo,
		?string $outDoadorOrgaos,
		?string $outNumeroCNS,
		?string $outNomeMunNascto,
		?string $outUFMunNascto,
		?string $outCodMunNasctoDNE
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
		$this-> outPossuiNotebookSmartphoneTablet  = $outPossuiNotebookSmartphoneTablet ;
		$this->outBolsaFamilia = $outBolsaFamilia;
		$this->outQuilombola = $outQuilombola;
		$this->outTipoSanguineo = $outTipoSanguineo;
		$this->outDoadorOrgaos = $outDoadorOrgaos;
		$this->outNumeroCNS = $outNumeroCNS;
		$this->outNomeMunNascto = $outNomeMunNascto;
		$this->outUFMunNascto = $outUFMunNascto;
		$this->outCodMunNasctoDNE = $outCodMunNasctoDNE;
	}

	public function getOutNumRa(): ?string
	{
		return $this->outNumRA;
	}

	public function getOutDigitoRa(): ?string
	{
		return $this->outDigitoRA;
	}

	public function getOutSiglaUfra(): ?string
	{
		return $this->outSiglaUFRA;
	}

	public function getOutNomeAluno(): ?string
	{
		return $this->outNomeAluno;
	}

	public function getOutDataNascimento(): ?string
	{
		return $this->outDataNascimento;
	}

	public function getOutCorRaca(): ?string
	{
		return $this->outCorRaca;
	}

	public function getOutDescCorRaca(): ?string
	{
		return $this->outDescCorRaca;
	}

	public function getOutSexo(): ?string
	{
		return $this->outSexo;
	}

	public function getOutNomeMae(): ?string
	{
		return $this->outNomeMae;
	}

	public function getOutNomePai(): ?string
	{
		return $this->outNomePai;
	}

	public function getOutNomeSocial(): ?string
	{
		return $this->outNomeSocial;
	}

	public function getOutNomeAfetivo(): ?string
	{
		return $this->outNomeAfetivo;
	}

	public function getOutEmail(): ?string
	{
		return $this->outEmail;
	}

	public function getOutNacionalidade(): ?string
	{
		return $this->outNacionalidade;
	}

	public function getOutDescNacionalidade(): ?string
	{
		return $this->outDescNacionalidade;
	}

	public function getOutDataEntradaPais(): ?string
	{
		return $this->outDataEntradaPais;
	}

	public function getOutCodPaisOrigem(): ?string
	{
		return $this->outCodPaisOrigem;
	}

	public function getOutNomePaisOrigem(): ?string
	{
		return $this->outNomePaisOrigem;
	}

	public function getOutCodBolsaFamilia(): ?string
	{
		return $this->outCodBolsaFamilia;
	}

	public function getOutPossuiInternet(): ?string
	{
		return $this->outPossuiInternet;
	}

	public function getOutPossuiNotebookSmartphoneTablet(): ?string
	{
		return $this-> outPossuiNotebookSmartphoneTablet ;
	}

	public function getOutBolsaFamilia(): ?string
	{
		return $this->outBolsaFamilia;
	}

	public function getOutQuilombola(): ?string
	{
		return $this->outQuilombola;
	}

	public function getOutTipoSanguineo(): ?string
	{
		return $this->outTipoSanguineo;
	}

	public function getOutDoadorOrgaos(): ?string
	{
		return $this->outDoadorOrgaos;
	}

	public function getOutNumeroCns(): ?string
	{
		return $this->outNumeroCNS;
	}

	public function getOutNomeMunNascto(): ?string
	{
		return $this->outNomeMunNascto;
	}

	public function getOutUfMunNascto(): ?string
	{
		return $this->outUFMunNascto;
	}

	public function getOutCodMunNasctoDne(): ?string
	{
		return $this->outCodMunNasctoDNE;
	}

	public function setOutNumRa(?string $outNumRA): self
	{
		$this->outNumRA = $outNumRA;
		return $this;
	}

	public function setOutDigitoRa(?string $outDigitoRA): self
	{
		$this->outDigitoRA = $outDigitoRA;
		return $this;
	}

	public function setOutSiglaUfra(?string $outSiglaUFRA): self
	{
		$this->outSiglaUFRA = $outSiglaUFRA;
		return $this;
	}

	public function setOutNomeAluno(?string $outNomeAluno): self
	{
		$this->outNomeAluno = $outNomeAluno;
		return $this;
	}

	public function setOutDataNascimento(?string $outDataNascimento): self
	{
		$this->outDataNascimento = $outDataNascimento;
		return $this;
	}

	public function setOutCorRaca(?string $outCorRaca): self
	{
		$this->outCorRaca = $outCorRaca;
		return $this;
	}

	public function setOutDescCorRaca(?string $outDescCorRaca): self
	{
		$this->outDescCorRaca = $outDescCorRaca;
		return $this;
	}

	public function setOutSexo(?string $outSexo): self
	{
		$this->outSexo = $outSexo;
		return $this;
	}

	public function setOutNomeMae(?string $outNomeMae): self
	{
		$this->outNomeMae = $outNomeMae;
		return $this;
	}

	public function setOutNomePai(?string $outNomePai): self
	{
		$this->outNomePai = $outNomePai;
		return $this;
	}

	public function setOutNomeSocial(?string $outNomeSocial): self
	{
		$this->outNomeSocial = $outNomeSocial;
		return $this;
	}

	public function setOutNomeAfetivo(?string $outNomeAfetivo): self
	{
		$this->outNomeAfetivo = $outNomeAfetivo;
		return $this;
	}

	public function setOutEmail(?string $outEmail): self
	{
		$this->outEmail = $outEmail;
		return $this;
	}

	public function setOutNacionalidade(?string $outNacionalidade): self
	{
		$this->outNacionalidade = $outNacionalidade;
		return $this;
	}

	public function setOutDescNacionalidade(?string $outDescNacionalidade): self
	{
		$this->outDescNacionalidade = $outDescNacionalidade;
		return $this;
	}

	public function setOutDataEntradaPais(?string $outDataEntradaPais): self
	{
		$this->outDataEntradaPais = $outDataEntradaPais;
		return $this;
	}

	public function setOutCodPaisOrigem(?string $outCodPaisOrigem): self
	{
		$this->outCodPaisOrigem = $outCodPaisOrigem;
		return $this;
	}

	public function setOutNomePaisOrigem(?string $outNomePaisOrigem): self
	{
		$this->outNomePaisOrigem = $outNomePaisOrigem;
		return $this;
	}

	public function setOutCodBolsaFamilia(?string $outCodBolsaFamilia): self
	{
		$this->outCodBolsaFamilia = $outCodBolsaFamilia;
		return $this;
	}

	public function setOutPossuiInternet(?string $outPossuiInternet): self
	{
		$this->outPossuiInternet = $outPossuiInternet;
		return $this;
	}

	public function setOutPossuiNotebookSmartphoneTablet(?string $outPossuiNotebookSmartphoneTablet ): self
	{
		$this-> outPossuiNotebookSmartphoneTablet  = $outPossuiNotebookSmartphoneTablet ;
		return $this;
	}

	public function setOutBolsaFamilia(?string $outBolsaFamilia): self
	{
		$this->outBolsaFamilia = $outBolsaFamilia;
		return $this;
	}

	public function setOutQuilombola(?string $outQuilombola): self
	{
		$this->outQuilombola = $outQuilombola;
		return $this;
	}

	public function setOutTipoSanguineo(?string $outTipoSanguineo): self
	{
		$this->outTipoSanguineo = $outTipoSanguineo;
		return $this;
	}

	public function setOutDoadorOrgaos(?string $outDoadorOrgaos): self
	{
		$this->outDoadorOrgaos = $outDoadorOrgaos;
		return $this;
	}

	public function setOutNumeroCns(?string $outNumeroCNS): self
	{
		$this->outNumeroCNS = $outNumeroCNS;
		return $this;
	}

	public function setOutNomeMunNascto(?string $outNomeMunNascto): self
	{
		$this->outNomeMunNascto = $outNomeMunNascto;
		return $this;
	}

	public function setOutUfMunNascto(?string $outUFMunNascto): self
	{
		$this->outUFMunNascto = $outUFMunNascto;
		return $this;
	}

	public function setOutCodMunNasctoDne(?string $outCodMunNasctoDNE): self
	{
		$this->outCodMunNasctoDNE = $outCodMunNasctoDNE;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outNumRA'] ?? null,
			$data['outDigitoRA'] ?? null,
			$data['outSiglaUFRA'] ?? null,
			$data['outNomeAluno'] ?? null,
			$data['outDataNascimento'] ?? null,
			$data['outCorRaca'] ?? null,
			$data['outDescCorRaca'] ?? null,
			$data['outSexo'] ?? null,
			$data['outNomeMae'] ?? null,
			$data['outNomePai'] ?? null,
			$data['outNomeSocial'] ?? null,
			$data['outNomeAfetivo'] ?? null,
			$data['outEmail'] ?? null,
			$data['outNacionalidade'] ?? null,
			$data['outDescNacionalidade'] ?? null,
			$data['outDataEntradaPais'] ?? null,
			$data['outCodPaisOrigem'] ?? null,
			$data['outNomePaisOrigem'] ?? null,
			$data['outCodBolsaFamilia'] ?? null,
			$data['outPossuiInternet'] ?? null,
			$data['outPossuiNotebookSmartphoneTablet'] ?? null,
			$data['outBolsaFamilia'] ?? null,
			$data['outQuilombola'] ?? null,
			$data['outTipoSanguineo'] ?? null,
			$data['outDoadorOrgaos'] ?? null,
			$data['outNumeroCNS'] ?? null,
			$data['outNomeMunNascto'] ?? null,
			$data['outUFMunNascto'] ?? null,
			$data['outCodMunNasctoDNE'] ?? null
		);
	}
}