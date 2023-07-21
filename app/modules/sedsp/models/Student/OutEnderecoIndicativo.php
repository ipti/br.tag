<?php

class OutEnderecoIndicativo
{
    private $outLogradouro;
    private $outNumero;
    private $outBairro;
    private $outNomeCidade;
    private $outUFCidade;
    private $outLatitude;
    private $outLongitude;
    private $outCep;

	/**
	 * Summary of __construct
	 * @param OutEnderecoIndicativo $enderecoIndicativo
	 */
	public function __construct($enderecoIndicativo) {
		$this->outLogradouro = $enderecoIndicativo->outLogradouro;
		$this->outNumero = $enderecoIndicativo->outNumero;
		$this->outBairro = $enderecoIndicativo->outBairro;
		$this->outNomeCidade = $enderecoIndicativo->outNomeCidade;
		$this->outUFCidade = $enderecoIndicativo->outUFCidade;
		$this->outLatitude = $enderecoIndicativo->outLatitude;
		$this->outLongitude = $enderecoIndicativo->outLongitude;
		$this->outCep = $enderecoIndicativo->outCep;
	}
	

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
		return $this->outUFCidade;
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
}