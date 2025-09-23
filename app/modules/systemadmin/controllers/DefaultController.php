<?php

class DefaultController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions' => array(
                    'managemodules',
                    'editManagemodules'
                ),
                'expression' => 'TagUtils::isSuperuser()',
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

	public function actionManageModules()
	{
		// if (!Yii::app()->getAuthManager()->checkAccess('superuser', Yii::app()->user->loginInfos->id)) {
		// 	$this->redirect(array('/'));
		// 	Yii::app()->end();
		// }

		$configs = FeatureFlags::model()->with('featureName')->findAll();
        $configs = array_filter($configs, fn($config) => str_starts_with($config->feature_name, 'TASK'));
		$this->render('manageModules', [
			"configs" => $configs
		]);
	}

	
    public function actionEditManageModules()
    {
        $changed = false;
        foreach ($_POST["configs"] as $config) {
            $instanceConfig = FeatureFlags::model()->findByAttributes(['feature_name' => $config["id"]]);
            if ($instanceConfig->active != $config["value"]) {
                $instanceConfig->active = $config["value"];
                $instanceConfig->save();
                $changed = true;
            }
        }
        echo json_encode(["valid" => $changed, "text" => "Configurações alteradas com sucesso.</br>"]);
    }
}
