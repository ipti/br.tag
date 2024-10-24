<?php

declare(strict_types=1);

use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Config\RectorConfig;
use Rector\Php80\ValueObject\AnnotationToAttribute;

return static function (RectorConfig $rectorConfig): void {
    // Define os caminhos onde o Rector irá atuar
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/tests',
        __DIR__ . '/themes',
    ]);

    // Converte as anotações para atributos
    // Aplica a regra de conversão de anotações para atributos
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute('JMS\Serializer\Annotation\Type'),
        new AnnotationToAttribute('JMS\Serializer\Annotation\SerializedName'),
        new AnnotationToAttribute('JMS\Serializer\Annotation\XmlElement'),
    ]);
};
