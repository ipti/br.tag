<?php

class AbilityController extends Controller
{
    private ?MaceteAbilityService $abilityService = null;

    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['search', 'initialStructure', 'nextStructure'],
                'users' => ['@'],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    public function actionSearch($q = '')
    {
        echo CJSON::encode($this->abilityService()->search((string) $q));
        Yii::app()->end();
    }

    public function actionInitialStructure()
    {
        $disciplineId = Yii::app()->request->getPost('discipline');
        $disciplineId = $disciplineId !== null && $disciplineId !== '' ? (int) $disciplineId : null;

        echo CJSON::encode($this->abilityService()->initialStructure($disciplineId));
        Yii::app()->end();
    }

    public function actionNextStructure()
    {
        $parentId = (int) Yii::app()->request->getPost('id');

        echo CJSON::encode($this->abilityService()->nextStructure($parentId));
        Yii::app()->end();
    }

    private function abilityService(): MaceteAbilityService
    {
        if ($this->abilityService === null) {
            $this->abilityService = new MaceteAbilityService();
        }

        return $this->abilityService;
    }
}

