<?php

/**
 * Controller de gerenciamento de notificações (admin).
 * Permite criar, listar e visualizar notificações enviadas.
 */
class NotificationsController extends Controller
{
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
                'actions' => ['index', 'create', 'view', 'delete'],
                'users' => ['@'],
            ],
            ['deny', 'users' => ['*']],
        ];
    }

    /**
     * Lista notificações enviadas.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 'created_at DESC';
        $criteria->limit = 50;

        $notifications = Notification::model()->findAll($criteria);

        $this->render('index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Form de criação de notificação.
     */
    public function actionCreate()
    {
        $model = new Notification();

        if (isset($_POST['Notification'])) {
            $title = $_POST['Notification']['title'];
            $body = $_POST['Notification']['body'];
            $type = $_POST['Notification']['type'] ?? 'info';
            $schoolId = !empty($_POST['Notification']['school_fk']) ? (int) $_POST['Notification']['school_fk'] : null;
            $targetRoles = $_POST['targetRoles'] ?? [];
            $expirationDays = isset($_POST['expirationDays']) ? (int) $_POST['expirationDays'] : null;

            $options = [
                'type' => $type,
                'source' => 'admin',
                'createdBy' => Yii::app()->user->id,
                'schoolId' => $schoolId,
            ];

            if ($expirationDays !== null) {
                $options['expirationDays'] = $expirationDays;
            }

            if (!empty($targetRoles)) {
                $options['targetRoles'] = $targetRoles;
                $notification = Yii::app()->notifier->broadcast($title, $body, $options);
            } else {
                // Se nenhum role selecionado, broadcast para todos
                $options['targetRoles'] = array_map(fn($r) => $r->value, TRole::cases());
                $notification = Yii::app()->notifier->broadcast($title, $body, $options);
            }

            if ($notification && $notification->id) {
                Yii::app()->user->setFlash('success', 'Notificação enviada com sucesso!');
                $this->redirect(['index']);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Visualiza detalhes de uma notificação.
     */
    public function actionView($id)
    {
        $notification = Notification::model()->with('recipients')->findByPk((int) $id);

        if (!$notification) {
            throw new CHttpException(404, 'Notificação não encontrada.');
        }

        $this->render('view', [
            'notification' => $notification,
        ]);
    }

    /**
     * Exclui uma notificação.
     */
    public function actionDelete($id)
    {
        $notification = Notification::model()->findByPk((int) $id);

        if (!$notification) {
            throw new CHttpException(404, 'Notificação não encontrada.');
        }

        $notification->delete();
        Yii::app()->user->setFlash('success', 'Notificação excluída.');
        $this->redirect(['index']);
    }
}
