<?php

/* CLI characterization of the real controller action with an in-memory model. */
class Yii
{
    public static function import($alias) {}
    public static function app() { return (object) ['charset' => 'UTF-8']; }
}
class Controller {}
interface AuthenticateSEDTokenInterface { public function authenticateSedToken(); }
class CDbCriteria
{
    public $condition = '';
    public $params = [];
    public function addCondition($condition) { $this->condition = $condition; }
    public function compare($column, $value) { $this->condition = $column . ' = :stage'; $this->params[':stage'] = $value; }
}
class CHttpException extends Exception
{
    public $statusCode;
    public function __construct($statusCode, $message = '') { $this->statusCode = $statusCode; parent::__construct($message); }
}
class EdcensoStageVsModality
{
    public static $lastQuery;
    public static function model() { return new self(); }
    public function findAll($query)
    {
        self::$lastQuery = $query;
        $rows = [
            (object) ['id' => 101, 'stage' => 1, 'edcenso_associated_stage_id' => 7, 'name' => 'Pré & Creche'],
            (object) ['id' => 102, 'stage' => 1, 'edcenso_associated_stage_id' => 8, 'name' => 'Etapa <A>'],
            (object) ['id' => 201, 'stage' => 2, 'edcenso_associated_stage_id' => 9, 'name' => 'Outra etapa'],
        ];
        if ($query === '' || ($query instanceof CDbCriteria && $query->condition === '')) return $rows;
        if (is_string($query) && preg_match('/^stage = (-?\d+)$/', $query, $matches)) $stage = (int) $matches[1];
        elseif ($query instanceof CDbCriteria && preg_match('/^stage\s*=\s*(:[A-Za-z0-9_]+)$/', $query->condition, $matches)) $stage = (int) $query->params[$matches[1]];
        else throw new RuntimeException('Condição inesperada na caracterização');
        return array_values(array_filter($rows, function ($row) use ($stage) { return $row->stage === $stage; }));
    }
}
$framework = __DIR__ . '/../../vendor/yiisoft/yii/framework';
if (!is_file($framework . '/web/helpers/CHtml.php')) {
    $framework = getenv('TAG_YII_FRAMEWORK') ?: dirname(__DIR__, 3) . '/br.tag/vendor/yiisoft/yii/framework';
}
if (!is_file($framework . '/web/helpers/CHtml.php')) throw new RuntimeException('Instale as dependências ou defina TAG_YII_FRAMEWORK');
require $framework . '/web/helpers/CHtml.php';
require __DIR__ . '/../../app/modules/student/controllers/EnrollmentController.php';

function captureStage($stage)
{
    $_POST = ['Stage' => $stage];
    ob_start();
    try { (new EnrollmentController())->actionGetModalities(); return ob_get_clean(); }
    catch (Throwable $e) { ob_end_clean(); throw $e; }
}

$actual = ['all' => captureStage('0'), 'stage1' => captureStage('1'), 'stage2' => captureStage('2'), 'emptyResult' => captureStage('99')];
$fixture = json_decode(file_get_contents(__DIR__ . '/R0Sql04Characterization.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($fixture as $case => $expected) {
    if ($actual[$case] !== $expected) throw new RuntimeException('Golden master divergiu: ' . $case);
}
echo "R0-SQL-04: caracterização de opções válidas aprovada\n";
