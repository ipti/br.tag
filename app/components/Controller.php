<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();


    public function init()
    {
        parent::init();


        if (!Yii::app()->user->isGuest) {

            $authTimeout = Yii::app()->user->getState("authTimeout", SESSION_MAX_LIFETIME);
            Yii::app()->user->authTimeout = $authTimeout;

            Yii::app()->sentry->setUserContext([
                'id' => Yii::app()->user->loginInfos->id,
                'username' => Yii::app()->user->loginInfos->username,
                'role' => Yii::app()->authManager->getRoles(Yii::app()->user->loginInfos->id)
            ]);
        }

        if (Yii::app()->user->isGuest && Yii::app()->request->isAjaxRequest) {
            // Se a sessão expirou e é uma requisição AJAX
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['redirect' => Yii::app()->createUrl('site/login')]);
            Yii::app()->end();
        }
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // Verifica o timeout com base na última atividade
            if (isset(Yii::app()->user->authTimeout)) {
                $lastActivity = Yii::app()->user->getState('last_activity');
                $timeout = Yii::app()->user->authTimeout;

                if ($lastActivity !== null && (time() - $lastActivity > $timeout)) {
                    Yii::app()->user->logout();
                    return false;
                }
            }

            // Atualiza a última atividade
            Yii::app()->user->setState('last_activity', time());
            return true;
        }
        return false;
    }

}
