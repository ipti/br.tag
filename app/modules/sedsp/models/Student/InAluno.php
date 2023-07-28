<?php


class InAluno
{
    public $inNumRA;

    public $inDigitoRA;

    public $inSiglaUFRA;

    /**
     * @param string $inNumRA
     * @param ?string $inDigitoRA
     * @param string $inSiglaUFRA
     */
    public function __construct(
        string $inNumRA,
        ?string $inDigitoRA,
        string $inSiglaUFRA
    ) {
        $this->inNumRA = $inNumRA;
        $this->inDigitoRA = $inDigitoRA;
        $this->inSiglaUFRA = $inSiglaUFRA;
    }

    /**
     * @param string|null $inNumRa
     * @return self
     */
    public function setInNumRa(?string $inNumRA): self
    {
        $this->inNumRA = $inNumRA;
        return $this;
    }

    function getInNumRA(): string
    {
        return $this->inNumRA;
    }

    /**
     * @param string|null $inDigitoRA
     * @return self
     */
    public function setInDigitoRa(?string $inDigitoRA): self
    {
        $this->inDigitoRA = $inDigitoRA;
        return $this;
    }

    function getInDigitoRA() : ?string
    {
        return $this->inDigitoRA;
    }

    /**
     * @param string|null $inSiglaUFRA
     * @return self
     */
    public function setInSiglaUfra(?string $inSiglaUFRA): self
    {
        $this->inSiglaUFRA = $inSiglaUFRA;
        return $this;
    }

    public function getInSiglaUFRA() : string  
    {
        return $this->inSiglaUFRA;
    }

}
