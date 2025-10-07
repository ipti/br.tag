<?php

class DefaultController extends Controller
{
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => [
                    'managemodules',
                    'editManagemodules'
                ],
                'expression' => 'TagUtils::isSuperuser()',
            ],
            [
                'deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function actionManageModules()
    {
        $configs = FeatureFlags::model()->with('featureName')->findAll();
        $configs = array_filter($configs, fn ($config) => str_starts_with($config->feature_name, 'TASK'));
        $this->render('manageModules', [
            'configs' => $configs
        ]);
    }

    public function actionEditManageModules()
    {
        $changed = false;
        foreach ($_POST['configs'] as $config) {
            $instanceConfig = FeatureFlags::model()->findByAttributes(['feature_name' => $config['id']]);
            if ($instanceConfig->active != $config['value']) {
                $instanceConfig->active = $config['value'];
                $instanceConfig->save();
                $changed = true;
            }
        }
        echo json_encode(['valid' => $changed, 'text' => 'Configurações alteradas com sucesso.</br>']);
    }
}
