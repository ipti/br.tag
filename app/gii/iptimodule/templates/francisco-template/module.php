<?php echo "<?php\n"; ?>

class <?php echo $this->moduleClass; ?> extends CWebModule {

	public $baseScriptUrl;
	public $baseUrl;

	public function init() {
        $this->baseUrl = Yii::app()->createUrl("<?php echo $this->moduleID; ?>");
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.<?php echo $this->moduleID; ?>.resources'));
        $this->layoutPath = yii::getPathOfAlias("<?php echo $this->moduleID; ?>.views.layouts");
        $this->layout = "layout";

        Yii::app()->setComponents([
            'errorHandler' => [
                'errorAction' => '<?php echo $this->moduleID; ?>/default/error',
            ],
        ]);

		$this->setImport(array(
			'<?php echo $this->moduleID; ?>.models.*',
			'<?php echo $this->moduleID; ?>.components.*',
		));
	}

	public function beforeControllerAction($controller, $action){
		if(parent::beforeControllerAction($controller, $action)){
			return true;
		}
		else
			return false;
	}
}
