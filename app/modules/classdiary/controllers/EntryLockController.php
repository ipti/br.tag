<?php

class EntryLockController extends Controller
{
    public function filters()
    {
        return ['accessControl'];
    }

    public function accessRules()
    {
        return [
            ['allow', 'expression' => '!Yii::app()->user->isGuest && TagUtils::isAdmin()'],
            ['deny', 'users' => ['*']],
        ];
    }

    public function actionIndex()
    {
        $session = Yii::app()->session;
        if (!isset($session['diary_lock_token'])) {
            $session['diary_lock_token'] = bin2hex(random_bytes(32));
        }

        if (Yii::app()->request->isPostRequest) {
            $token = Yii::app()->request->getPost('token', '');
            if (!is_string($token) || !hash_equals($session['diary_lock_token'], $token)) {
                throw new CHttpException(400, 'Formulário expirado. Atualize a página e tente novamente.');
            }
            if ((string) Yii::app()->request->getPost('school_year') !== (string) Yii::app()->user->year) {
                throw new CHttpException(400, 'O ano letivo foi alterado. Atualize a página antes de aplicar.');
            }
            $this->applySelection();
            Yii::app()->user->setFlash('success', 'Configuração de lançamentos atualizada.');
            $this->redirect(['index']);
        }

        $db = Yii::app()->db;
        $schools = $db->createCommand('SELECT inep_id, name FROM school_identification ORDER BY name')->queryAll();
        $classrooms = $db->createCommand('SELECT id, name, school_inep_fk FROM classroom WHERE school_year = :year ORDER BY name')
            ->queryAll(true, [':year' => Yii::app()->user->year]);
        $rules = $db->createCommand('SELECT school_inep_fk, classroom_fk, entry_type, locked FROM diary_entry_lock_year WHERE school_year = :year')
            ->queryAll(true, [':year' => Yii::app()->user->year]);
        $this->render('index', compact('schools', 'classrooms', 'rules'));
    }

    private function applySelection()
    {
        $request = Yii::app()->request;
        $types = $request->getPost('types', []);
        $schools = $request->getPost('schools', []);
        $classrooms = $request->getPost('classrooms', []);
        $action = $request->getPost('operation');
        if (!is_array($types) || !$types || array_diff($types, ['grade', 'frequency', 'lesson']) ||
            !is_array($schools) || !is_array($classrooms) || (!$schools && !$classrooms) ||
            !in_array($action, ['lock', 'unlock', 'inherit'], true)) {
            throw new CHttpException(400, 'Selecione os tipos, o destino e a ação desejada.');
        }
        if ($action === 'inherit' && $schools) {
            throw new CHttpException(400, 'A herança só pode ser aplicada a turmas.');
        }

        $db = Yii::app()->db;
        $transaction = $db->beginTransaction();
        try {
            foreach (array_unique($schools) as $school) {
                if (!is_string($school) || !$db->createCommand('SELECT COUNT(*) FROM school_identification WHERE inep_id = :id')->queryScalar([':id' => $school])) {
                    throw new CHttpException(400, 'Escola inválida.');
                }
                foreach (array_unique($types) as $type) {
                    $db->createCommand('DELETE FROM diary_entry_lock_year WHERE school_inep_fk = :school AND school_year = :year AND entry_type = :type')
                        ->execute([':school' => $school, ':year' => Yii::app()->user->year, ':type' => $type]);
                    $this->writeRule($school, 0, $type, $action === 'lock');
                }
            }
            foreach (array_unique($classrooms) as $id) {
                if (!is_scalar($id) || !ctype_digit((string) $id)) {
                    throw new CHttpException(400, 'Turma inválida.');
                }
                $classroom = Classroom::model()->findByPk((int) $id);
                if ($classroom === null || (string) $classroom->school_year !== (string) Yii::app()->user->year) {
                    throw new CHttpException(400, 'Turma inválida para o ano selecionado.');
                }
                // A seleção da escola já inclui todas as suas turmas.
                if (in_array($classroom->school_inep_fk, $schools, true)) {
                    continue;
                }
                foreach (array_unique($types) as $type) {
                    $db->createCommand('DELETE FROM diary_entry_lock_year WHERE school_inep_fk = :school AND school_year = :year AND classroom_fk = :classroom AND entry_type = :type')
                        ->execute([':school' => $classroom->school_inep_fk, ':year' => Yii::app()->user->year, ':classroom' => $classroom->id, ':type' => $type]);
                    if ($action !== 'inherit') {
                        $this->writeRule($classroom->school_inep_fk, $classroom->id, $type, $action === 'lock');
                    }
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

    private function writeRule($school, $classroom, $type, $locked)
    {
        Yii::app()->db->createCommand()->insert('diary_entry_lock_year', [
            'school_inep_fk' => $school,
            'school_year' => Yii::app()->user->year,
            'classroom_fk' => $classroom,
            'entry_type' => $type,
            'locked' => $locked ? 1 : 0,
        ]);
    }
}
