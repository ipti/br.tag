<?php



class SerieExtractor
{
    protected SerieExtractorParams $params;
    public function __construct(SerieExtractorParams $params)
    {
        $this->params = $params;
    }


    public function execute()
    {
        $strategy = $this->defineStategy();
        $raw = $strategy->execute();
    }

    private function defineStategy(): ISerieExtractorStrategy
    {
        return $this->params->multiStage ?
            $this->stategy = new SerieMultiStageStrategy($this->params) :
            $this->stategy = new SerieOneStageStrategy($this->params);
    }

}

interface ISerieExtractorStrategy
{
    public function execute();
}
class SerieExtractorParams
{
    public $classid;
    public $multiStage;

    public function __construct($multiStage, $classid)
    {

        $this->multiStage = $multiStage;
        $this->classid = $classid;
    }
}

class SerieMultiStageStrategy implements ISerieExtractorStrategy
{
    protected SerieExtractorParams $params;
    public function __construct(SerieExtractorParams $params)
    {
        $this->params = $params;

    }

    public function execute()
    {

        $criteria = new CDbCriteria();
        $criteria->alias = 'c';

        $criteria->select = [
            'esvm.edcenso_associated_stage_id AS edcensoCode',
            'c.edcenso_stage_vs_modality_fk AS edcensoCodeOriginal',
            'c.complementary_activity AS complementaryActivity',
            'c.schooling AS schooling',
            'c.aee AS aee',
        ];

        $criteria->join = '
            JOIN student_enrollment se ON se.classroom_fk = c.id
            JOIN edcenso_stage_vs_modality esvm ON esvm.id = se.edcenso_stage_vs_modality_fk';

        $criteria->condition = 'c.id = :id AND esvm.edcenso_associated_stage_id IS NOT NULL';
        $criteria->params = [':id' => $this->params->classid];

        $criteria->group = 'se.edcenso_stage_vs_modality_fk';

        return Classroom::model()->findAll($criteria);

    }

}

class SerieOneStageStrategy implements ISerieExtractorStrategy
{
    protected SerieExtractorParams $params;
    public function __construct(SerieExtractorParams $params)
    {
        $this->params = $params;
    }

    public function execute()
    {
        $criteria = new CDbCriteria();
        $criteria->alias = 'c';

        $criteria->select = [
            'esvm.edcenso_associated_stage_id AS edcensoCode',
            'c.edcenso_stage_vs_modality_fk AS edcensoCodeOriginal',
            'c.complementary_activity AS complementaryActivity',
            'c.schooling AS schooling',
            'c.aee AS aee',
        ];

        $criteria->join = 'JOIN edcenso_stage_vs_modality esvm ON esvm.id = c.edcenso_stage_vs_modality_fk';

        $criteria->condition = 'c.id = :id';
        $criteria->params = [':id' => $this->params->classid];

        $result = Classroom::model()->find($criteria);

        return $result;
    }

}
?>