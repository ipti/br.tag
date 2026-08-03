<?php

class MaceteAiPlanContextResolver
{
    private MaceteLessonPlanService $lessonPlanService;
    private MaceteAbilityService $abilityService;
    private MaceteAccessService $accessService;
    private string $actorPseudonymKey;

    public function __construct(
        ?MaceteLessonPlanService $lessonPlanService = null,
        ?MaceteAbilityService $abilityService = null,
        ?MaceteAccessService $accessService = null,
        ?string $actorPseudonymKey = null
    ) {
        $this->lessonPlanService = $lessonPlanService ?? new MaceteLessonPlanService();
        $this->abilityService = $abilityService ?? new MaceteAbilityService();
        $this->accessService = $accessService ?? new MaceteAccessService();
        $this->actorPseudonymKey = $actorPseudonymKey ?? (string) getenv('MACETE_AI_ACTOR_PSEUDONYM_KEY');
    }

    public function isConfigured(): bool
    {
        return $this->actorPseudonymKey !== '';
    }

    public function createConversationPayload(MaceteLessonPlan $lessonPlan): array
    {
        return [
            'tenant_id' => 'school:' . $lessonPlan->school_inep_fk,
            'actor' => [
                'id' => $this->pseudonymizeActor($this->accessService->currentUserId()),
                'role' => TagUtils::isInstructor() ? 'instructor' : 'administrator',
            ],
            'plan' => $this->resolvePlan($lessonPlan),
        ];
    }

    public function sendMessagePayload(MaceteLessonPlan $lessonPlan, string $message): array
    {
        return [
            'tenant_id' => 'school:' . $lessonPlan->school_inep_fk,
            'message' => trim($message),
        ];
    }

    private function resolvePlan(MaceteLessonPlan $lessonPlan): array
    {
        return [
            'external_id' => $lessonPlan->isNewRecord ? null : (int) $lessonPlan->id,
            'school_year' => (int) $lessonPlan->school_year,
            'name' => (string) $lessonPlan->name,
            'theme' => (string) $lessonPlan->theme,
            'unit' => (string) $lessonPlan->unit,
            'stage_components' => $this->resolveStageComponents($lessonPlan),
            'abilities' => $this->resolveAbilities($lessonPlan),
            'draft' => [
                'territory_context' => (string) $lessonPlan->territory_context,
                'knowledge_object' => (string) $lessonPlan->knowledge_object,
                'sections' => $this->lessonPlanService->getSectionValues($lessonPlan),
                'resources' => $this->lessonPlanService->getResourceValues($lessonPlan),
                'evaluation' => (string) $lessonPlan->evaluation,
                'references_text' => (string) $lessonPlan->references_text,
            ],
        ];
    }

    private function resolveStageComponents(MaceteLessonPlan $lessonPlan): array
    {
        $components = [];
        foreach ($lessonPlan->planStages as $planStage) {
            $components[] = $this->formatStageComponent(
                $planStage->stageFk,
                $planStage->disciplineFk,
            );
        }

        if (empty($components)) {
            $components[] = $this->formatStageComponent($lessonPlan->stageFk, $lessonPlan->disciplineFk);
        }

        return $components;
    }

    private function formatStageComponent(?EdcensoStageVsModality $stage, ?EdcensoDiscipline $discipline): array
    {
        if ($stage === null || $discipline === null) {
            throw new CException('O plano MACETE precisa ter etapa e componente curricular antes de usar o assistente.');
        }

        return [
            'stage_id' => (int) $stage->id,
            'stage_name' => (string) $stage->name,
            'discipline_id' => (int) $discipline->id,
            'discipline_name' => (string) $discipline->name,
        ];
    }

    private function resolveAbilities(MaceteLessonPlan $lessonPlan): array
    {
        $abilities = [];
        foreach ($this->abilityService->getByIds($this->lessonPlanService->getAbilityIds($lessonPlan)) as $ability) {
            $abilities[] = [
                'id' => (int) $ability->id,
                'code' => (string) $ability->code,
                'description' => (string) $ability->description,
            ];
        }

        return $abilities;
    }

    private function pseudonymizeActor(int $userId): string
    {
        if (!$this->isConfigured()) {
            throw new MaceteAiAssistantGatewayException('assistant_not_configured');
        }

        return 'actor:' . hash_hmac('sha256', (string) $userId, $this->actorPseudonymKey);
    }
}
