<?php


class InAluno implements JsonSerializable
{
    public $inNumRa;

    public $inDigitoRa;

    public $inSiglaUfra;

    /**
     * @param string|null $inNumRa
     * @param string|null $inDigitoRa
     * @param string|null $inSiglaUfra
     */
    public function __construct(
        ?string $inNumRa,
        ?string $inDigitoRa,
        ?string $inSiglaUfra
    ) {
        $this->inNumRa = $inNumRa;
        $this->inDigitoRa = $inDigitoRa;
        $this->inSiglaUfra = $inSiglaUfra;
    }

    /**
     * @param string|null $inNumRa
     * @return self
     */
    public function setInNumRa(?string $inNumRa): self
    {
        $this->inNumRa = $inNumRa;
        return $this;
    }

    function getInNumRA(): string
    {
        return $this->inNumRa;
    }

    /**
     * @param string|null $inDigitoRa
     * @return self
     */
    public function setInDigitoRa(?string $inDigitoRa): self
    {
        $this->inDigitoRa = $inDigitoRa;
        return $this;
    }

    function getInDigitoRA() : ?string
    {
        return $this->inDigitoRa;
    }

    /**
     * @param string|null $inSiglaUfra
     * @return self
     */
    public function setInSiglaUfra(?string $inSiglaUfra): self
    {
        $this->inSiglaUfra = $inSiglaUfra;
        return $this;
    }

    public function getInSiglaUFRA() : string  
    {
        return $this->inSiglaUfra;
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromJson(array $data): self
    {
        return new self(
            $data['outNumRA'] ?? null,
            $data['outDigitoRA'] ?? null,
            $data['outSiglaUFRA'] ?? null
        );
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
