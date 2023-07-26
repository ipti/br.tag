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
    public $inNumeroCns;
    public $inEmail;
    public $inNacionalidade;
    public $inNomeMunNascto;
    public $inUfMunNascto;
    public $inCodMunNasctoDne;
    public $inDataEntradaPais;
    public $inCodPaisOrigem;
    public $inPaisOrigem;

    /**
     * Summary of __construct
     *
     * @param ?string $inNomeAluno
     * @param ?string $inNomeMae
     * @param ?string $inNomePai
     * @param ?string $inNomeSocial
     * @param ?string $inNomeAfetivo
     * @param ?string $inDataNascimento
     * @param ?string $inCorRaca
     * @param ?string $inSexo
     * @param ?string $inBolsaFamilia
     * @param ?string $inQuilombola
     * @param ?string $inPossuiInternet
     * @param ?string $inPossuiNotebookSmartphoneTablet
     * @param ?string $inTipoSanguineo
     * @param ?string $inDoadorOrgaos
     * @param ?string $inNumeroCns
     * @param ?string $inEmail
     * @param ?string $inNacionalidade
     * @param ?string $inNomeMunNascto
     * @param ?string $inUfMunNascto
     * @param ?string $inCodMunNasctoDne
     * @param ?string $inDataEntradaPais
     * @param ?string $inCodPaisOrigem
     * @param ?string $inPaisOrigem
     * 
     */
     
    public function __construct($InDadosPessoais)
    {
        $this->inNomeAluno = $InDadosPessoais->inNomeAluno;
        $this->inNomeMae = $InDadosPessoais->inNomeMae;
        $this->inNomePai = $InDadosPessoais->inNomePai;
        $this->inNomeSocial = $InDadosPessoais->inNomeSocial;
        $this->inNomeAfetivo = $InDadosPessoais->inNomeAfetivo;
        $this->inDataNascimento = $InDadosPessoais->inDataNascimento;
        $this->inCorRaca = $InDadosPessoais->inCorRaca;
        $this->inSexo = $InDadosPessoais->inSexo;
        $this->inBolsaFamilia = $InDadosPessoais->inBolsaFamilia;
        $this->inQuilombola = $InDadosPessoais->inQuilombola;
        $this->inPossuiInternet = $InDadosPessoais->inPossuiInternet;
        $this->inPossuiNotebookSmartphoneTablet = $InDadosPessoais->inPossuiNotebookSmartphoneTablet;
        $this->inTipoSanguineo = $InDadosPessoais->inTipoSanguineo;
        $this->inDoadorOrgaos = $InDadosPessoais->inDoadorOrgaos;
        $this->inNumeroCns = $InDadosPessoais->inNumeroCns;
        $this->inEmail = $InDadosPessoais->inEmail;
        $this->inNacionalidade = $InDadosPessoais->inNacionalidade;
        $this->inNomeMunNascto = $InDadosPessoais->inNomeMunNascto;
        $this->inUfMunNascto = $InDadosPessoais->inUfMunNascto;
        $this->inCodMunNasctoDne = $InDadosPessoais->inCodMunNasctoDne;
        $this->inDataEntradaPais = $InDadosPessoais->inDataEntradaPais;
        $this->inCodPaisOrigem = $InDadosPessoais->inCodPaisOrigem;
        $this->inPaisOrigem = $InDadosPessoais->inPaisOrigem;
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

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}