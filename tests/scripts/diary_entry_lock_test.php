<?php

// Executável com: php tests/scripts/diary_entry_lock_test.php
class CHttpException extends Exception
{
    public function __construct($status, $message) { parent::__construct($message, $status); }
}
class FakeCommand
{
    public function queryScalar($params)
    {
        $school = $params[':school'];
        $year = $params[':year'];
        $classroom = $params[':classroom'];
        $type = $params[':type'];
        return FakeDb::$rules[$school][$year][$classroom][$type] ?? FakeDb::$rules[$school][$year][0][$type] ?? false;
    }
}
class FakeDb
{
    public static $rules = [];
    public function createCommand($sql) { return new FakeCommand(); }
}
class Yii
{
    public static function app() { static $app; return $app ?? ($app = (object) ['db' => new FakeDb()]); }
}
class Classroom
{
    public static $rows = [];
    public static function model() { return new self(); }
    public function findByPk($id) { return self::$rows[$id] ?? null; }
}
class StudentEnrollment
{
    public static $rows = [];
    public static function model() { return new self(); }
    public function findByPk($id) { return self::$rows[$id] ?? null; }
}
class Grade
{
    public static $rows = [];
    public static function model() { return new self(); }
    public function findByPk($id) { return self::$rows[$id] ?? null; }
}
class GradeResults
{
    public static $row;
    public static function model() { return new self(); }
    public function findByAttributes($attributes) { return self::$row; }
}
require __DIR__ . '/../../app/modules/classdiary/services/DiaryEntryLock.php';

function expect($condition, $message)
{
    if (!$condition) { throw new RuntimeException($message); }
}
function expectHttp($fn, $status)
{
    try { $fn(); } catch (CHttpException $e) { expect($e->getCode() === $status, 'Código HTTP incorreto'); return; }
    throw new RuntimeException('A operação deveria ter sido rejeitada');
}
Classroom::$rows = [1 => (object) ['id' => 1, 'school_inep_fk' => '123', 'school_year' => 2026], 2 => (object) ['id' => 2, 'school_inep_fk' => '123', 'school_year' => 2026], 3 => (object) ['id' => 3, 'school_inep_fk' => '123', 'school_year' => 2027]];
expect(!DiaryEntryLock::isLocked(1, 'grade'), 'Sem regra deve liberar');
FakeDb::$rules['123'][2026][0]['grade'] = 1;
expect(DiaryEntryLock::isLocked(1, 'grade') && DiaryEntryLock::isLocked(2, 'grade'), 'A escola deve bloquear turmas existentes e novas');
expect(!DiaryEntryLock::isLocked(1, 'frequency'), 'Tipos devem ser independentes');
expect(!DiaryEntryLock::isLocked(3, 'grade'), 'Outro ano deve continuar liberado');
FakeDb::$rules['123'][2026][1]['grade'] = 0;
expect(!DiaryEntryLock::isLocked(1, 'grade') && DiaryEntryLock::isLocked(2, 'grade'), 'Exceção da turma deve prevalecer');
unset(FakeDb::$rules['123'][2026][1]['grade']);
expectHttp(function () { DiaryEntryLock::assertUnlocked(1, 'grade'); }, 403);
expectHttp(function () { DiaryEntryLock::assertUnlocked(999, 'grade'); }, 400);
StudentEnrollment::$rows[7] = (object) ['id' => 7, 'classroom_fk' => 1];
Grade::$rows[10] = (object) ['enrollment_fk' => 8];
expectHttp(function () { DiaryEntryLock::assertStudentsBelongToClassroom(1, [['enrollmentId' => 7, 'grades' => [['id' => 10]]]]); }, 400);
GradeResults::$row = (object) ['grade_1' => '7.0', 'grade_faults_1' => '2', 'given_classes_1' => '10'];
$students = [['enrollmentId' => 7, 'grades' => [['value' => '7', 'faults' => '3', 'givenClasses' => '10']]]];
DiaryEntryLock::assertReportCardChanges(1, $students, 1, false, false, false);
$students[0]['grades'][0]['value'] = '8';
expectHttp(function () use ($students) { DiaryEntryLock::assertReportCardChanges(1, $students, 1, false, false, false); }, 403);
echo "DiaryEntryLock: testes aprovados\n";
