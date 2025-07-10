<?php

interface ISerieBuilder
{

}
class SerieBuilder
{


    public function __construct(SerieExtractor $extractor, $classId, $inepId, $referenceYear)
    {
        $this->serie = new Serie;


    }
    public function loadEnrollments(): self
    {
        return $this;
    }

    public function build(): EscolaTType
    {



        return $this->escola;

    }

}
?>