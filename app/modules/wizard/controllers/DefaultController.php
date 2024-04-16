<?php

class DefaultController extends Controller {

    public function actionIndex() {
        $this->render('application.modules.wizard.views.default.index');
    }

}
