<?php


class InDadosPessoais implements JsonSerializable
{
    private $inNomeAluno;

    private $inNomeMae;

    private $inNomePai;

    private $inNomeSocial;

    private $inNomeAfetivo;

    private $inDataNascimento;

    private $inCorRaca;

    private $inSexo;

    private $inBolsaFamilia;

    private $inQuilombola;

    private $inPossuiInternet;

    private $inPossuiNotebookSmartphoneTablet;

    private $inTipoSanguineo;

    private $inDoadorOrgaos;

    private $inNumeroCns;

    private $inEmail;

    private $inNacionalidade;

    private $inNomeMunNascto;

    private $inUfMunNascto;

    private $inCodMunNasctoDne;

    private $inDataEntradaPais;

    private $inCodPaisOrigem;

    private $inPaisOrigem;

    /**
     * @param string|null $inNomeAluno
     * @param string|null $inNomeMae
     * @param string|null $inNomePai
     * @param string|null $inNomeSocial
     * @param string|null $inNomeAfetivo
     * @param string|null $inDataNascimento
     * @param string|null $inCorRaca
     * @param string|null $inSexo
     * @param string|null $inBolsaFamilia
     * @param string|null $inQuilombola
     * @param string|null $inPossuiInternet
     * @param string|null $inPossuiNotebookSmartphoneTablet
     * @param string|null $inTipoSanguineo
     * @param string|null $inDoadorOrgaos
     * @param string|null $inNumeroCns
     * @param string|null $inEmail
     * @param string|null $inNacionalidade
     * @param string|null $inNomeMunNascto
     * @param string|null $inUfMunNascto
     * @param string|null $inCodMunNasctoDne
     * @param string|null $inDataEntradaPais
     * @param string|null $inCodPaisOrigem
     * @param string|null $inPaisOrigem
     */
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
        ?string $inNumeroCns = null,
        ?string $inEmail = null,
        ?string $inNacionalidade = null,
        ?string $inNomeMunNascto = null,
        ?string $inUfMunNascto = null,
        ?string $inCodMunNasctoDne = null,
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
        $this->inNumeroCns = $inNumeroCns;
        $this->inEmail = $inEmail;
        $this->inNacionalidade = $inNacionalidade;
        $this->inNomeMunNascto = $inNomeMunNascto;
        $this->inUfMunNascto = $inUfMunNascto;
        $this->inCodMunNasctoDne = $inCodMunNasctoDne;
        $this->inDataEntradaPais = $inDataEntradaPais;
        $this->inCodPaisOrigem = $inCodPaisOrigem;
        $this->inPaisOrigem = $inPaisOrigem;
    }

    /**
     * @param string|null $inNomeAluno
     * @return self
     */
    public function setInNomeAluno(?string $inNomeAluno): self
    {
        $this->inNomeAluno = $inNomeAluno;
        return $this;
    }

    /**
     * @param string|null $inNomeMae
     * @return self
     */
    public function setInNomeMae(?string $inNomeMae): self
    {
        $this->inNomeMae = $inNomeMae;
        return $this;
    }

    /**
     * @param string|null $inNomePai
     * @return self
     */
    public function setInNomePai(?string $inNomePai): self
    {
        $this->inNomePai = $inNomePai;
        return $this;
    }

    /**
     * @param string|null $inNomeSocial
     * @return self
     */
    public function setInNomeSocial(?string $inNomeSocial): self
    {
        $this->inNomeSocial = $inNomeSocial;
        return $this;
    }

    /**
     * @param string|null $inNomeAfetivo
     * @return self
     */
    public function setInNomeAfetivo(?string $inNomeAfetivo): self
    {
        $this->inNomeAfetivo = $inNomeAfetivo;
        return $this;
    }

    /**
     * @param string|null $inDataNascimento
     * @return self
     */
    public function setInDataNascimento(?string $inDataNascimento): self
    {
        $this->inDataNascimento = $inDataNascimento;
        return $this;
    }

    /**
     * @param string|null $inCorRaca
     * @return self
     */
    public function setInCorRaca(?string $inCorRaca): self
    {
        $this->inCorRaca = $inCorRaca;
        return $this;
    }

    /**
     * @param string|null $inSexo
     * @return self
     */
    public function setInSexo(?string $inSexo): self
    {
        $this->inSexo = $inSexo;
        return $this;
    }

    /**
     * @param string|null $inBolsaFamilia
     * @return self
     */
    public function setInBolsaFamilia(?string $inBolsaFamilia): self
    {
        $this->inBolsaFamilia = $inBolsaFamilia;
        return $this;
    }

    /**
     * @param string|null $inQuilombola
     * @return self
     */
    public function setInQuilombola(?string $inQuilombola): self
    {
        $this->inQuilombola = $inQuilombola;
        return $this;
    }

    /**
     * @param string|null $inPossuiInternet
     * @return self
     */
    public function setInPossuiInternet(?string $inPossuiInternet): self
    {
        $this->inPossuiInternet = $inPossuiInternet;
        return $this;
    }

    /**
     * @param string|null $inPossuiNotebookSmartphoneTablet
     * @return self
     */
    public function setInPossuiNotebookSmartphoneTablet(?string $inPossuiNotebookSmartphoneTablet): self
    {
        $this->inPossuiNotebookSmartphoneTablet = $inPossuiNotebookSmartphoneTablet;
        return $this;
    }

    /**
     * @param string|null $inTipoSanguineo
     * @return self
     */
    public function setInTipoSanguineo(?string $inTipoSanguineo): self
    {
        $this->inTipoSanguineo = $inTipoSanguineo;
        return $this;
    }

    /**
     * @param string|null $inDoadorOrgaos
     * @return self
     */
    public function setInDoadorOrgaos(?string $inDoadorOrgaos): self
    {
        $this->inDoadorOrgaos = $inDoadorOrgaos;
        return $this;
    }

    /**
     * @param string|null $inNumeroCns
     * @return self
     */
    public function setInNumeroCns(?string $inNumeroCns): self
    {
        $this->inNumeroCns = $inNumeroCns;
        return $this;
    }

    /**
     * @param string|null $inEmail
     * @return self
     */
    public function setInEmail(?string $inEmail): self
    {
        $this->inEmail = $inEmail;
        return $this;
    }

    /**
     * @param string|null $inNacionalidade
     * @return self
     */
    public function setInNacionalidade(?string $inNacionalidade): self
    {
        $this->inNacionalidade = $inNacionalidade;
        return $this;
    }

    /**
     * @param string|null $inNomeMunNascto
     * @return self
     */
    public function setInNomeMunNascto(?string $inNomeMunNascto): self
    {
        $this->inNomeMunNascto = $inNomeMunNascto;
        return $this;
    }

    /**
     * @param string|null $inUfMunNascto
     * @return self
     */
    public function setInUfMunNascto(?string $inUfMunNascto): self
    {
        $this->inUfMunNascto = $inUfMunNascto;
        return $this;
    }

    /**
     * @param string|null $inCodMunNasctoDne
     * @return self
     */
    public function setInCodMunNasctoDne(?string $inCodMunNasctoDne): self
    {
        $this->inCodMunNasctoDne = $inCodMunNasctoDne;
        return $this;
    }

    /**
     * @param string|null $inDataEntradaPais
     * @return self
     */
    public function setInDataEntradaPais(?string $inDataEntradaPais): self
    {
        $this->inDataEntradaPais = $inDataEntradaPais;
        return $this;
    }

    /**
     * @param string|null $inCodPaisOrigem
     * @return self
     */
    public function setInCodPaisOrigem(?string $inCodPaisOrigem): self
    {
        $this->inCodPaisOrigem = $inCodPaisOrigem;
        return $this;
    }

    /**
     * @param string|null $inPaisOrigem
     * @return self
     */
    public function setInPaisOrigem(?string $inPaisOrigem): self
    {
        $this->inPaisOrigem = $inPaisOrigem;
        return $this;
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromJson(array $data): self
    {
        return new self(
            $data['outNomeAluno'] ?? null,
            $data['outNomeMae'] ?? null,
            $data['outNomePai'] ?? null,
            $data['outNomeSocial'] ?? null,
            $data['outNomeAfetivo'] ?? null,
            $data['outDataNascimento'] ?? null,
            $data['outCorRaca'] ?? null,
            $data['outSexo'] ?? null,
            $data['outBolsaFamilia'] ?? null,
            $data['outQuilombola'] ?? null,
            $data['outPossuiInternet'] ?? null,
            $data['outPossuiNotebookSmartphoneTablet'] ?? null,
            $data['outTipoSanguineo'] ?? null,
            $data['outDoadorOrgaos'] ?? null,
            $data['outNumeroCNS'] ?? null,
            $data['outEmail'] ?? null,
            $data['outNacionalidade'] ?? null,
            $data['outNomeMunNascto'] ?? null,
            $data['outUFMunNascto'] ?? null,
            $data['outCodMunNasctoDNE'] ?? null,
            $data['outDataEntradaPais'] ?? null,
            $data['outCodPaisOrigem'] ?? null,
            $data['outPaisOrigem'] ?? null
        );
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
