<?php


class InAluno implements JsonSerializable
{
    public $inNumRA;
	public $inDigitoRA;
	public $inSiglaUFRA;

	public function __construct(
		?string $inNumRA,
		?string $inDigitoRA,
		?string $inSiglaUFRA
	) {
		$this->inNumRA = $inNumRA;
		$this->inDigitoRA = $inDigitoRA;
		$this->inSiglaUFRA = $inSiglaUFRA;
	}

    /**
     * Get the value of inNumRA
     */
    public function getInNumRA()
    {
        return $this->inNumRA;
    }

    /**
     * Set the value of inNumRA
     */
    public function setInNumRA($inNumRA): self
    {
        $this->inNumRA = $inNumRA;

        return $this;
    }

	/**
	 * Get the value of inDigitoRA
	 */
	public function getInDigitoRA()
	{
		return $this->inDigitoRA;
	}

	/**
	 * Set the value of inDigitoRA
	 */
	public function setInDigitoRA($inDigitoRA): self
	{
		$this->inDigitoRA = $inDigitoRA;

		return $this;
	}

	/**
	 * Get the value of inSiglaUFRA
	 */
	public function getInSiglaUFRA()
	{
		return $this->inSiglaUFRA;
	}

	/**
	 * Set the value of inSiglaUFRA
	 */
	public function setInSiglaUFRA($inSiglaUFRA): self
	{
		$this->inSiglaUFRA = $inSiglaUFRA;

		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['inNumRA'] ?? null,
			$data['inDigitoRA'] ?? null,
			$data['inSiglaUFRA'] ?? null
		);
	}

	function jsonSerialize()
	{
		return get_object_vars($this);
	}
}
