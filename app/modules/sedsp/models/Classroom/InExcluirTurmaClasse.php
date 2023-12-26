<?php

class InExcluirTurmaClasse implements JsonSerializable
{
    public $inNrClasse;

    public function __construct(?string $inNrClasse)
    {
        $this->inNrClasse = $inNrClasse;
    }

    public function getInNrClasse(): ?string
    {
        return $this->inNrClasse;
    }

    public function setInNrClasse(?string $inNrClasse): self
    {
        $this->inNrClasse = $inNrClasse;
        return $this;
    }

    public static function fromJson(array $data): self
    {
        return new self(
            $data['inNrClasse'] ?? null
        );
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}
