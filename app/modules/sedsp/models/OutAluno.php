<?php

class OutAluno
{
	public $outSucesso;
	public $outProcessoId;
	public $outDataAlteracaoFicha;
	public $outOperador;
	public $outDadosPessoais;
	public $outDocumentos;
	public $outJustificativaDocumentos;
	public $outCertidaoNova;
	public $outCertidaoAntiga;
	public $outEnderecoResidencial;
	public $outEnderecoIndicativo;
	public $outTelefones;
	public $outDeficiencia;
	public $outRecursoAvaliacao;

	public function __construct(array $outData) {
		$this->outSucesso = $outData['outSucesso'];
		$this->outProcessoId =  $outData['outProcessoID'];
		$this->outDataAlteracaoFicha = $outData['outDataAlteracaoFicha'];
		$this->outOperador = $outData['outOperador'];
		$this->outDadosPessoais = $outData['outDadosPessoais'];
		$this->outDocumentos = $outData['outDocumentos'];
		$this->outJustificativaDocumentos = $outData['outJustificativaDocumentos'];
		$this->outCertidaoNova = $outData['outCertidaoNova'];
		$this->outCertidaoAntiga = $outData['outCertidaoAntiga'];
		$this->outEnderecoResidencial = $outData['outEnderecoResidencial'];
		$this->outEnderecoIndicativo = $outData['outEnderecoIndicativo'];
		$this->outTelefones = $outData['outTelefones'];
		$this->outDeficiencia = $outData['outDeficiencia'];
		$this->outRecursoAvaliacao = $outData['outRecursoAvaliacao'];
	}

	public function getOutSucesso(): string
	{
		return $this->outSucesso;
	}

	public function getOutProcessoId(): string
	{
		return $this->outProcessoId;
	}

	public function getOutDataAlteracaoFicha(): string
	{
		return $this->outDataAlteracaoFicha;
	}

	public function getOutOperador(): string
	{
		return $this->outOperador;
	}

	public function getOutDadosPessoais(): OutDadosPessoais
	{
		return $this->outDadosPessoais;
	}

	public function getOutDocumentos(): OutDocumentos
	{
		return $this->outDocumentos;
	}

	public function getOutJustificativaDocumentos(): string
	{
		return $this->outJustificativaDocumentos;
	}

	public function getOutCertidaoNova(): OutCertidaoNova
	{
		return $this->outCertidaoNova;
	}

	public function getOutCertidaoAntiga(): OutCertidaoAntiga
	{
		return $this->outCertidaoAntiga;
	}

	public function getOutEnderecoResidencial(): OutEnderecoResidencial
	{
		return $this->outEnderecoResidencial;
	}

	public function getOutEnderecoIndicativo(): OutEnderecoIndicativo
	{
		return $this->outEnderecoIndicativo;
	}

	public function getOutTelefones(): array
	{
		return $this->outTelefones;
	}

	public function getOutDeficiencia(): OutDeficiencia
	{
		return $this->outDeficiencia;
	}

	public function getOutRecursoAvaliacao(): OutRecursoAvaliacao
	{
		return $this->outRecursoAvaliacao;
	}

	public function setOutSucesso(string $outSucesso): self
	{
		$this->outSucesso = $outSucesso;
		return $this;
	}

	public function setOutProcessoId(string $outProcessoId): self
	{
		$this->outProcessoId = $outProcessoId;
		return $this;
	}

	public function setOutDataAlteracaoFicha(string $outDataAlteracaoFicha): self
	{
		$this->outDataAlteracaoFicha = $outDataAlteracaoFicha;
		return $this;
	}

	public function setOutOperador(string $outOperador): self
	{
		$this->outOperador = $outOperador;
		return $this;
	}

	public function setOutDadosPessoais(OutDadosPessoais $outDadosPessoais): self
	{
		$this->outDadosPessoais = $outDadosPessoais;
		return $this;
	}

	public function setOutDocumentos(OutDocumentos $outDocumentos): self
	{
		$this->outDocumentos = $outDocumentos;
		return $this;
	}

	public function setOutJustificativaDocumentos(string $outJustificativaDocumentos): self
	{
		$this->outJustificativaDocumentos = $outJustificativaDocumentos;
		return $this;
	}

	public function setOutCertidaoNova(OutCertidaoNova $outCertidaoNova): self
	{
		$this->outCertidaoNova = $outCertidaoNova;
		return $this;
	}

	public function setOutCertidaoAntiga(OutCertidaoAntiga $outCertidaoAntiga): self
	{
		$this->outCertidaoAntiga = $outCertidaoAntiga;
		return $this;
	}

	public function setOutEnderecoResidencial(OutEnderecoResidencial $outEnderecoResidencial): self
	{
		$this->outEnderecoResidencial = $outEnderecoResidencial;
		return $this;
	}

	public function setOutEnderecoIndicativo(OutEnderecoIndicativo $outEnderecoIndicativo): self
	{
		$this->outEnderecoIndicativo = $outEnderecoIndicativo;
		return $this;
	}

	public function setOutTelefones(array $outTelefones): self
	{
		$this->outTelefones = $outTelefones;
		return $this;
	}

	public function setOutDeficiencia(OutDeficiencia $outDeficiencia): self
	{
		$this->outDeficiencia = $outDeficiencia;
		return $this;
	}

	public function setOutRecursoAvaliacao(OutRecursoAvaliacao $outRecursoAvaliacao): self
	{
		$this->outRecursoAvaliacao = $outRecursoAvaliacao;
		return $this;
	}
}

class OutDadosPessoais
{
	public $outNumRa;
	public $outDigitoRa;
	public $outSiglaUfra;
	public $outNomeAluno;
	public $outDataNascimento;
	public $outCorRaca;
	public $outDescCorRaca;
	public $outCodSexo;
	public $outSexo;
	public $outNomeMae;
	public $outNomePai;
	public $outNomeSocial;
	public $outNomeAfetivo;
	public $outEmail;
	public $outEmailGoogle;
	public $outEmailMicrosoft;
	public $outNacionalidade;
	public $outDescNacionalidade;
	public $outCodPaisOrigem;
	public $outNomePaisOrigem;
	public $outDataEntradaPais;
	public $outBolsaFamilia;
	public $outQuilombola;
	public $outPossuiInternet;
	public $outPossuiNotebookSmartphoneTablet;
	public $outNomeMunNascto;
	public $outUfMunNascto;
	public $outAlunoFalecido;
	public $outDataFalecimento;
	public $outCodMunNasctoDne;
	public $outCodBolsaFamilia;
	public $outDoadorOrgaos;

	public function getOutNumRa(): string
	{
		return $this->outNumRa;
	}

	public function getOutDigitoRa(): string
	{
		return $this->outDigitoRa;
	}

	public function getOutSiglaUfra(): string
	{
		return $this->outSiglaUfra;
	}

	public function getOutNomeAluno(): string
	{
		return $this->outNomeAluno;
	}

	public function getOutDataNascimento(): string
	{
		return $this->outDataNascimento;
	}

	public function getOutCorRaca(): string
	{
		return $this->outCorRaca;
	}

	public function getOutDescCorRaca(): string
	{
		return $this->outDescCorRaca;
	}

	public function getOutCodSexo(): string
	{
		return $this->outCodSexo;
	}

	public function getOutSexo(): string
	{
		return $this->outSexo;
	}

	public function getOutNomeMae(): string
	{
		return $this->outNomeMae;
	}

	public function getOutNomePai(): string
	{
		return $this->outNomePai;
	}

	public function getOutNomeSocial(): string
	{
		return $this->outNomeSocial;
	}

	public function getOutNomeAfetivo(): string
	{
		return $this->outNomeAfetivo;
	}

	public function getOutEmail(): string
	{
		return $this->outEmail;
	}

	public function getOutEmailGoogle(): string
	{
		return $this->outEmailGoogle;
	}

	public function getOutEmailMicrosoft(): string
	{
		return $this->outEmailMicrosoft;
	}

	public function getOutNacionalidade(): string
	{
		return $this->outNacionalidade;
	}

	public function getOutDescNacionalidade(): string
	{
		return $this->outDescNacionalidade;
	}

	public function getOutCodPaisOrigem(): string
	{
		return $this->outCodPaisOrigem;
	}

	public function getOutNomePaisOrigem(): string
	{
		return $this->outNomePaisOrigem;
	}

	public function getOutDataEntradaPais(): string
	{
		return $this->outDataEntradaPais;
	}

	public function getOutBolsaFamilia(): string
	{
		return $this->outBolsaFamilia;
	}

	public function getOutQuilombola(): string
	{
		return $this->outQuilombola;
	}

	public function getOutPossuiInternet(): string
	{
		return $this->outPossuiInternet;
	}

	public function getOutPossuiNotebookSmartphoneTablet(): string
	{
		return $this->outPossuiNotebookSmartphoneTablet;
	}

	public function getOutNomeMunNascto(): string
	{
		return $this->outNomeMunNascto;
	}

	public function getOutUfMunNascto(): string
	{
		return $this->outUfMunNascto;
	}

	public function getOutAlunoFalecido(): string
	{
		return $this->outAlunoFalecido;
	}

	public function getOutDataFalecimento(): string
	{
		return $this->outDataFalecimento;
	}

	public function getOutCodMunNasctoDne(): string
	{
		return $this->outCodMunNasctoDne;
	}

	public function getOutCodBolsaFamilia(): string
	{
		return $this->outCodBolsaFamilia;
	}

	public function getOutDoadorOrgaos(): string
	{
		return $this->outDoadorOrgaos;
	}

	public function setOutNumRa(string $outNumRa): self
	{
		$this->outNumRa = $outNumRa;
		return $this;
	}

	public function setOutDigitoRa(string $outDigitoRa): self
	{
		$this->outDigitoRa = $outDigitoRa;
		return $this;
	}

	public function setOutSiglaUfra(string $outSiglaUfra): self
	{
		$this->outSiglaUfra = $outSiglaUfra;
		return $this;
	}

	public function setOutNomeAluno(string $outNomeAluno): self
	{
		$this->outNomeAluno = $outNomeAluno;
		return $this;
	}

	public function setOutDataNascimento(string $outDataNascimento): self
	{
		$this->outDataNascimento = $outDataNascimento;
		return $this;
	}

	public function setOutCorRaca(string $outCorRaca): self
	{
		$this->outCorRaca = $outCorRaca;
		return $this;
	}

	public function setOutDescCorRaca(string $outDescCorRaca): self
	{
		$this->outDescCorRaca = $outDescCorRaca;
		return $this;
	}

	public function setOutCodSexo(string $outCodSexo): self
	{
		$this->outCodSexo = $outCodSexo;
		return $this;
	}

	public function setOutSexo(string $outSexo): self
	{
		$this->outSexo = $outSexo;
		return $this;
	}

	public function setOutNomeMae(string $outNomeMae): self
	{
		$this->outNomeMae = $outNomeMae;
		return $this;
	}

	public function setOutNomePai(string $outNomePai): self
	{
		$this->outNomePai = $outNomePai;
		return $this;
	}

	public function setOutNomeSocial(string $outNomeSocial): self
	{
		$this->outNomeSocial = $outNomeSocial;
		return $this;
	}

	public function setOutNomeAfetivo(string $outNomeAfetivo): self
	{
		$this->outNomeAfetivo = $outNomeAfetivo;
		return $this;
	}

	public function setOutEmail(string $outEmail): self
	{
		$this->outEmail = $outEmail;
		return $this;
	}

	public function setOutEmailGoogle(string $outEmailGoogle): self
	{
		$this->outEmailGoogle = $outEmailGoogle;
		return $this;
	}

	public function setOutEmailMicrosoft(string $outEmailMicrosoft): self
	{
		$this->outEmailMicrosoft = $outEmailMicrosoft;
		return $this;
	}

	public function setOutNacionalidade(string $outNacionalidade): self
	{
		$this->outNacionalidade = $outNacionalidade;
		return $this;
	}

	public function setOutDescNacionalidade(string $outDescNacionalidade): self
	{
		$this->outDescNacionalidade = $outDescNacionalidade;
		return $this;
	}

	public function setOutCodPaisOrigem(string $outCodPaisOrigem): self
	{
		$this->outCodPaisOrigem = $outCodPaisOrigem;
		return $this;
	}

	public function setOutNomePaisOrigem(string $outNomePaisOrigem): self
	{
		$this->outNomePaisOrigem = $outNomePaisOrigem;
		return $this;
	}

	public function setOutDataEntradaPais(string $outDataEntradaPais): self
	{
		$this->outDataEntradaPais = $outDataEntradaPais;
		return $this;
	}

	public function setOutBolsaFamilia(string $outBolsaFamilia): self
	{
		$this->outBolsaFamilia = $outBolsaFamilia;
		return $this;
	}

	public function setOutQuilombola(string $outQuilombola): self
	{
		$this->outQuilombola = $outQuilombola;
		return $this;
	}

	public function setOutPossuiInternet(string $outPossuiInternet): self
	{
		$this->outPossuiInternet = $outPossuiInternet;
		return $this;
	}

	public function setOutPossuiNotebookSmartphoneTablet(string $outPossuiNotebookSmartphoneTablet): self
	{
		$this->outPossuiNotebookSmartphoneTablet = $outPossuiNotebookSmartphoneTablet;
		return $this;
	}

	public function setOutNomeMunNascto(string $outNomeMunNascto): self
	{
		$this->outNomeMunNascto = $outNomeMunNascto;
		return $this;
	}

	public function setOutUfMunNascto(string $outUfMunNascto): self
	{
		$this->outUfMunNascto = $outUfMunNascto;
		return $this;
	}

	public function setOutAlunoFalecido(string $outAlunoFalecido): self
	{
		$this->outAlunoFalecido = $outAlunoFalecido;
		return $this;
	}

	public function setOutDataFalecimento(string $outDataFalecimento): self
	{
		$this->outDataFalecimento = $outDataFalecimento;
		return $this;
	}

	public function setOutCodMunNasctoDne(string $outCodMunNasctoDne): self
	{
		$this->outCodMunNasctoDne = $outCodMunNasctoDne;
		return $this;
	}

	public function setOutCodBolsaFamilia(string $outCodBolsaFamilia): self
	{
		$this->outCodBolsaFamilia = $outCodBolsaFamilia;
		return $this;
	}

	public function setOutDoadorOrgaos(string $outDoadorOrgaos): self
	{
		$this->outDoadorOrgaos = $outDoadorOrgaos;
		return $this;
	}
}

class OutDocumentos
{
	public $outCodInep;
	public $outCpf;
	public $outDataEmissaoDoctoCivil;
	public $outDataEmissaoCertidao;
	public $outNumeroCns;

	public function getOutCodInep(): string
	{
		return $this->outCodInep;
	}

	public function getOutCpf(): string
	{
		return $this->outCpf;
	}

	public function getOutDataEmissaoDoctoCivil(): string
	{
		return $this->outDataEmissaoDoctoCivil;
	}

	public function getOutDataEmissaoCertidao(): string
	{
		return $this->outDataEmissaoCertidao;
	}

	public function getOutNumeroCns(): string
	{
		return $this->outNumeroCns;
	}

	public function setOutCodInep(string $outCodInep): self
	{
		$this->outCodInep = $outCodInep;
		return $this;
	}

	public function setOutCpf(string $outCpf): self
	{
		$this->outCpf = $outCpf;
		return $this;
	}

	public function setOutDataEmissaoDoctoCivil(string $outDataEmissaoDoctoCivil): self
	{
		$this->outDataEmissaoDoctoCivil = $outDataEmissaoDoctoCivil;
		return $this;
	}

	public function setOutDataEmissaoCertidao(string $outDataEmissaoCertidao): self
	{
		$this->outDataEmissaoCertidao = $outDataEmissaoCertidao;
		return $this;
	}

	public function setOutNumeroCns(string $outNumeroCns): self
	{
		$this->outNumeroCns = $outNumeroCns;
		return $this;
	}
}

class OutCertidaoNova
{
	public $outCertMatr01;
	public $outCertMatr02;
	public $outCertMatr03;
	public $outCertMatr04;
	public $outCertMatr05;
	public $outCertMatr06;
	public $outCertMatr07;
	public $outCertMatr08;
	public $outCertMatr09;

	public function getOutCertMatr01(): string
	{
		return $this->outCertMatr01;
	}

	public function getOutCertMatr02(): string
	{
		return $this->outCertMatr02;
	}

	public function getOutCertMatr03(): string
	{
		return $this->outCertMatr03;
	}

	public function getOutCertMatr04(): string
	{
		return $this->outCertMatr04;
	}

	public function getOutCertMatr05(): string
	{
		return $this->outCertMatr05;
	}

	public function getOutCertMatr06(): string
	{
		return $this->outCertMatr06;
	}

	public function getOutCertMatr07(): string
	{
		return $this->outCertMatr07;
	}

	public function getOutCertMatr08(): string
	{
		return $this->outCertMatr08;
	}

	public function getOutCertMatr09(): string
	{
		return $this->outCertMatr09;
	}

	public function setOutCertMatr01(string $outCertMatr01): self
	{
		$this->outCertMatr01 = $outCertMatr01;
		return $this;
	}

	public function setOutCertMatr02(string $outCertMatr02): self
	{
		$this->outCertMatr02 = $outCertMatr02;
		return $this;
	}

	public function setOutCertMatr03(string $outCertMatr03): self
	{
		$this->outCertMatr03 = $outCertMatr03;
		return $this;
	}

	public function setOutCertMatr04(string $outCertMatr04): self
	{
		$this->outCertMatr04 = $outCertMatr04;
		return $this;
	}

	public function setOutCertMatr05(string $outCertMatr05): self
	{
		$this->outCertMatr05 = $outCertMatr05;
		return $this;
	}

	public function setOutCertMatr06(string $outCertMatr06): self
	{
		$this->outCertMatr06 = $outCertMatr06;
		return $this;
	}

	public function setOutCertMatr07(string $outCertMatr07): self
	{
		$this->outCertMatr07 = $outCertMatr07;
		return $this;
	}

	public function setOutCertMatr08(string $outCertMatr08): self
	{
		$this->outCertMatr08 = $outCertMatr08;
		return $this;
	}

	public function setOutCertMatr09(string $outCertMatr09): self
	{
		$this->outCertMatr09 = $outCertMatr09;
		return $this;
	}
}

class OutCertidaoAntiga
{
}

class OutEnderecoResidencial
{
	public $outLogradouro;
	public $outNumero;
	public $outAreaLogradouro;
	public $outComplemento;
	public $outBairro;
	public $outNomeCidade;
	public $outUfCidade;
	public $outLatitude;
	public $outLongitude;
	public $outCep;
	public $outCodMunicipioDne;
	public $outCodArea;
	public $outCodLocalizacao;
	public $outLocalizacaoDiferenciada;

	public function getOutLogradouro(): string
	{
		return $this->outLogradouro;
	}

	public function getOutNumero(): string
	{
		return $this->outNumero;
	}

	public function getOutAreaLogradouro(): string
	{
		return $this->outAreaLogradouro;
	}

	public function getOutComplemento(): string
	{
		return $this->outComplemento;
	}

	public function getOutBairro(): string
	{
		return $this->outBairro;
	}

	public function getOutNomeCidade(): string
	{
		return $this->outNomeCidade;
	}

	public function getOutUfCidade(): string
	{
		return $this->outUfCidade;
	}

	public function getOutLatitude(): string
	{
		return $this->outLatitude;
	}

	public function getOutLongitude(): string
	{
		return $this->outLongitude;
	}

	public function getOutCep(): string
	{
		return $this->outCep;
	}

	public function getOutCodMunicipioDne(): string
	{
		return $this->outCodMunicipioDne;
	}

	public function getOutCodArea(): string
	{
		return $this->outCodArea;
	}

	public function getOutCodLocalizacao(): string
	{
		return $this->outCodLocalizacao;
	}

	public function getOutLocalizacaoDiferenciada(): string
	{
		return $this->outLocalizacaoDiferenciada;
	}

	public function setOutLogradouro(string $outLogradouro): self
	{
		$this->outLogradouro = $outLogradouro;
		return $this;
	}

	public function setOutNumero(string $outNumero): self
	{
		$this->outNumero = $outNumero;
		return $this;
	}

	public function setOutAreaLogradouro(string $outAreaLogradouro): self
	{
		$this->outAreaLogradouro = $outAreaLogradouro;
		return $this;
	}

	public function setOutComplemento(string $outComplemento): self
	{
		$this->outComplemento = $outComplemento;
		return $this;
	}

	public function setOutBairro(string $outBairro): self
	{
		$this->outBairro = $outBairro;
		return $this;
	}

	public function setOutNomeCidade(string $outNomeCidade): self
	{
		$this->outNomeCidade = $outNomeCidade;
		return $this;
	}

	public function setOutUfCidade(string $outUfCidade): self
	{
		$this->outUfCidade = $outUfCidade;
		return $this;
	}

	public function setOutLatitude(string $outLatitude): self
	{
		$this->outLatitude = $outLatitude;
		return $this;
	}

	public function setOutLongitude(string $outLongitude): self
	{
		$this->outLongitude = $outLongitude;
		return $this;
	}

	public function setOutCep(string $outCep): self
	{
		$this->outCep = $outCep;
		return $this;
	}

	public function setOutCodMunicipioDne(string $outCodMunicipioDne): self
	{
		$this->outCodMunicipioDne = $outCodMunicipioDne;
		return $this;
	}

	public function setOutCodArea(string $outCodArea): self
	{
		$this->outCodArea = $outCodArea;
		return $this;
	}

	public function setOutCodLocalizacao(string $outCodLocalizacao): self
	{
		$this->outCodLocalizacao = $outCodLocalizacao;
		return $this;
	}

	public function setOutLocalizacaoDiferenciada(string $outLocalizacaoDiferenciada): self
	{
		$this->outLocalizacaoDiferenciada = $outLocalizacaoDiferenciada;
		return $this;
	}
}

class OutEnderecoIndicativo
{
	public $outLogradouro;
	public $outNumero;
	public $outBairro;
	public $outNomeCidade;
	public $outUfCidade;
	public $outLatitude;
	public $outLongitude;
	public $outCep;

	public function getOutLogradouro(): string
	{
		return $this->outLogradouro;
	}

	public function getOutNumero(): string
	{
		return $this->outNumero;
	}

	public function getOutBairro(): string
	{
		return $this->outBairro;
	}

	public function getOutNomeCidade(): string
	{
		return $this->outNomeCidade;
	}

	public function getOutUfCidade(): string
	{
		return $this->outUfCidade;
	}

	public function getOutLatitude(): string
	{
		return $this->outLatitude;
	}

	public function getOutLongitude(): string
	{
		return $this->outLongitude;
	}

	public function getOutCep(): string
	{
		return $this->outCep;
	}

	public function setOutLogradouro(string $outLogradouro): self
	{
		$this->outLogradouro = $outLogradouro;
		return $this;
	}

	public function setOutNumero(string $outNumero): self
	{
		$this->outNumero = $outNumero;
		return $this;
	}

	public function setOutBairro(string $outBairro): self
	{
		$this->outBairro = $outBairro;
		return $this;
	}

	public function setOutNomeCidade(string $outNomeCidade): self
	{
		$this->outNomeCidade = $outNomeCidade;
		return $this;
	}

	public function setOutUfCidade(string $outUfCidade): self
	{
		$this->outUfCidade = $outUfCidade;
		return $this;
	}

	public function setOutLatitude(string $outLatitude): self
	{
		$this->outLatitude = $outLatitude;
		return $this;
	}

	public function setOutLongitude(string $outLongitude): self
	{
		$this->outLongitude = $outLongitude;
		return $this;
	}

	public function setOutCep(string $outCep): self
	{
		$this->outCep = $outCep;
		return $this;
	}
}

class OutDeficiencia
{
	public $outMobilidadeReduzida;
	public $outTipoMobilidadeReduzida;
	public $outCuidador;
	public $outTipoCuidador;
	public $outProfSaude;
	public $outTipoProfSaude;

	public function getOutMobilidadeReduzida(): string
	{
		return $this->outMobilidadeReduzida;
	}

	public function getOutTipoMobilidadeReduzida(): string
	{
		return $this->outTipoMobilidadeReduzida;
	}

	public function getOutCuidador(): string
	{
		return $this->outCuidador;
	}

	public function getOutTipoCuidador(): string
	{
		return $this->outTipoCuidador;
	}

	public function getOutProfSaude(): string
	{
		return $this->outProfSaude;
	}

	public function getOutTipoProfSaude(): string
	{
		return $this->outTipoProfSaude;
	}

	public function setOutMobilidadeReduzida(string $outMobilidadeReduzida): self
	{
		$this->outMobilidadeReduzida = $outMobilidadeReduzida;
		return $this;
	}

	public function setOutTipoMobilidadeReduzida(string $outTipoMobilidadeReduzida): self
	{
		$this->outTipoMobilidadeReduzida = $outTipoMobilidadeReduzida;
		return $this;
	}

	public function setOutCuidador(string $outCuidador): self
	{
		$this->outCuidador = $outCuidador;
		return $this;
	}

	public function setOutTipoCuidador(string $outTipoCuidador): self
	{
		$this->outTipoCuidador = $outTipoCuidador;
		return $this;
	}

	public function setOutProfSaude(string $outProfSaude): self
	{
		$this->outProfSaude = $outProfSaude;
		return $this;
	}

	public function setOutTipoProfSaude(string $outTipoProfSaude): self
	{
		$this->outTipoProfSaude = $outTipoProfSaude;
		return $this;
	}
}

class OutRecursoAvaliacao
{
	public $outGuiaInterprete;
	public $outInterpreteLibras;
	public $outLeituraLabial;
	public $outNenhum;
	public $outProvaAmpliada;
	public $outTamanhoFonte;
	public $outProvaBraile;
	public $outAuxilioTranscricao;
	public $outAuxilioLeitor;
	public $outProvaVideoLibras;
	public $outCdAudioDefVisual;
	public $outProvaLinguaPortuguesa;

	public function getOutGuiaInterprete(): string
	{
		return $this->outGuiaInterprete;
	}

	public function getOutInterpreteLibras(): string
	{
		return $this->outInterpreteLibras;
	}

	public function getOutLeituraLabial(): string
	{
		return $this->outLeituraLabial;
	}

	public function getOutNenhum(): string
	{
		return $this->outNenhum;
	}

	public function getOutProvaAmpliada(): string
	{
		return $this->outProvaAmpliada;
	}

	public function getOutTamanhoFonte(): string
	{
		return $this->outTamanhoFonte;
	}

	public function getOutProvaBraile(): string
	{
		return $this->outProvaBraile;
	}

	public function getOutAuxilioTranscricao(): string
	{
		return $this->outAuxilioTranscricao;
	}

	public function getOutAuxilioLeitor(): string
	{
		return $this->outAuxilioLeitor;
	}

	public function getOutProvaVideoLibras(): string
	{
		return $this->outProvaVideoLibras;
	}

	public function getOutCdAudioDefVisual(): string
	{
		return $this->outCdAudioDefVisual;
	}

	public function getOutProvaLinguaPortuguesa(): string
	{
		return $this->outProvaLinguaPortuguesa;
	}

	public function setOutGuiaInterprete(string $outGuiaInterprete): self
	{
		$this->outGuiaInterprete = $outGuiaInterprete;
		return $this;
	}

	public function setOutInterpreteLibras(string $outInterpreteLibras): self
	{
		$this->outInterpreteLibras = $outInterpreteLibras;
		return $this;
	}

	public function setOutLeituraLabial(string $outLeituraLabial): self
	{
		$this->outLeituraLabial = $outLeituraLabial;
		return $this;
	}

	public function setOutNenhum(string $outNenhum): self
	{
		$this->outNenhum = $outNenhum;
		return $this;
	}

	public function setOutProvaAmpliada(string $outProvaAmpliada): self
	{
		$this->outProvaAmpliada = $outProvaAmpliada;
		return $this;
	}

	public function setOutTamanhoFonte(string $outTamanhoFonte): self
	{
		$this->outTamanhoFonte = $outTamanhoFonte;
		return $this;
	}

	public function setOutProvaBraile(string $outProvaBraile): self
	{
		$this->outProvaBraile = $outProvaBraile;
		return $this;
	}

	public function setOutAuxilioTranscricao(string $outAuxilioTranscricao): self
	{
		$this->outAuxilioTranscricao = $outAuxilioTranscricao;
		return $this;
	}

	public function setOutAuxilioLeitor(string $outAuxilioLeitor): self
	{
		$this->outAuxilioLeitor = $outAuxilioLeitor;
		return $this;
	}

	public function setOutProvaVideoLibras(string $outProvaVideoLibras): self
	{
		$this->outProvaVideoLibras = $outProvaVideoLibras;
		return $this;
	}

	public function setOutCdAudioDefVisual(string $outCdAudioDefVisual): self
	{
		$this->outCdAudioDefVisual = $outCdAudioDefVisual;
		return $this;
	}

	public function setOutProvaLinguaPortuguesa(string $outProvaLinguaPortuguesa): self
	{
		$this->outProvaLinguaPortuguesa = $outProvaLinguaPortuguesa;
		return $this;
	}
}