<?php


class SchoolExtractor
{

    /**
     * @return SchoolIdentification[]
     */
    public function execute()
    {
        $schools = SchoolIdentification::model()->findAllByAttributes(
            ['situation' => 1],
            ['select' => ['inep_id', 'name']]
        );

        if (!isset($schools)) {
            throw new NotFoundActiveSchoolsException("Error Processing Request", 1);

        }
        return $schools;
    }




}





?>