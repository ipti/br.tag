<?php

$inputDir = 'modules/sagres/soap/src2'; // Diretório de entrada com os arquivos gerados
$outputDir = 'modules/sagres/soap/src3'; // Diretório de saída com os arquivos convertidos

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($inputDir));

foreach ($files as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }

    $content = file_get_contents($file->getPathname());

    // Adiciona a linha "use JMS\Serializer\Annotation as Serializer;"
    if (!str_contains($content, 'use JMS\\Serializer\\Annotation as Serializer;')) {
        $content = preg_replace(
            '/namespace (.*?);/s',
            "namespace $1;\n\nuse JMS\\Serializer\\Annotation as Serializer;",
            $content
        );
    }

    // Para cada propriedade, adiciona as anotações do JMS
    $content = preg_replace_callback(
        '/\/\*\*\s+\*\s+@var\s+([^\s]+)\s+\*\s+\*\//',
        function ($matches) {
            $phpType = match ($matches[1]) {
                'string' => '?string',
                '\DateTime' => '?\DateTime',
                'int' => '?int',
                'bool' => '?bool',
                default => $matches[1],
            };

            $serializedName = strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($matches[1])));

            $annotations = <<<ANNOTATIONS
    #[Serializer\SerializedName("edu:$serializedName")]
    #[Serializer\XmlElement(cdata: false)]
ANNOTATIONS;

            if ($phpType === '?\DateTime') {
                $annotations .= PHP_EOL . '    #[Serializer\Type("DateTime<\'Y-m-d\'>")]';
            }

            return $annotations . PHP_EOL . "    private {$phpType} \${$serializedName} = null;";
        },
        $content
    );

    // Ajusta os métodos getters e setters
    $content = preg_replace(
        '/public function get([A-Z][a-zA-Z0-9]+)\(\)/',
        'public function get$1(): ?$2',
        $content
    );
    $content = preg_replace(
        '/public function set([A-Z][a-zA-Z0-9]+)\(\$([a-z][a-zA-Z0-9]+)\)/',
        'public function set$1(?$2 $2): self',
        $content
    );

    // Salva o arquivo convertido
    $outputFile = $outputDir . DIRECTORY_SEPARATOR . $file->getFilename();
    file_put_contents($outputFile, $content);
}

echo "Conversão concluída. Arquivos convertidos estão em $outputDir.\n";
