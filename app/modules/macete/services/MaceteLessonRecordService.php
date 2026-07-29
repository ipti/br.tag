<?php

class MaceteLessonRecordService
{
    private MaceteAbilityService $abilityService;

    private MaceteAccessService $accessService;

    public function __construct(?MaceteAbilityService $abilityService = null, ?MaceteAccessService $accessService = null)
    {
        $this->abilityService = $abilityService ?? new MaceteAbilityService();
        $this->accessService = $accessService ?? new MaceteAccessService();
    }

    public function save(MaceteLessonRecord $lessonRecord, array $request): MaceteLessonRecord
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $recordData = $this->normalizeRecordData($request['MaceteLessonRecord'] ?? []);
            $lessonRecord->attributes = $this->editableRecordData($recordData);

            if ($lessonRecord->isNewRecord) {
                $lessonRecord->school_inep_fk = Yii::app()->user->school;
                $lessonRecord->users_fk = $this->accessService->currentUserId();
            }

            $plan = $this->accessService->findPlan((int) $lessonRecord->lesson_plan_fk);
            if ($plan === null) {
                throw new CException('Plano MACETE não encontrado ou não disponível para o usuário atual.');
            }

            $classroom = $this->accessService->findClassroom((int) $lessonRecord->classroom_fk);
            if ($classroom === null) {
                throw new CException('Turma não encontrada ou não disponível para o usuário atual.');
            }

            $planStage = MaceteLessonPlanStage::model()->findByAttributes([
                'lesson_plan_fk' => $plan->id,
                'edcenso_stage_vs_modality_fk' => $classroom->edcenso_stage_vs_modality_fk,
            ]);
            if ($planStage === null) {
                throw new CException('A turma selecionada não pertence a uma etapa prevista no plano MACETE.');
            }

            $lessonRecord->edcenso_stage_vs_modality_fk = $classroom->edcenso_stage_vs_modality_fk;
            $lessonRecord->edcenso_discipline_fk = $planStage->edcenso_discipline_fk;

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
        $this->accessService->applyPlanScope($criteria);

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
            if (!self::isValidViewDate((string) $recordData['lesson_date'])) {
                throw new CException('Informe uma data de aula válida no formato DD/MM/AAAA.');
            }

            $recordData['lesson_date'] = self::convertDateToDatabase($recordData['lesson_date']);
        }

        foreach (['edcenso_discipline_fk'] as $nullableField) {
            if (array_key_exists($nullableField, $recordData) && $recordData[$nullableField] === '') {
                $recordData[$nullableField] = null;
            }
        }

        return $recordData;
    }

    private static function isValidViewDate(string $date): bool
    {
        $dateObject = DateTime::createFromFormat('!d/m/Y', $date);
        $errors = DateTime::getLastErrors();

        return $dateObject !== false
            && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0));
    }

    private function editableRecordData(array $recordData): array
    {
        $allowedAttributes = [
            'lesson_plan_fk',
            'classroom_fk',
            'lesson_date',
            'executed_content',
            'methodology_notes',
            'evaluation_notes',
            'adaptation_notes',
            'status',
        ];

        $recordData = array_intersect_key($recordData, array_flip($allowedAttributes));
        foreach (['executed_content', 'methodology_notes', 'evaluation_notes', 'adaptation_notes'] as $attribute) {
            if (array_key_exists($attribute, $recordData)) {
                $recordData[$attribute] = MaceteRichTextSanitizer::sanitize((string) $recordData[$attribute]);
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
