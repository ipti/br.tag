<?php

class InManutencaoTurmaClasse implements JsonSerializable
{
    public $inAnoLetivo;
    public $inNumClasse;
    public $inCodTipoClasse;
    public $inCodTurno;
    public $inTurma;
    public $inNrCapacidadeFisicaMaxima;
    public $inDataInicioAula;
    public $inDataFimAula;
    public $inHorarioInicioAula;
    public $inHorarioFimAula;
    public $inCodDuracao;
    public $inCodHabilitacao;
    /** @var string[]|null */
    public $inCodigoAtividadeComplementar;
    public $inNumeroSala;

    /**
     * @param string[] $inCodigoAtividadeComplementar
     */
    public function __construct(
        ?string $inAnoLetivo,
        ?string $inNumClasse,
        ?string $inCodTipoClasse,
        ?string $inCodTurno,
        ?string $inTurma,
        ?string $inNrCapacidadeFisicaMaxima,
        ?string $inDataInicioAula,
        ?string $inDataFimAula,
        ?string $inHorarioInicioAula,
        ?string $inHorarioFimAula,
        ?string $inCodDuracao,
        ?string $inCodHabilitacao,
        ?array $inCodigoAtividadeComplementar,
        ?string $inNumeroSala,
        ?InDiasDaSemana $inDiasDaSemana
    ) {
        $this->inAnoLetivo = $inAnoLetivo;
        $this->inNumClasse = $inNumClasse;
        $this->inCodTipoClasse = $inCodTipoClasse;
        $this->inCodTurno = $inCodTurno;
        $this->inTurma = $inTurma;
        $this->inNrCapacidadeFisicaMaxima = $inNrCapacidadeFisicaMaxima;
        $this->inDataInicioAula = $inDataInicioAula;
        $this->inDataFimAula = $inDataFimAula;
        $this->inHorarioInicioAula = $inHorarioInicioAula;
        $this->inHorarioFimAula = $inHorarioFimAula;
        $this->inCodDuracao = $inCodDuracao;
        $this->inCodHabilitacao = $inCodHabilitacao;
        $this->inCodigoAtividadeComplementar = $inCodigoAtividadeComplementar;
        $this->inNumeroSala = $inNumeroSala;
        $this->inDiasDaSemana = $inDiasDaSemana;
    }

    public function getInAnoLetivo(): ?string
    {
        return $this->inAnoLetivo;
    }

    public function getInNumClasse(): ?string
    {
        return $this->inNumClasse;
    }

    public function getInCodTipoClasse(): ?string
    {
        return $this->inCodTipoClasse;
    }

    public function getInCodTurno(): ?string
    {
        return $this->inCodTurno;
    }

    public function getInTurma(): ?string
    {
        return $this->inTurma;
    }

    public function getInNrCapacidadeFisicaMaxima(): ?string
    {
        return $this->inNrCapacidadeFisicaMaxima;
    }

    public function getInDataInicioAula(): ?string
    {
        return $this->inDataInicioAula;
    }

    public function getInDataFimAula(): ?string
    {
        return $this->inDataFimAula;
    }

    public function getInHorarioInicioAula(): ?string
    {
        return $this->inHorarioInicioAula;
    }

    public function getInHorarioFimAula(): ?string
    {
        return $this->inHorarioFimAula;
    }

    public function getInCodDuracao(): ?string
    {
        return $this->inCodDuracao;
    }

    public function getInCodHabilitacao(): ?string
    {
        return $this->inCodHabilitacao;
    }

    /**
     * @return string[]|null
     */
    public function getInCodigoAtividadeComplementar(): ?array
    {
        return $this->inCodigoAtividadeComplementar;
    }

    public function getInDiasDaSemana(): ?InDiasDaSemana
    {
        return $this->inDiasDaSemana;
    }

    public function getInNumeroSala(): ?string
    {
        return $this->inNumeroSala;
    }

    public function setInAnoLetivo(?string $inAnoLetivo): self
    {
        $this->inAnoLetivo = $inAnoLetivo;
        return $this;
    }

    public function setInNumClasse(?string $inNumClasse): self
    {
        $this->inNumClasse = $inNumClasse;
        return $this;
    }

    public function setInCodTipoClasse(?string $inCodTipoClasse): self
    {
        $this->inCodTipoClasse = $inCodTipoClasse;
        return $this;
    }

    public function setInCodTurno(?string $inCodTurno): self
    {
        $this->inCodTurno = $inCodTurno;
        return $this;
    }

    public function setInTurma(?string $inTurma): self
    {
        $this->inTurma = $inTurma;
        return $this;
    }

    public function setInNrCapacidadeFisicaMaxima(?string $inNrCapacidadeFisicaMaxima): self
    {
        $this->inNrCapacidadeFisicaMaxima = $inNrCapacidadeFisicaMaxima;
        return $this;
    }

    public function setInDataInicioAula(?string $inDataInicioAula): self
    {
        $this->inDataInicioAula = $inDataInicioAula;
        return $this;
    }

    public function setInDataFimAula(?string $inDataFimAula): self
    {
        $this->inDataFimAula = $inDataFimAula;
        return $this;
    }

    public function setInHorarioInicioAula(?string $inHorarioInicioAula): self
    {
        $this->inHorarioInicioAula = $inHorarioInicioAula;
        return $this;
    }

    public function setInHorarioFimAula(?string $inHorarioFimAula): self
    {
        $this->inHorarioFimAula = $inHorarioFimAula;
        return $this;
    }

    public function setInCodDuracao(?string $inCodDuracao): self
    {
        $this->inCodDuracao = $inCodDuracao;
        return $this;
    }

    public function setInCodHabilitacao(?string $inCodHabilitacao): self
    {
        $this->inCodHabilitacao = $inCodHabilitacao;
        return $this;
    }

    /**
     * @param string[]|null $inCodigoAtividadeComplementar
     */
    public function setInCodigoAtividadeComplementar(?array $inCodigoAtividadeComplementar): self
    {
        $this->inCodigoAtividadeComplementar = $inCodigoAtividadeComplementar;
        return $this;
    }

    public function setInDiasDaSemana(?InDiasDaSemana $inDiasDaSemana): self
    {
        $this->inDiasDaSemana = $inDiasDaSemana;
        return $this;
    }

    public function setInNumeroSala(?string $inNumeroSala): self
    {
        $this->inNumeroSala = $inNumeroSala;
        return $this;
    }

    public static function fromJson(array $data): self
    {
        return new self(
            $data['inAnoLetivo'] ?? null,
            $data['inNumClasse'] ?? null,
            $data['inCodTipoClasse'] ?? null,
            $data['inCodTurno'] ?? null,
            $data['inTurma'] ?? null,
            $data['inNrCapacidadeFisicaMaxima'] ?? null,
            $data['inDataInicioAula'] ?? null,
            $data['inDataFimAula'] ?? null,
            $data['inHorarioInicioAula'] ?? null,
            $data['inHorarioFimAula'] ?? null,
            $data['inCodDuracao'] ?? null,
            $data['inCodHabilitacao'] ?? null,
            $data['inCodigoAtividadeComplementar'] ?? null,
            $data['inNumeroSala'] ?? null,
            ($data['inDiasDaSemana'] ?? null) !== null ? InDiasDaSemana::fromJson($data['inDiasDaSemana']) : null
        );
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}
