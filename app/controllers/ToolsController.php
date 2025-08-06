<?php

class ToolsController extends Controller
{
    public $layout = 'fullmenu';

    public function actionIndex()
    {
        $tools = [
            ['name' => 'Visualizar Logs', 'url' => Yii::app()->createUrl('tools/viewLogs')],
            ['name' => 'OpCache', 'url' => Yii::app()->createUrl('tools/opcache')],
        ];

        $this->render('index', [
            'tools' => $tools,
        ]);
    }

    public function actionOpcache()
    {
        $this->layout = 'reportsclean';
        // Caminho completo para o OPcache GUI
        $opcacheGuiPath = Yii::getPathOfAlias('application.extensions.opcache-gui').'/index.php';
        // Verifica se o arquivo existe
        if (!file_exists($opcacheGuiPath)) {
            throw new CHttpException(404, 'OPcache GUI não encontrado.');
        }

        // Inclui o arquivo do OPcache GUI
        require_once $opcacheGuiPath;

        Yii::app()->end(); // Finaliza o processamento Yii após exibir o OPcache GUI
    }

    public function actionViewLogs()
    {
        // Caminho do diretório onde os logs são armazenados
        $logPath = '/app/app/runtime/'.INSTANCE.'/'.date('Y-m-d');
        $logFiles = glob($logPath.'/application.log*'); // Encontra todos os arquivos de log
        $logs = [];

        // Ordena os arquivos para garantir que os mais recentes sejam processados primeiro
        rsort($logFiles); // Ordena os arquivos de forma decrescente (do mais recente para o mais antigo)

        if (!empty($logFiles)) {
            foreach ($logFiles as $file) {
                // Adiciona o cabeçalho com o nome do arquivo
                array_unshift($logs, ['type' => 'header', 'file' => basename($file)]); // Coloca no início

                // Abre o arquivo e lê linha por linha
                $fileHandle = fopen($file, 'r');
                if ($fileHandle) {
                    while (($line = fgets($fileHandle)) !== false) {
                        array_unshift($logs, ['type' => 'log', 'line' => $line]); // Coloca no início
                    }
                    fclose($fileHandle);
                }
            }
        }

        $dataProvider = new CArrayDataProvider($logs, [
            'id' => 'log',
            'pagination' => [
                'pageSize' => 50, // Exibir 50 linhas por página
            ],
        ]);

        $this->render('viewLogs', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
