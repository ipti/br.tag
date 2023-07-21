<?php

class OutCertidaoNova
{
	private $outCertMatr01;
    private $outCertMatr02;
    private $outCertMatr03;
    private $outCertMatr04;
    private $outCertMatr05;
    private $outCertMatr06;
    private $outCertMatr07;
    private $outCertMatr08;
    private $outCertMatr09;
	/**
	 * Summary of __construct
	 * @param OutCertidaoNova $certidaoNova
	 */
	public function __construct($certidaoNova) {
		$this->outCertMatr01 = $certidaoNova->outCertMatr01;
		$this->outCertMatr02 = $certidaoNova->outCertMatr02;
		$this->outCertMatr03 = $certidaoNova->outCertMatr03;
		$this->outCertMatr04 = $certidaoNova->outCertMatr04;
		$this->outCertMatr05 = $certidaoNova->outCertMatr05;
		$this->outCertMatr06 = $certidaoNova->outCertMatr06;
		$this->outCertMatr07 = $certidaoNova->outCertMatr07;
		$this->outCertMatr08 = $certidaoNova->outCertMatr08;
		$this->outCertMatr09 = $certidaoNova->outCertMatr09;
	}
	

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
}