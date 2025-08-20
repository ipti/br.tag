<?php

/**
 * Substitui o componente DB do Yii por uma conexão que retorna comandos
 * decorados com tracing OpenTelemetry.
 */
class TracedDbConnection extends CDbConnection
{
    /** @var \OpenTelemetry\API\Trace\TracerInterface|null */
    private $tracer;

    /**
     * Injeta o tracer criado no bootstrap (index.php).
     * @param \OpenTelemetry\API\Trace\TracerInterface $tracer
     */
    public function setTracer($tracer)
    {
        $this->tracer = $tracer;
    }

    /**
     * Cria um CDbCommand e o embrulha num decorator que instrumenta as execuções.
     * Não abre spans aqui, apenas retorna o wrapper.
     *
     * @param string|null $query
     * @return TracedDbCommand|CDbCommand
     */
    public function createCommand($query = null)
    {
        $cmd = parent::createCommand($query);

        if ($this->tracer instanceof \OpenTelemetry\API\Trace\TracerInterface) {
            return new TracedDbCommand($cmd, $this->tracer, $this);
        }

        return $cmd;
    }
}
