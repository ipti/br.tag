<?php

class MaceteLessonPlanService
{
    private MaceteAbilityService $abilityService;

    public function __construct(?MaceteAbilityService $abilityService = null)
    {
        $this->abilityService = $abilityService ?? new MaceteAbilityService();
    }

    public function save(MaceteLessonPlan $lessonPlan, array $request): MaceteLessonPlan
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $planData = $request['MaceteLessonPlan'] ?? [];
            $stageIds = $this->normalizeStageIds($request['stage_ids'] ?? ($planData['edcenso_stage_vs_modality_fk'] ?? []));
            if (empty($stageIds)) {
                throw new CException('Selecione pelo menos uma etapa.');
            }

            $lessonPlan->attributes = $this->normalizePlanData($planData);
            $lessonPlan->edcenso_stage_vs_modality_fk = $stageIds[0];

            if ($lessonPlan->isNewRecord) {
                $lessonPlan->school_inep_fk = Yii::app()->user->school;
                $lessonPlan->users_fk = Yii::app()->user->loginInfos->id;
                $lessonPlan->school_year = Yii::app()->user->year;
            }

            if ($lessonPlan->status === null || $lessonPlan->status === '') {
                $lessonPlan->status = MaceteLessonPlan::STATUS_DRAFT;
            }

            if (!$lessonPlan->save()) {
                throw new CException('Não foi possível salvar o plano MACETE.');
            }

            $this->syncStages($lessonPlan, $stageIds);
            $this->syncAbilities($lessonPlan, $request['abilities'] ?? []);
            $this->syncSections($lessonPlan, $request['sections'] ?? []);
            $this->syncResources($lessonPlan, $request['resources'] ?? []);
            $this->syncMaterials($lessonPlan, $request['materials'] ?? []);

            $transaction->commit();

            return $lessonPlan;
        } catch (Exception $exception) {
            if ($transaction->active) {
                $transaction->rollBack();
            }

            throw $exception;
        }
    }

    public function getStages(): array
    {
        if (TagUtils::isInstructor()) {
            return Yii::app()->db->createCommand(
                'SELECT DISTINCT esvm.id, esvm.name
                FROM edcenso_stage_vs_modality esvm
                JOIN curricular_matrix cm ON cm.stage_fk = esvm.id
                JOIN teaching_matrixes tm ON tm.curricular_matrix_fk = cm.id
                JOIN instructor_teaching_data itd ON itd.id = tm.teaching_data_fk
                JOIN instructor_identification ii ON ii.id = itd.instructor_fk
                WHERE ii.users_fk = :user_id
                    AND cm.school_year = :school_year
                ORDER BY esvm.name'
            )
                ->bindValue(':user_id', Yii::app()->user->loginInfos->id, PDO::PARAM_INT)
                ->bindValue(':school_year', Yii::app()->user->year, PDO::PARAM_INT)
                ->queryAll();
        }

        return Yii::app()->db->createCommand(
            'SELECT id, name
            FROM edcenso_stage_vs_modality
            ORDER BY name'
        )->queryAll();
    }

    public function getDisciplines($stageIds = null): array
    {
        $disciplinesLabels = ClassroomHelper::classroomDisciplineLabelArray();

        if ($stageIds === null) {
            $disciplines = EdcensoDiscipline::model()->findAll(['order' => 'name']);
            return $this->formatDisciplines($disciplines, $disciplinesLabels);
        }

        $stageIds = $this->normalizeStageIds($stageIds);
        if (empty($stageIds)) {
            return [];
        }
        $stageList = implode(',', $stageIds);

        if (TagUtils::isInstructor()) {
            $rows = Yii::app()->db->createCommand(
                'SELECT DISTINCT ed.id
                FROM teaching_matrixes tm
                JOIN instructor_teaching_data itd ON itd.id = tm.teaching_data_fk
                JOIN instructor_identification ii ON ii.id = itd.instructor_fk
                JOIN curricular_matrix cm ON cm.id = tm.curricular_matrix_fk
                JOIN edcenso_discipline ed ON ed.id = cm.discipline_fk
                WHERE ii.users_fk = :user_id
                    AND cm.stage_fk IN (' . $stageList . ')
                    AND cm.school_year = :school_year
                ORDER BY ed.name'
            )
                ->bindValue(':user_id', Yii::app()->user->loginInfos->id, PDO::PARAM_INT)
                ->bindValue(':school_year', Yii::app()->user->year, PDO::PARAM_INT)
                ->queryAll();
        } else {
            $rows = Yii::app()->db->createCommand(
                'SELECT DISTINCT cm.discipline_fk AS id
                FROM curricular_matrix cm
                JOIN edcenso_discipline ed ON ed.id = cm.discipline_fk
                WHERE cm.stage_fk IN (' . $stageList . ')
                    AND cm.school_year = :school_year
                ORDER BY ed.name'
            )
                ->bindValue(':school_year', Yii::app()->user->year, PDO::PARAM_INT)
                ->queryAll();
        }

        $result = [];
        foreach ($rows as $row) {
            $id = (int) $row['id'];
            $result[] = [
                'id' => $id,
                'name' => $disciplinesLabels[$id] ?? '',
                'isMinorEducation' => count($stageIds) === 1 && TagUtils::isStageChildishEducation($stageIds[0]),
            ];
        }

        return $result;
    }

    public function getClassrooms(): array
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'school_inep_fk = :school AND school_year = :school_year';
        $criteria->params = [
            ':school' => Yii::app()->user->school,
            ':school_year' => Yii::app()->user->year,
        ];
        $criteria->order = 'name ASC';

        return Classroom::model()->findAll($criteria);
    }

    public function getSectionValues(MaceteLessonPlan $lessonPlan): array
    {
        $values = [];
        foreach ($lessonPlan->sections as $section) {
            $targetGroup = $section->target_group ?: 'general';
            $values[$section->section_type][$targetGroup] = $section->content;
        }

        return $values;
    }

    public function getResourceValues(MaceteLessonPlan $lessonPlan): array
    {
        $values = [];
        foreach ($lessonPlan->resources as $resource) {
            $values[$resource->resource_type] = $resource->description;
        }

        return $values;
    }

    public function getMaterialValues(MaceteLessonPlan $lessonPlan): array
    {
        $values = [];
        foreach ($lessonPlan->materials as $material) {
            $values[$material->material_type] = [
                'title' => $material->title,
                'description' => $material->description,
                'file_path' => $material->file_path,
            ];
        }

        return $values;
    }

    public function getStageIds(MaceteLessonPlan $lessonPlan): array
    {
        return $lessonPlan->getStageIds();
    }

    public function getStagesByIds(array $stageIds): array
    {
        $stageIds = $this->normalizeStageIds($stageIds);
        if (empty($stageIds)) {
            return [];
        }

        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $stageIds);
        $criteria->order = 'name ASC';

        return EdcensoStageVsModality::model()->findAll($criteria);
    }

    public function getAbilityIds(MaceteLessonPlan $lessonPlan): array
    {
        $ids = [];
        foreach ($lessonPlan->abilities as $ability) {
            $ids[] = (int) $ability->ability_fk;
        }

        return $ids;
    }

    private function normalizePlanData(array $planData): array
    {
        foreach (['classroom_fk', 'edcenso_discipline_fk'] as $nullableField) {
            if (array_key_exists($nullableField, $planData) && $planData[$nullableField] === '') {
                $planData[$nullableField] = null;
            }
        }

        return $planData;
    }

    public function normalizeStageIds($stageIds): array
    {
        if (!is_array($stageIds)) {
            $stageIds = [$stageIds];
        }

        $normalized = [];
        foreach ($stageIds as $stageId) {
            if ($stageId === null || $stageId === '') {
                continue;
            }
            $stageId = (int) $stageId;
            if ($stageId > 0) {
                $normalized[] = $stageId;
            }
        }

        return array_values(array_unique($normalized));
    }

    private function syncStages(MaceteLessonPlan $lessonPlan, array $stageIds): void
    {
        MaceteLessonPlanStage::model()->deleteAll(
            'lesson_plan_fk = :lesson_plan_fk',
            [':lesson_plan_fk' => $lessonPlan->id]
        );

        foreach ($stageIds as $stageId) {
            $stage = new MaceteLessonPlanStage();
            $stage->lesson_plan_fk = $lessonPlan->id;
            $stage->edcenso_stage_vs_modality_fk = $stageId;

            if (!$stage->save()) {
                throw new CException('Não foi possível salvar uma etapa do plano MACETE.');
            }
        }
    }

    private function syncAbilities(MaceteLessonPlan $lessonPlan, array $abilityIds): void
    {
        $abilityIds = $this->abilityService->normalizeIds($abilityIds);
        MaceteLessonPlanAbility::model()->deleteAll(
            'lesson_plan_fk = :lesson_plan_fk',
            [':lesson_plan_fk' => $lessonPlan->id]
        );

        foreach ($abilityIds as $abilityId) {
            $ability = new MaceteLessonPlanAbility();
            $ability->lesson_plan_fk = $lessonPlan->id;
            $ability->ability_fk = $abilityId;

            if (!$ability->save()) {
                throw new CException('Não foi possível salvar uma habilidade do plano MACETE.');
            }
        }
    }

    private function syncSections(MaceteLessonPlan $lessonPlan, array $sections): void
    {
        MaceteLessonPlanSection::model()->deleteAll(
            'lesson_plan_fk = :lesson_plan_fk',
            [':lesson_plan_fk' => $lessonPlan->id]
        );

        $position = 1;
        foreach ($sections as $type => $targets) {
            if (!is_array($targets)) {
                $targets = ['general' => $targets];
            }
            foreach ($targets as $targetGroup => $content) {
                if (trim((string) $content) === '') {
                    continue;
                }

                $section = new MaceteLessonPlanSection();
                $section->lesson_plan_fk = $lessonPlan->id;
                $section->section_type = $type;
                $section->target_group = $targetGroup;
                $section->title = MaceteLessonPlanSection::sectionLabels()[$type] ?? $type;
                $section->content = $content;
                $section->position = $position++;

                if (!$section->save()) {
                    throw new CException('Não foi possível salvar uma seção do plano MACETE.');
                }
            }
        }
    }

    private function syncResources(MaceteLessonPlan $lessonPlan, array $resources): void
    {
        MaceteLessonPlanResource::model()->deleteAll(
            'lesson_plan_fk = :lesson_plan_fk',
            [':lesson_plan_fk' => $lessonPlan->id]
        );

        foreach ($resources as $type => $description) {
            if (trim((string) $description) === '') {
                continue;
            }

            $resource = new MaceteLessonPlanResource();
            $resource->lesson_plan_fk = $lessonPlan->id;
            $resource->resource_type = $type;
            $resource->name = MaceteLessonPlanResource::typeLabels()[$type] ?? $type;
            $resource->description = $description;

            if (!$resource->save()) {
                throw new CException('Não foi possível salvar um recurso do plano MACETE.');
            }
        }
    }

    private function syncMaterials(MaceteLessonPlan $lessonPlan, array $materials): void
    {
        MaceteLessonMaterial::model()->deleteAll(
            'lesson_plan_fk = :lesson_plan_fk',
            [':lesson_plan_fk' => $lessonPlan->id]
        );

        foreach ($materials as $type => $materialData) {
            if (!is_array($materialData)) {
                continue;
            }

            $title = trim((string) ($materialData['title'] ?? ''));
            $description = trim((string) ($materialData['description'] ?? ''));
            $filePath = trim((string) ($materialData['file_path'] ?? ''));

            if ($title === '' && $description === '' && $filePath === '') {
                continue;
            }

            $material = new MaceteLessonMaterial();
            $material->lesson_plan_fk = $lessonPlan->id;
            $material->material_type = $type;
            $material->title = $title !== '' ? $title : (MaceteLessonMaterial::typeLabels()[$type] ?? $type);
            $material->description = $description;
            $material->file_path = $filePath !== '' ? $filePath : null;

            if (!$material->save()) {
                throw new CException('Não foi possível salvar um material do plano MACETE.');
            }
        }
    }

    private function formatDisciplines(array $disciplines, array $labels): array
    {
        $result = [];
        foreach ($disciplines as $discipline) {
            $result[] = [
                'id' => (int) $discipline->id,
                'name' => $labels[$discipline->id] ?? $discipline->name,
                'isMinorEducation' => false,
            ];
        }

        return $result;
    }
}
