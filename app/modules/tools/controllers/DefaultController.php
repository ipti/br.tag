<?php

/**
 * Controller do módulo Tools.
 * Permite que o SUPERUSER execute console commands da aplicação via web.
 *
 * SEGURANÇA:
 *   - Acesso restrito exclusivamente ao role SUPERUSER
 *   - Apenas commands da $whitelist podem ser executados (sem execução arbitrária)
 *   - Cada execução é registrada no log da aplicação
 */
class DefaultController extends Controller
{
    /**
     * Lista de commands permitidos.
     * Formato: 'yiic-nome' => [ label, description, args_disponíveis ]
     */
    private $whitelist = [
        'deduplicateinstructors' => [
            'label' => 'Deduplicar Professores',
            'description' => 'Remove cadastros duplicados de professores com o mesmo CPF, mantendo o mais antigo e mesclando os dados.',
            'icon' => 'fa-users',
            'args' => [
                '' => 'Dry-run (preview — não altera nada)',
                '--commit' => 'Commit (aplica as alterações no banco)',
            ],
        ],
        'purgenotifications' => [
            'label' => 'Limpar Notificações Expiradas',
            'description' => 'Remove do banco todas as notificações cuja data de expiração já passou.',
            'icon' => 'fa-bell-slash',
            'args' => [
                '' => 'Executar',
            ],
        ],
        'sqlmigration' => [
            'label' => 'Executar Migrations SQL',
            'description' => 'Executa os arquivos SQL pendentes na pasta de migrations.',
            'icon' => 'fa-database',
            'args' => [
                'run' => 'Executar migrations pendentes',
            ],
        ],
    ];

    public function filters()
    {
        return ['accessControl'];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['index', 'run', 'opcache', 'viewLogs'],
                'expression' => 'TagUtils::isSuperuser()',
            ],
            ['deny', 'users' => ['*']],
        ];
    }

    /**
     * Tela principal: lista os commands disponíveis e links de diagnóstico.
     */
    public function actionIndex()
    {
        $diagnostics = [
            [
                'name' => 'Visualizar Logs',
                'description' => 'Exibe as entradas de log da aplicação do dia atual.',
                'icon' => 'fa-file-text',
                'url' => Yii::app()->createUrl('tools/default/viewLogs'),
            ],
            [
                'name' => 'OPcache',
                'description' => 'Painel de monitoramento do cache de bytecode PHP (OPcache GUI).',
                'icon' => 'fa-tachometer',
                'url' => Yii::app()->createUrl('tools/default/opcache'),
            ],
        ];

        $this->render('index', [
            'commands' => $this->whitelist,
            'diagnostics' => $diagnostics,
        ]);
    }

    /**
     * Exibe o painel OPcache GUI.
     */
    public function actionOpcache()
    {
        $this->layout = 'reportsclean';
        $opcacheGuiPath = Yii::getPathOfAlias('application.extensions.opcache-gui') . '/index.php';

        if (!file_exists($opcacheGuiPath)) {
            throw new CHttpException(404, 'OPcache GUI não encontrado.');
        }

        require_once $opcacheGuiPath;
        Yii::app()->end();
    }

    /**
     * Exibe os logs da aplicação do dia atual com paginação.
     */
    public function actionViewLogs()
    {
        $logPath = '/app/app/runtime/' . INSTANCE . '/' . date('Y-m-d');
        $logFiles = glob($logPath . '/application.log*');
        $logs = [];

        rsort($logFiles);

        if (!empty($logFiles)) {
            foreach ($logFiles as $file) {
                array_unshift($logs, ['type' => 'header', 'file' => basename($file)]);

                $fileHandle = fopen($file, 'r');
                if ($fileHandle) {
                    while (($line = fgets($fileHandle)) !== false) {
                        array_unshift($logs, ['type' => 'log', 'line' => $line]);
                    }
                    fclose($fileHandle);
                }
            }
        }

        $dataProvider = new CArrayDataProvider($logs, [
            'id' => 'log',
            'pagination' => ['pageSize' => 50],
        ]);

        $this->render('viewLogs', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Executa um command e retorna o output via JSON (chamado via AJAX).
     */
    public function actionRun()
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(405, 'Método não permitido.');
        }

        $commandName = Yii::app()->request->getPost('command');
        $arg = Yii::app()->request->getPost('arg', '');

        // Valida command na whitelist
        if (!array_key_exists($commandName, $this->whitelist)) {
            throw new CHttpException(403, 'Command não permitido.');
        }

        // Valida arg na whitelist do command
        $allowedArgs = array_keys($this->whitelist[$commandName]['args']);
        if (!in_array($arg, $allowedArgs, true)) {
            throw new CHttpException(403, 'Argumento não permitido.');
        }

        $yiicPath = Yii::getPathOfAlias('application') . '/yiic';
        $fullCmd = escapeshellcmd("php {$yiicPath} {$commandName}");

        if (!empty($arg)) {
            $fullCmd .= ' ' . escapeshellarg($arg);
        }

        // Redireciona stderr para stdout para capturar tudo
        $fullCmd .= ' 2>&1';

        Yii::log(
            "Tools: superuser executou command [{$commandName} {$arg}]",
            CLogger::LEVEL_INFO,
            'application.tools'
        );

        $output = [];
        $exitCode = 0;
        exec($fullCmd, $output, $exitCode);

        header('Content-Type: application/json');
        echo CJSON::encode([
            'success' => $exitCode === 0,
            'exitCode' => $exitCode,
            'output' => implode("\n", $output),
            'command' => "{$commandName} {$arg}",
        ]);
        Yii::app()->end();
    }
}
