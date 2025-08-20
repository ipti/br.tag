<?php

use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\API\Trace\StatusCode;
use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\Context\Context;

/**
 * Decorator para CDbCommand que abre spans apenas na execução (execute/query*).
 * Mantém compatibilidade com a API do Yii encaminhando chamadas ao objeto real.
 */
class TracedDbCommand
{
    /** @var CDbCommand */
    private $wrapped;

    /** @var TracerInterface */
    private $tracer;

    /** @var CDbConnection */
    private $connection;

    public function __construct(CDbCommand $wrapped, TracerInterface $tracer, CDbConnection $connection)
    {
        $this->wrapped = $wrapped;
        $this->tracer = $tracer;
        $this->connection = $connection;
    }

    // ---- Encaminhadores básicos para manter compatibilidade ----

    public function getText()
    {
        return $this->wrapped->getText();
    }

    public function setText($value)
    {
        $this->wrapped->setText($value);
        return $this;
    }

    public function bindParam($name, &$value, $dataType = null, $length = null, $driverOptions = null)
    {
        $this->wrapped->bindParam($name, $value, $dataType, $length, $driverOptions);
        return $this;
    }

    public function bindValue($name, $value, $dataType = null)
    {
        $this->wrapped->bindValue($name, $value, $dataType);
        return $this;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function __get($name)
    {
        return $this->wrapped->$name;
    }

    public function __set($name, $value)
    {
        $this->wrapped->$name = $value;
    }

    // ---- Execuções com tracing ----

    public function execute($params = [])
    {
        return $this->runWithTrace('db.execute', function () use ($params) {
            return $this->wrapped->execute($params);
        });
    }

    public function queryAll($fetchAssociative = true, $params = [])
    {
        return $this->runWithTrace('db.queryAll', function () use ($fetchAssociative, $params) {
            return $this->wrapped->queryAll($fetchAssociative, $params);
        });
    }

    public function queryRow($fetchAssociative = true, $params = [])
    {
        return $this->runWithTrace('db.queryRow', function () use ($fetchAssociative, $params) {
            return $this->wrapped->queryRow($fetchAssociative, $params);
        });
    }

    public function queryScalar($params = [])
    {
        return $this->runWithTrace('db.queryScalar', function () use ($params) {
            return $this->wrapped->queryScalar($params);
        });
    }

    public function queryColumn($params = [])
    {
        return $this->runWithTrace('db.queryColumn', function () use ($params) {
            return $this->wrapped->queryColumn($params);
        });
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->wrapped, $method], $args);
    }

    // ---- Utilitário central de tracing ----

    private function runWithTrace(string $operation, callable $fn)
    {
        $sql = $this->wrapped->getText();

        if (!empty($sql)) {
            $dsn = $this->connection->connectionString ?? '';
            if (stripos($dsn, 'mysql:') === 0) {
                $parts = [];
                foreach (explode(';', substr($dsn, 6)) as $kv) {
                    if (strpos($kv, '=') !== false) {
                        [$k, $v] = explode('=', $kv, 2);
                        $parts[trim($k)] = trim($v);
                    }
                }
            }
        }
        return TracerManager::runInSpan($operation, $fn, [
            'db.system' => 'mysql',
            'db.operation'=> $operation,
            'db.statement'=>$sql,
            'net.peer.name'=> $parts['host'] ?? null,
            'net.peer.port'=>$parts['port'] ?? null,
            'db.name'=>$parts['dbname'] ?? null,
        ]);
    }
}
