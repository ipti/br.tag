<?php

class MaceteLessonRecordService
{
    private MaceteAbilityService $abilityService;

    public function __construct(?MaceteAbilityService $abilityService = null)
    {
        $this->abilityService = $abilityService ?? new MaceteAbilityService();
    }

    public function save(MaceteLessonRecord $lessonRecord, array $request): MaceteLessonRecord
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $recordData = $this->normalizeRecordData($request['MaceteLessonRecord'] ?? []);
            $lessonRecord->attributes = $recordData;

            if ($lessonRecord->isNewRecord) {
                $lessonRecord->school_inep_fk = Yii::app()->user->school;
                $lessonRecord->users_fk = Yii::app()->user->loginInfos->id;
            }

            $plan = MaceteLessonPlan::model()->findByPk($lessonRecord->lesson_plan_fk);
            if ($plan !== null) {
                $lessonRecord->edcenso_stage_vs_modality_fk = $plan->edcenso_stage_vs_modality_fk;
                $lessonRecord->edcenso_discipline_fk = $plan->edcenso_discipline_fk;
            }

            $classroom = Classroom::model()->findByPk($lessonRecord->classroom_fk);
            if ($classroom !== null && $classroom->edcenso_stage_vs_modality_fk !== null) {
                $lessonRecord->edcenso_stage_vs_modality_fk = $classroom->edcenso_stage_vs_modality_fk;
            }

            if ($lessonRecord->status === null || $lessonRecord->status === '') {
                $lessonRecord->status = MaceteLessonRecord::STATUS_DRAFT;
            }

            if (!$lessonRecord->save()) {
                throw new CException('Não foi possível salvar o registro de aula MACETE.');
            }

            $abilityIds = $request['abilities'] ?? [];
            if (empty($abilityIds) && $plan !== null) {
                foreach ($plan->abilities as $ability) {
                    $abilityIds[] = $ability->ability_fk;
                }
            }

            $this->syncAbilities($lessonRecord, $abilityIds);

            if ($lessonRecord->status === MaceteLessonRecord::STATUS_DONE && $plan !== null) {
                $plan->status = MaceteLessonPlan::STATUS_REGISTERED;
                $plan->save(false, ['status', 'updated_at']);
            }

            $transaction->commit();

            return $lessonRecord;
        } catch (Exception $exception) {
            if ($transaction->active) {
                $transaction->rollBack();
            }

            throw $exception;
        }
    }

    public function getPlans(): array
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'school_inep_fk = :school AND school_year = :school_year';
        $criteria->params = [
            ':school' => Yii::app()->user->school,
            ':school_year' => Yii::app()->user->year,
        ];

        if (TagUtils::isInstructor()) {
            $criteria->addCondition('users_fk = :user_id');
            $criteria->params[':user_id'] = Yii::app()->user->loginInfos->id;
        }

        $criteria->order = 'name ASC';

        return MaceteLessonPlan::model()->findAll($criteria);
    }

    public function getAbilityIds(MaceteLessonRecord $lessonRecord): array
    {
        $ids = [];
        foreach ($lessonRecord->abilities as $ability) {
            $ids[] = (int) $ability->ability_fk;
        }

        return $ids;
    }

    public static function convertDateToDatabase(?string $date): ?string
    {
        if ($date === null || trim($date) === '') {
            return null;
        }

        $dateObject = DateTime::createFromFormat('d/m/Y', $date);
        if (!$dateObject) {
            return $date;
        }

        return $dateObject->format('Y-m-d');
    }

    public static function convertDateToView(?string $date): ?string
    {
        if ($date === null || trim($date) === '') {
            return null;
        }

        $dateObject = DateTime::createFromFormat('Y-m-d', substr($date, 0, 10));
        if (!$dateObject) {
            return $date;
        }

        return $dateObject->format('d/m/Y');
    }

    private function normalizeRecordData(array $recordData): array
    {
        if (array_key_exists('lesson_date', $recordData)) {
            $recordData['lesson_date'] = self::convertDateToDatabase($recordData['lesson_date']);
        }

        foreach (['edcenso_discipline_fk'] as $nullableField) {
            if (array_key_exists($nullableField, $recordData) && $recordData[$nullableField] === '') {
                $recordData[$nullableField] = null;
            }
        }

        return $recordData;
    }

    private function syncAbilities(MaceteLessonRecord $lessonRecord, array $abilityIds): void
    {
        $abilityIds = $this->abilityService->normalizeIds($abilityIds);
        MaceteLessonRecordAbility::model()->deleteAll(
            'lesson_record_fk = :lesson_record_fk',
            [':lesson_record_fk' => $lessonRecord->id]
        );

        foreach ($abilityIds as $abilityId) {
            $ability = new MaceteLessonRecordAbility();
            $ability->lesson_record_fk = $lessonRecord->id;
            $ability->ability_fk = $abilityId;

            if (!$ability->save()) {
                throw new CException('Não foi possível salvar uma habilidade do registro MACETE.');
            }
        }
    }
}
