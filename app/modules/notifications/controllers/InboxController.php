<?php

/**
 * Controller de inbox pessoal do usuário.
 * Acessível por todos os roles autenticados.
 */
class InboxController extends Controller
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
                'users' => ['@'], // qualquer usuário autenticado
            ],
            ['deny', 'users' => ['*']],
        ];
    }

    /**
     * Inbox — lista de notificações do usuário.
     */
    public function actionIndex()
    {
        $userId = Yii::app()->user->loginInfos->id;
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $notifications = Yii::app()->notifier->getUserNotifications($userId, $limit, $offset);
        $unreadCount = Yii::app()->notifier->getUnreadCount($userId);

        $total = NotificationRecipient::model()
            ->forUser($userId)
            ->with(['notification' => ['scopes' => 'active']])
            ->count();

        $this->render('index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'page' => $page,
            'totalPages' => ceil($total / $limit),
        ]);
    }

    /**
     * Retorna contagem de não-lidas (JSON) — usado pelo polling AJAX.
     */
    public function actionUnreadCount()
    {
        $userId = Yii::app()->user->loginInfos->id;
        $count = Yii::app()->notifier->getUnreadCount($userId);

        header('Content-Type: application/json');
        echo CJSON::encode(['unread' => $count]);
        Yii::app()->end();
    }

    /**
     * Retorna as últimas N notificações (JSON) — usado pelo dropdown.
     */
    public function actionRecent()
    {
        $userId = Yii::app()->user->loginInfos->id;
        $notifications = Yii::app()->notifier->getUserNotifications($userId, 5, 0);

        $items = [];
        foreach ($notifications as $nr) {
            $n = $nr->notification;
            $items[] = [
                'id' => $nr->id,
                'title' => $n->title,
                'body' => mb_substr($n->body, 0, 100),
                'type' => $n->type,
                'source' => $n->source,
                'url' => $n->source_url,
                'is_read' => (bool) $nr->is_read,
                'created_at' => $n->created_at,
            ];
        }

        header('Content-Type: application/json');
        echo CJSON::encode([
            'unread' => Yii::app()->notifier->getUnreadCount($userId),
            'items' => $items,
        ]);
        Yii::app()->end();
    }

    /**
     * Marca uma notificação como lida.
     */
    public function actionMarkRead($id)
    {
        $userId = Yii::app()->user->loginInfos->id;
        Yii::app()->notifier->markAsRead((int) $id, $userId);

        if (Yii::app()->request->isAjaxRequest) {
            header('Content-Type: application/json');
            echo CJSON::encode(['success' => true]);
            Yii::app()->end();
        }

        $this->redirect(['index']);
    }

    /**
     * Marca todas como lidas.
     */
    public function actionMarkAllRead()
    {
        $userId = Yii::app()->user->loginInfos->id;
        Yii::app()->notifier->markAllAsRead($userId);

        if (Yii::app()->request->isAjaxRequest) {
            header('Content-Type: application/json');
            echo CJSON::encode(['success' => true]);
            Yii::app()->end();
        }

        $this->redirect(['index']);
    }
}
