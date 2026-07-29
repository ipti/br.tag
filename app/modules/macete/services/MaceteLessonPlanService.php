<?php

class MaceteLessonPlanService
{
    private MaceteAbilityService $abilityService;

    private MaceteAccessService $accessService;

    public function __construct(?MaceteAbilityService $abilityService = null, ?MaceteAccessService $accessService = null)
    {
        $this->abilityService = $abilityService ?? new MaceteAbilityService();
        $this->accessService = $accessService ?? new MaceteAccessService();
    }

    public function save(MaceteLessonPlan $lessonPlan, array $request): MaceteLessonPlan
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $planData = $request['MaceteLessonPlan'] ?? [];
            $stageComponents = $this->normalizeStageComponents($request['stage_components'] ?? []);
            if (empty($stageComponents)) {
                throw new CException('Adicione pelo menos uma etapa e seu componente curricular.');
            }

            $lessonPlan->attributes = $this->editablePlanData($planData);
            $lessonPlan->edcenso_stage_vs_modality_fk = $stageComponents[0]['stage_id'];
            $lessonPlan->edcenso_discipline_fk = $stageComponents[0]['discipline_id'];

            if ($lessonPlan->classroom_fk !== null) {
                $classroom = $this->accessService->findClassroom((int) $lessonPlan->classroom_fk);
                if ($classroom === null) {
                    throw new CException('Turma não encontrada ou não disponível para o usuário atual.');
                }

                if (!in_array((int) $classroom->edcenso_stage_vs_modality_fk, $this->getStageIdsFromComponents($stageComponents), true)) {
                    throw new CException('A turma selecionada não pertence a uma etapa prevista no plano MACETE.');
                }
            }

            if ($lessonPlan->isNewRecord) {
                $lessonPlan->school_inep_fk = Yii::app()->user->school;
                $lessonPlan->users_fk = $this->accessService->currentUserId();
                $lessonPlan->school_year = Yii::app()->user->year;
            }

            if ($lessonPlan->status === null || $lessonPlan->status === '') {
                $lessonPlan->status = MaceteLessonPlan::STATUS_DRAFT;
            }

            if (!$lessonPlan->save()) {
                throw new CException('Não foi possível salvar o plano MACETE.');
            }

            $this->syncStages($lessonPlan, $stageComponents);
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
        $criteria->addCondition('school_inep_fk = :macete_school');
        $criteria->addCondition('school_year = :macete_school_year');
        $criteria->params = [
            ':macete_school' => Yii::app()->user->school,
            ':macete_school_year' => Yii::app()->user->year,
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

    public function getStageComponents(MaceteLessonPlan $lessonPlan): array
    {
        $components = [];
        foreach ($lessonPlan->planStages as $planStage) {
            $components[] = [
                'stage_id' => (int) $planStage->edcenso_stage_vs_modality_fk,
                'discipline_id' => $planStage->edcenso_discipline_fk !== null ? (int) $planStage->edcenso_discipline_fk : null,
            ];
        }

        if (empty($components) && $lessonPlan->edcenso_stage_vs_modality_fk !== null) {
            $components[] = [
                'stage_id' => (int) $lessonPlan->edcenso_stage_vs_modality_fk,
                'discipline_id' => $lessonPlan->edcenso_discipline_fk !== null ? (int) $lessonPlan->edcenso_discipline_fk : null,
            ];
        }

        return $components;
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

    private function editablePlanData(array $planData): array
    {
        $allowedAttributes = [
            'name',
            'theme',
            'classroom_fk',
            'unit',
            'territory_context',
            'knowledge_object',
            'evaluation',
            'references_text',
            'status',
        ];

        $planData = $this->normalizePlanData(array_intersect_key($planData, array_flip($allowedAttributes)));
        foreach (['territory_context', 'knowledge_object', 'evaluation', 'references_text'] as $attribute) {
            if (array_key_exists($attribute, $planData)) {
                $planData[$attribute] = MaceteRichTextSanitizer::sanitize((string) $planData[$attribute]);
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

    public function normalizeStageComponents($stageComponents): array
    {
        if (!is_array($stageComponents)) {
            return [];
        }

        $normalized = [];
        foreach ($stageComponents as $component) {
            if (!is_array($component)) {
                continue;
            }

            $stageId = (int) ($component['stage_id'] ?? 0);
            $disciplineId = (int) ($component['discipline_id'] ?? 0);
            if ($stageId <= 0 || $disciplineId <= 0) {
                continue;
            }
            if (isset($normalized[$stageId])) {
                throw new CException('Cada etapa pode ser associada a apenas um componente curricular.');
            }

            $allowedDisciplines = $this->getDisciplines([$stageId]);
            $allowedDisciplineIds = array_map(static fn (array $discipline): int => (int) $discipline['id'], $allowedDisciplines);
            if (!in_array($disciplineId, $allowedDisciplineIds, true)) {
                throw new CException('O componente curricular selecionado não pertence à etapa informada.');
            }

            $normalized[$stageId] = [
                'stage_id' => $stageId,
                'discipline_id' => $disciplineId,
            ];
        }

        return array_values($normalized);
    }

    private function syncStages(MaceteLessonPlan $lessonPlan, array $stageComponents): void
    {
        MaceteLessonPlanStage::model()->deleteAll(
            'lesson_plan_fk = :lesson_plan_fk',
            [':lesson_plan_fk' => $lessonPlan->id]
        );

        foreach ($stageComponents as $stageComponent) {
            $stage = new MaceteLessonPlanStage();
            $stage->lesson_plan_fk = $lessonPlan->id;
            $stage->edcenso_stage_vs_modality_fk = $stageComponent['stage_id'];
            $stage->edcenso_discipline_fk = $stageComponent['discipline_id'];

            if (!$stage->save()) {
                throw new CException('Não foi possível salvar uma etapa do plano MACETE.');
            }
        }
    }

    private function getStageIdsFromComponents(array $stageComponents): array
    {
        return array_map(static fn (array $component): int => $component['stage_id'], $stageComponents);
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
                $content = MaceteRichTextSanitizer::sanitize((string) $content);
                if ($content === '') {
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
            $description = MaceteRichTextSanitizer::sanitize((string) $description);
            if ($description === '') {
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
            $description = MaceteRichTextSanitizer::sanitize((string) ($materialData['description'] ?? ''));
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
