<?php

class SchoolExtractorParams extends IExtractorParams
{
    public function __construct($inepId)
    {
        $this->inepId = $inepId;
    }
}

class SchoolExtractor
{

    /**
     * @return SchoolIdentification[]
     */
    public function execute()
    {
        $schools = SchoolIdentification::model()->select("inep_id, name")->findAllByAttributes(['situation' => '1']);

        if (!isset($schools)) {
            throw new NotFoundActiveSchoolsException("Error Processing Request", 1);

        }
        return $schools;
    }




}





?>