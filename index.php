<?php

// change the following paths if necessary
//$yii_app=dirname(__FILE__).'/app/vendor/autoload.php';
$autoload = dirname(__FILE__) . '/vendor/autoload.php';
$yii = dirname(__FILE__) . '/vendor/yiisoft/yii/framework/yii.php';

$instance = dirname(__FILE__) . '/instance.php';
$configtag = dirname(__FILE__) . '/config.php';
$config = dirname(__FILE__) . '/app/config/main.php';
$sagres = dirname(__FILE__) . '/app/modules/sagres/models/SagresConsultModel.php';

$escola_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/EscolaTType.php';
$turma_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/TurmaTType.php';
$serie_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/SerieTType.php';
$aluno_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/AlunoTType.php';
$atendimento_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/AtendimentoTType.php';
$educacao_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/EducacaoTType.php';
$horario_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/HorarioTType.php';
$matricula_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/MatriculaTType.php';
$profissional_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/ProfissionalTType.php';
$cardapio_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/CardapioTType.php';
$diretor_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/DiretorTType.php';
$cabecalho_t = dirname(__FILE__) . '/app/modules/sagres/soap/src/sagresEdu/CabecalhoTType.php';

require_once $yii;
require_once $autoload;
require_once $configtag;
require_once $instance;

require_once $sagres;
require_once $aluno_t;
require_once $atendimento_t;
require_once $cabecalho_t;
require_once $cardapio_t;
require_once $diretor_t;
require_once $educacao_t;
require_once $escola_t;
require_once $horario_t;
require_once $matricula_t;
require_once $profissional_t;
require_once $serie_t;
require_once $turma_t;

require_once dirname(__FILE__) . '/app/components/FeaturesComponent.php';
require_once dirname(__FILE__) . '/app/components/TracerManager.php';

use OpenTelemetry\SDK\Trace\TracerProviderBuilder;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\SDK\Trace\Sampler\ParentBased;
use OpenTelemetry\SDK\Trace\Sampler\TraceIdRatioBasedSampler;

// Setup OTEL Tracer
$transport = (new OtlpHttpTransportFactory())->create(
    'http://otel-collector:4318/v1/traces',
    'application/json'
);
$exporter = new SpanExporter($transport);

$processor = new SimpleSpanProcessor($exporter);
$tracerProvider = (new TracerProviderBuilder())
    ->addSpanProcessor($processor)
    ->setSampler(new ParentBased(new TraceIdRatioBasedSampler(0.5)))
    ->build();
$tracer = $tracerProvider->getTracer('yii-app');

TracerManager::init($tracer, $processor);

TracerManager::runInSpan(INSTANCE . ' - ' . $_SERVER['REQUEST_METHOD'] . ' - ' . $_SERVER['REQUEST_URI'] ?? 'request', function ($span) use ($config) {
    $app = Yii::createWebApplication($config);

    // DB
    $app->db->setTracer(TracerManager::getTracer());

    $span->addEvent('request_start');
    $app->run();

    $span->addEvent('request_end');
}, [
    'http.method' => $_SERVER['REQUEST_METHOD'],
    'http.uri' => $_SERVER['REQUEST_URI'],

]);


TracerManager::shutdown();
