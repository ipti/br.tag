public function filters() {
	return array(
			'accessControl', 
			);
}

public function accessRules() {
	return array(
			array('allow',
				'actions'=>array('index'),
				'roles'=>array('*'),
				),
			array('allow', 
				'actions'=>array('create','update'),
				'roles'=>array('UserCreator'),
				),
			array('allow', 
				'actions'=>array('delete'),
				'users'=>array('admin'),
				),
			array('deny', 
				'users'=>array('*'),
				),
			);
}
