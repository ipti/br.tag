<?php

/**
 * Gerencia as credenciais dos clientes da API (app/modules/api).
 *
 * Cada município tem o seu banco, então todo comando recebe --db com o nome
 * do banco do município (ex.: santaluzia.tag.ong.br).
 *
 * Uso:
 *   php yiic apiclient create --db=santaluzia.tag.ong.br --school=28012345 --name="Raspberry Escola X"
 *   php yiic apiclient list --db=santaluzia.tag.ong.br
 *   php yiic apiclient deactivate --db=santaluzia.tag.ong.br --client=tag_abc123
 *   php yiic apiclient activate --db=santaluzia.tag.ong.br --client=tag_abc123
 *
 * O client_secret aparece uma única vez, no create. No banco fica só o hash;
 * se for perdido, crie um cliente novo e desative o antigo.
 */
class ApiClientCommand extends CConsoleCommand
{
    public function init()
    {
        parent::init();
        Yii::import('application.modules.api.models.*');
    }

    public function actionCreate($db, $school, $name)
    {
        $this->useDatabase($db);

        $schoolModel = SchoolIdentification::model()->findByPk($school);
        if ($schoolModel === null) {
            echo "Erro: escola com INEP {$school} não encontrada em {$db}.\n";
            return 1;
        }

        [$client, $secret] = ApiClient::createWithSecret($name, $schoolModel->inep_id);
        if (!$client->save()) {
            echo "Erro ao criar o cliente:\n" . print_r($client->getErrors(), true);
            return 1;
        }

        echo "Cliente criado em {$db}\n";
        echo "  Escola:        {$schoolModel->inep_id} - {$schoolModel->name}\n";
        echo "  Nome:          {$client->name}\n";
        echo "  client_id:     {$client->client_id}\n";
        echo "  client_secret: {$secret}\n\n";
        echo "Guarde o client_secret agora: ele não fica salvo em texto e não aparece de novo.\n";
        return 0;
    }

    public function actionList($db)
    {
        $this->useDatabase($db);

        $clients = ApiClient::model()->findAll(['order' => 'school_inep_fk, name']);
        if (empty($clients)) {
            echo "Nenhum cliente da API em {$db}.\n";
            return 0;
        }

        foreach ($clients as $client) {
            printf(
                "%-30s %-8s %-8s %-19s %s\n",
                $client->client_id,
                $client->school_inep_fk ?? '-',
                $client->active ? 'ativo' : 'inativo',
                $client->last_used_at ?? 'nunca usado',
                $client->name
            );
        }
        return 0;
    }

    public function actionDeactivate($db, $client)
    {
        return $this->setActive($db, $client, false);
    }

    public function actionActivate($db, $client)
    {
        return $this->setActive($db, $client, true);
    }

    private function setActive(string $db, string $clientId, bool $active): int
    {
        $this->useDatabase($db);

        $client = ApiClient::model()->findByAttributes(['client_id' => $clientId]);
        if ($client === null) {
            echo "Erro: cliente {$clientId} não encontrado em {$db}.\n";
            return 1;
        }

        $client->active = $active ? 1 : 0;
        $client->save(false, ['active']);
        echo 'Cliente ' . $clientId . ($active ? ' ativado' : ' desativado') . " em {$db}.\n";
        return 0;
    }

    /**
     * Troca a conexão padrão do console pelo banco do município informado,
     * com o mesmo host e usuário da configuração.
     */
    private function useDatabase(string $dbName): void
    {
        if (!preg_match('/^[A-Za-z0-9._-]+$/', $dbName)) {
            throw new CException('Nome de banco inválido: ' . $dbName);
        }

        $current = Yii::app()->db;
        preg_match('/host=([^;]+)/', $current->connectionString, $matches);
        $host = $matches[1] ?? 'localhost';

        $connection = new CDbConnection("mysql:host={$host};dbname={$dbName}", $current->username, $current->password);
        $connection->charset = 'utf8mb4';
        $connection->emulatePrepare = true;
        $connection->active = true;

        Yii::app()->setComponent('db', $connection);
        CActiveRecord::$db = $connection;
    }
}
