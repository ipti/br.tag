<?php

class MaceteLessonPlanTemplateService
{
    private MaceteAbilityService $abilityService;

    private MaceteLessonPlanService $lessonPlanService;

    public function __construct(?MaceteAbilityService $abilityService = null, ?MaceteLessonPlanService $lessonPlanService = null)
    {
        $this->abilityService = $abilityService ?? new MaceteAbilityService();
        $this->lessonPlanService = $lessonPlanService ?? new MaceteLessonPlanService();
    }

    public function save(MaceteLessonPlanTemplate $template, array $request): MaceteLessonPlanTemplate
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $templateData = $request['MaceteLessonPlanTemplate'] ?? [];
            $stageComponents = $this->lessonPlanService->normalizeStageComponents($request['stage_components'] ?? []);
            if (empty($stageComponents)) {
                throw new CException('Adicione pelo menos uma etapa e seu componente curricular.');
            }

            $template->attributes = $this->editableTemplateData($templateData);
            $template->theme = $template->name;
            $template->edcenso_stage_vs_modality_fk = $stageComponents[0]['stage_id'];
            $template->edcenso_discipline_fk = $stageComponents[0]['discipline_id'];

            if ($template->isNewRecord) {
                $template->created_by_users_fk = (int) Yii::app()->user->loginInfos->id;
            }

            if (!$template->save()) {
                throw new CException('Não foi possível salvar o modelo de plano MACETE.');
            }

            $this->syncStages($template, $stageComponents);
            $this->syncAbilities($template, $request['abilities'] ?? []);
            $this->syncSections($template, $request['sections'] ?? []);
            $this->syncResources($template, $request['resources'] ?? []);
            $this->syncMaterials($template, $request['materials'] ?? []);

            $transaction->commit();

            return $template;
        } catch (Exception $exception) {
            if ($transaction->active) {
                $transaction->rollBack();
            }

            throw $exception;
        }
    }

    public function loadModel(int $id): ?MaceteLessonPlanTemplate
    {
        return MaceteLessonPlanTemplate::model()->findByPk($id);
    }

    public function getStageComponents(MaceteLessonPlanTemplate $template): array
    {
        $components = [];
        foreach ($template->planStages as $planStage) {
            $components[] = [
                'stage_id' => (int) $planStage->edcenso_stage_vs_modality_fk,
                'discipline_id' => $planStage->edcenso_discipline_fk !== null ? (int) $planStage->edcenso_discipline_fk : null,
            ];
        }

        if (empty($components) && $template->edcenso_stage_vs_modality_fk !== null) {
            $components[] = [
                'stage_id' => (int) $template->edcenso_stage_vs_modality_fk,
                'discipline_id' => $template->edcenso_discipline_fk !== null ? (int) $template->edcenso_discipline_fk : null,
            ];
        }

        return $components;
    }

    public function getSectionValues(MaceteLessonPlanTemplate $template): array
    {
        $values = [];
        foreach ($template->sections as $section) {
            $targetGroup = $section->target_group ?: 'general';
            $values[$section->section_type][$targetGroup] = $section->content;
        }

        return $values;
    }

    public function getResourceValues(MaceteLessonPlanTemplate $template): array
    {
        $values = [];
        foreach ($template->resources as $resource) {
            $values[$resource->resource_type] = $resource->description;
        }

        return $values;
    }

    public function getMaterialValues(MaceteLessonPlanTemplate $template): array
    {
        $values = [];
        foreach ($template->materials as $material) {
            $values[$material->material_type][] = [
                'title' => $material->title,
                'description' => $material->description,
                'file_path' => $material->file_path,
            ];
        }

        return $values;
    }

    public function getAbilityIds(MaceteLessonPlanTemplate $template): array
    {
        $ids = [];
        foreach ($template->abilities as $ability) {
            $ids[] = (int) $ability->ability_fk;
        }

        return $ids;
    }

    private function editableTemplateData(array $templateData): array
    {
        $allowedAttributes = [
            'name',
            'code',
            'unit',
            'knowledge_object',
            'evaluation',
            'references_text',
        ];

        $templateData = array_intersect_key($templateData, array_flip($allowedAttributes));
        foreach (['knowledge_object', 'evaluation', 'references_text'] as $attribute) {
            if (array_key_exists($attribute, $templateData)) {
                $templateData[$attribute] = MaceteRichTextSanitizer::sanitize((string) $templateData[$attribute]);
            }
        }

        return $templateData;
    }

    private function syncStages(MaceteLessonPlanTemplate $template, array $stageComponents): void
    {
        MaceteLessonPlanTemplateStage::model()->deleteAll(
            'lesson_plan_template_fk = :lesson_plan_template_fk',
            [':lesson_plan_template_fk' => $template->id]
        );

        foreach ($stageComponents as $stageComponent) {
            $stage = new MaceteLessonPlanTemplateStage();
            $stage->lesson_plan_template_fk = $template->id;
            $stage->edcenso_stage_vs_modality_fk = $stageComponent['stage_id'];
            $stage->edcenso_discipline_fk = $stageComponent['discipline_id'];

            if (!$stage->save()) {
                throw new CException('Não foi possível salvar uma etapa do modelo de plano MACETE.');
            }
        }
    }

    private function syncAbilities(MaceteLessonPlanTemplate $template, array $abilityIds): void
    {
        $abilityIds = $this->abilityService->normalizeIds($abilityIds);
        MaceteLessonPlanTemplateAbility::model()->deleteAll(
            'lesson_plan_template_fk = :lesson_plan_template_fk',
            [':lesson_plan_template_fk' => $template->id]
        );

        foreach ($abilityIds as $abilityId) {
            $ability = new MaceteLessonPlanTemplateAbility();
            $ability->lesson_plan_template_fk = $template->id;
            $ability->ability_fk = $abilityId;

            if (!$ability->save()) {
                throw new CException('Não foi possível salvar uma habilidade do modelo de plano MACETE.');
            }
        }
    }

    private function syncSections(MaceteLessonPlanTemplate $template, array $sections): void
    {
        MaceteLessonPlanTemplateSection::model()->deleteAll(
            'lesson_plan_template_fk = :lesson_plan_template_fk',
            [':lesson_plan_template_fk' => $template->id]
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

                $section = new MaceteLessonPlanTemplateSection();
                $section->lesson_plan_template_fk = $template->id;
                $section->section_type = $type;
                $section->target_group = $targetGroup;
                $section->title = MaceteLessonPlanTemplateSection::sectionLabels()[$type] ?? $type;
                $section->content = $content;
                $section->position = $position++;

                if (!$section->save()) {
                    throw new CException('Não foi possível salvar uma seção do modelo de plano MACETE.');
                }
            }
        }
    }

    private function syncResources(MaceteLessonPlanTemplate $template, array $resources): void
    {
        MaceteLessonPlanTemplateResource::model()->deleteAll(
            'lesson_plan_template_fk = :lesson_plan_template_fk',
            [':lesson_plan_template_fk' => $template->id]
        );

        foreach ($resources as $type => $description) {
            if (is_array($description)) {
                $description = implode(', ', array_filter(array_map('trim', $description), static fn (string $item): bool => $item !== ''));
            }

            $description = MaceteRichTextSanitizer::sanitize((string) $description);
            if ($description === '') {
                continue;
            }

            $resource = new MaceteLessonPlanTemplateResource();
            $resource->lesson_plan_template_fk = $template->id;
            $resource->resource_type = $type;
            $resource->name = MaceteLessonPlanTemplateResource::typeLabels()[$type] ?? $type;
            $resource->description = $description;

            if (!$resource->save()) {
                throw new CException('Não foi possível salvar um recurso do modelo de plano MACETE.');
            }
        }
    }

    private function syncMaterials(MaceteLessonPlanTemplate $template, array $materials): void
    {
        MaceteLessonMaterialTemplate::model()->deleteAll(
            'lesson_plan_template_fk = :lesson_plan_template_fk',
            [':lesson_plan_template_fk' => $template->id]
        );

        foreach ($materials as $type => $entries) {
            if (!is_array($entries)) {
                continue;
            }

            if (array_key_exists('title', $entries) || array_key_exists('description', $entries) || array_key_exists('file_path', $entries)) {
                $entries = [$entries];
            }

            foreach ($entries as $materialData) {
                if (!is_array($materialData)) {
                    continue;
                }

                $title = trim((string) ($materialData['title'] ?? ''));
                $description = MaceteRichTextSanitizer::sanitize((string) ($materialData['description'] ?? ''));
                $filePath = trim((string) ($materialData['file_path'] ?? ''));

                if ($title === '' && $description === '' && $filePath === '') {
                    continue;
                }

                $material = new MaceteLessonMaterialTemplate();
                $material->lesson_plan_template_fk = $template->id;
                $material->material_type = $type;
                $material->title = $title !== '' ? $title : (MaceteLessonMaterialTemplate::typeLabels()[$type] ?? $type);
                $material->description = $description;
                $material->file_path = $filePath !== '' ? $filePath : null;

                if (!$material->save()) {
                    throw new CException('Não foi possível salvar um material do modelo de plano MACETE.');
                }
            }
        }
    }
}
