<?php

use Sentry\Tracing\TransactionContext;
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */

use Sentry\SentrySdk;
use Sentry\State\Hub;
use Sentry\Event;

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
    public $menu = [];
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = [];

    public function init()
    {
        parent::init();

        if (!Yii::app()->user->isGuest) {
            $authTimeout = Yii::app()->user->getState('authTimeout', SESSION_MAX_LIFETIME);
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
        $module = Yii::app()->controller->module ? Yii::app()->controller->module->id . '/' : '';
        $transaction = SentrySdk::getCurrentHub()->startTransaction(new TransactionContext(
            $module . '/' . Yii::app()->controller->id . '/' . $action->id,
        ));

        SentrySdk::getCurrentHub()->setSpan($transaction);

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

            Yii::app()->requestTracer->startRequestSpan(
                $module . '/' . Yii::app()->controller->id . '/' . $action->id,
                [
                    'user.id' => Yii::app()->user->isGuest ? null : Yii::app()->user->loginInfos->id,
                    'user.username' => Yii::app()->user->isGuest ? null : Yii::app()->user->loginInfos->username,
                    'user.roles' => Yii::app()->user->isGuest ? [] : Yii::app()->authManager->getRoles(Yii::app()->user->loginInfos->id),
                    'http.method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
                    'http.url' => $_SERVER['REQUEST_URI'] ?? '',
                    'client.ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                ]
            );

            // Atualiza a última atividade
            Yii::app()->user->setState('last_activity', time());
            return true;
        }
        return false;
    }

    public function afterAction($action)
    {
        $transaction = SentrySdk::getCurrentHub()->getSpan();
        if ($transaction) {
            $transaction->finish();
        }
        Yii::app()->requestTracer->endRequestSpan();

        return parent::afterAction($action);
    }
}
