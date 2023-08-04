<?php

class OutExibirFichaAluno 
{
    public $outDataAlteracaoFicha;
	public $outOperador;
	public $outDadosPessoais;
	/** @var OutIrmaos[]|null */
	public $outIrmaos;
	public $outDocumentos;
	public $outCodJustificativa;
	public $outJustificativaDocumento;
	public $outCertidaoNova;
	public $outCertidaoAntiga;
	public $outEnderecoResidencial;
	public $outEnderecoIndicativo;
	/** @var OutTelefones[]|null */
	public $outTelefones;
	public $outDeficiencia;
	/** @var OutListaNecessidadesEspeciais[]|null */
	public $outListaNecessidadesEspeciais;
	public $outRecursoAvaliacao;
	public $outSucesso;
	public $outErro;
	public $outProcessoID;

	/**
	 * @param OutIrmaos[]|null $outIrmaos
	 * @param OutTelefones[]|null $outTelefones
	 * @param OutListaNecessidadesEspeciais[]|null $outListaNecessidadesEspeciais
	 */
	public function __construct(
		?string $outDataAlteracaoFicha,
		?string $outOperador,
		?OutDadosPessoais $outDadosPessoais,
		?array $outIrmaos,
		?OutDocumentos $outDocumentos,
		?string $outCodJustificativa,
		?string $outJustificativaDocumento,
		?OutCertidaoNova $outCertidaoNova,
		?OutCertidaoAntiga $outCertidaoAntiga,
		?OutEnderecoResidencial $outEnderecoResidencial,
		?OutEnderecoIndicativo $outEnderecoIndicativo,
		?array $outTelefones,
		?OutDeficiencia $outDeficiencia,
		?array $outListaNecessidadesEspeciais,
		?OutRecursoAvaliacao $outRecursoAvaliacao,
		?string $outSucesso,
		?string $outErro,
		?string $outProcessoID
	) {
		$this->outDataAlteracaoFicha = $outDataAlteracaoFicha;
		$this->outOperador = $outOperador;
		$this->outDadosPessoais = $outDadosPessoais;
		$this->outIrmaos = $outIrmaos;
		$this->outDocumentos = $outDocumentos;
		$this->outCodJustificativa = $outCodJustificativa;
		$this->outJustificativaDocumento = $outJustificativaDocumento;
		$this->outCertidaoNova = $outCertidaoNova;
		$this->outCertidaoAntiga = $outCertidaoAntiga;
		$this->outEnderecoResidencial = $outEnderecoResidencial;
		$this->outEnderecoIndicativo = $outEnderecoIndicativo;
		$this->outTelefones = $outTelefones;
		$this->outDeficiencia = $outDeficiencia;
		$this->outListaNecessidadesEspeciais = $outListaNecessidadesEspeciais;
		$this->outRecursoAvaliacao = $outRecursoAvaliacao;
		$this->outSucesso = $outSucesso;
		$this->outErro = $outErro;
		$this->outProcessoID = $outProcessoID;
	}

	public function getOutDataAlteracaoFicha(): ?string
	{
		return $this->outDataAlteracaoFicha;
	}

	public function getOutOperador(): ?string
	{
		return $this->outOperador;
	}

	public function getOutDadosPessoais(): ?OutDadosPessoais
	{
		return $this->outDadosPessoais;
	}

	/**
	 * @return OutIrmaos[]|null
	 */
	public function getOutIrmaos(): ?array
	{
		return $this->outIrmaos;
	}

	public function getOutDocumentos(): ?OutDocumentos
	{
		return $this->outDocumentos;
	}

	public function getOutCodJustificativa(): ?string
	{
		return $this->outCodJustificativa;
	}

	public function getOutJustificativaDocumento(): ?string
	{
		return $this->outJustificativaDocumento;
	}

	public function getOutCertidaoNova(): ?OutCertidaoNova
	{
		return $this->outCertidaoNova;
	}

	public function getOutCertidaoAntiga(): ?OutCertidaoAntiga
	{
		return $this->outCertidaoAntiga;
	}

	public function getOutEnderecoResidencial(): ?OutEnderecoResidencial
	{
		return $this->outEnderecoResidencial;
	}

	public function getOutEnderecoIndicativo(): ?OutEnderecoIndicativo
	{
		return $this->outEnderecoIndicativo;
	}

	/**
	 * @return OutTelefones[]|null
	 */
	public function getOutTelefones(): ?array
	{
		return $this->outTelefones;
	}

	public function getOutDeficiencia(): ?OutDeficiencia
	{
		return $this->outDeficiencia;
	}

	/**
	 * @return OutListaNecessidadesEspeciais[]|null
	 */
	public function getOutListaNecessidadesEspeciais(): ?array
	{
		return $this->outListaNecessidadesEspeciais;
	}

	public function getOutRecursoAvaliacao(): ?OutRecursoAvaliacao
	{
		return $this->outRecursoAvaliacao;
	}

	public function getOutSucesso(): ?string
	{
		return $this->outSucesso;
	}

	public function getOutErro(): ?string
	{
		return $this->outErro;
	}

	public function getOutProcessoId(): ?string
	{
		return $this->outProcessoID;
	}

	public function setOutDataAlteracaoFicha(?string $outDataAlteracaoFicha): self
	{
		$this->outDataAlteracaoFicha = $outDataAlteracaoFicha;
		return $this;
	}

	public function setOutOperador(?string $outOperador): self
	{
		$this->outOperador = $outOperador;
		return $this;
	}

	public function setOutDadosPessoais(?OutDadosPessoais $outDadosPessoais): self
	{
		$this->outDadosPessoais = $outDadosPessoais;
		return $this;
	}

	/**
	 * @param OutIrmaos[]|null $outIrmaos
	 */
	public function setOutIrmaos(?array $outIrmaos): self
	{
		$this->outIrmaos = $outIrmaos;
		return $this;
	}

	public function setOutDocumentos(?OutDocumentos $outDocumentos): self
	{
		$this->outDocumentos = $outDocumentos;
		return $this;
	}

	public function setOutCodJustificativa(?string $outCodJustificativa): self
	{
		$this->outCodJustificativa = $outCodJustificativa;
		return $this;
	}

	public function setOutJustificativaDocumento(?string $outJustificativaDocumento): self
	{
		$this->outJustificativaDocumento = $outJustificativaDocumento;
		return $this;
	}

	public function setOutCertidaoNova(?OutCertidaoNova $outCertidaoNova): self
	{
		$this->outCertidaoNova = $outCertidaoNova;
		return $this;
	}

	public function setOutCertidaoAntiga(?OutCertidaoAntiga $outCertidaoAntiga): self
	{
		$this->outCertidaoAntiga = $outCertidaoAntiga;
		return $this;
	}

	public function setOutEnderecoResidencial(?OutEnderecoResidencial $outEnderecoResidencial): self
	{
		$this->outEnderecoResidencial = $outEnderecoResidencial;
		return $this;
	}

	public function setOutEnderecoIndicativo(?OutEnderecoIndicativo $outEnderecoIndicativo): self
	{
		$this->outEnderecoIndicativo = $outEnderecoIndicativo;
		return $this;
	}

	/**
	 * @param OutTelefones[]|null $outTelefones
	 */
	public function setOutTelefones(?array $outTelefones): self
	{
		$this->outTelefones = $outTelefones;
		return $this;
	}

	public function setOutDeficiencia(?OutDeficiencia $outDeficiencia): self
	{
		$this->outDeficiencia = $outDeficiencia;
		return $this;
	}

	/**
	 * @param OutListaNecessidadesEspeciais[]|null $outListaNecessidadesEspeciais
	 */
	public function setOutListaNecessidadesEspeciais(?array $outListaNecessidadesEspeciais): self
	{
		$this->outListaNecessidadesEspeciais = $outListaNecessidadesEspeciais;
		return $this;
	}

	public function setOutRecursoAvaliacao(?OutRecursoAvaliacao $outRecursoAvaliacao): self
	{
		$this->outRecursoAvaliacao = $outRecursoAvaliacao;
		return $this;
	}

	public function setOutSucesso(?string $outSucesso): self
	{
		$this->outSucesso = $outSucesso;
		return $this;
	}

	public function setOutErro(?string $outErro): self
	{
		$this->outErro = $outErro;
		return $this;
	}

	public function setOutProcessoId(?string $outProcessoID): self
	{
		$this->outProcessoID = $outProcessoID;
		return $this;
	}

    /**
     * Summary of fromJson
     * @param array $data
     * @return OutExibirFichaAluno
     */
    public static function fromJson(array $data): self
    {
        return new self(
            $data['outDataAlteracaoFicha'] ?? null,
            $data['outOperador'] ?? null,
            $data['outDadosPessoais'] ?? null,
            isset($data['outIrmaos']) ? array_map(static function($item) {
                return OutAlunos::fromJson($item);
            }, $data['outIrmaos']) : null,
            $data['outDocumentos'] ?? null,
            $data['outCodJustificativa'] ?? null,
            $data['outJustificativaDocumento'] ?? null,
            $data['outCertidaoNova'] ?? null,
            $data['outCertidaoAntiga'] ?? null,
            $data['outEnderecoResidencial'] ?? null,
            $data['outEnderecoIndicativo'] ?? null,
            isset($data['outTelefones']) ? array_map(static function($item) {
                return OutAlunos::fromJson($item);
            }, $data['outTelefones']) : null,
            $data['outDeficiencia'] ?? null,
            isset($data['outListaNecessidadesEspeciais']) ? array_map(static function($item) {
                return OutAlunos::fromJson($item);
            }, $data['outListaNecessidadesEspeciais']) : null,
            $data['outRecursoAvaliacao'] ?? null,
            $data['outSucesso'] ?? null,
            $data['outErro'] ?? null,
            $data['outProcessoID'] ?? null
        );
    }
}
