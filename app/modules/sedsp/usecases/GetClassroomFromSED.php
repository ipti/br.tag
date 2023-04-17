<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');
Yii::import('application.modules.sedsp.mappers.*');

/**
 * @property ClassroomSEDDataSource $classroomSEDDataSource
 */
class GetClassroomFromSED
{
    private  $classroomSEDDataSource;

    public function __construct($classroomSEDDataSource = null)
    {
        $this->classroomSEDDataSource = $classroomSEDDataSource ?? new ClassroomSEDDataSource();
    }
    public function exec($num_classe)
    {
        $response = $this->classroomSEDDataSource->getClassroom($num_classe);
        $content = $response->getBody()->getContents();
        $classroom_tag = ClassroomMapper::parseToTAGFormacaoClasse($content);
        return $classroom_tag;
    }
}
