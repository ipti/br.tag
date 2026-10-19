<?php

/**
 * TUrlBridge — injeta URLs PHP-geradas em JavaScript via window.AppRoutes.
 *
 * Resolve rotas usando o urlManager do Yii (respeitando URL rules e rewrite),
 * eliminando strings ?r=... hardcoded em arquivos JS.
 *
 * Uso no controller ou view:
 *
 *   TUrlBridge::register([
 *       'markRead'    => NotificationsRoutes::INBOX_MARKREAD,
 *       'unreadCount' => NotificationsRoutes::INBOX_UNREADCOUNT,
 *       'update'      => [StudentRoutes::STUDENT_UPDATE, ['id' => $model->id]],
 *   ]);
 *   TUrlBridge::flush();
 *
 * No JavaScript:
 *
 *   $.post(AppRoutes.markRead + '&id=' + id, ...);
 *   fetch(AppRoutes.update);
 *
 * window.AppRoutes é acumulativo — múltiplos flush() em includes distintos
 * são mesclados via Object.assign sem sobrescrever chaves anteriores.
 */
class TUrlBridge
{
    /** @var array<string, string> */
    private static array $routes = [];

    /**
     * Registra um mapa de chave → rota para posterior injeção no JS.
     *
     * Cada entrada pode ser:
     *   - string:  nome de rota direto (ex: NotificationsRoutes::INBOX_MARKREAD)
     *   - array:   [rota, params] (ex: [StudentRoutes::STUDENT_UPDATE, ['id' => 5]])
     *
     * @param array<string, string|array{0: string, 1?: array<string, mixed>}> $routes
     */
    public static function register(array $routes): void
    {
        foreach ($routes as $key => $def) {
            if (is_array($def)) {
                [$route, $params] = [$def[0], $def[1] ?? []];
            } else {
                [$route, $params] = [$def, []];
            }

            self::$routes[$key] = Yii::app()->createUrl($route, $params);
        }
    }

    /**
     * Emite o bloco de script que define/mescla window.AppRoutes.
     * Deve ser chamado após register() e antes do render da view.
     * Limpa o buffer interno após emitir.
     */
    public static function flush(): void
    {
        if (empty(self::$routes)) {
            return;
        }

        $json = json_encode(self::$routes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        Yii::app()->clientScript->registerScript(
            'tag-app-routes',
            "window.AppRoutes = Object.assign(window.AppRoutes || {}, {$json});",
            CClientScript::POS_HEAD
        );

        self::$routes = [];
    }
}
