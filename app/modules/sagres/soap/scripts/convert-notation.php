<?php

$inputDir = 'modules/sagres/soap/src2'; // Diretório onde estão as classes PHP geradas
$outputDir = 'modules/sagres/soap/src2_converted'; // Diretório para salvar as classes convertidas

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($inputDir));

foreach ($files as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }

    $content = file_get_contents($file->getPathname());

    // Converte @Serializer\SerializedName para #[Serializer\SerializedName]
    $content = preg_replace(
        '/\*\s+@Serializer\\\SerializedName\("([^"]+)"\)/',
        '    #[Serializer\SerializedName("$1")]',
        $content
    );

    // Converte @Serializer\XmlElement para #[Serializer\XmlElement]
    $content = preg_replace(
        '/\*\s+@Serializer\\\XmlElement\(cdata=(true|false)\)/',
        '    #[Serializer\XmlElement(cdata=$1)]',
        $content
    );

    // Converte @Serializer\Type para #[Serializer\Type]
    $content = preg_replace(
        '/\*\s+@Serializer\\\Type\("([^"]+)"\)/',
        '    #[Serializer\Type("$1")]',
        $content
    );

    // Remove comentários de docblock que ficaram vazios
    $content = preg_replace('/\/\*\*\s+\*\s+\*\//', '', $content);

    // Salva o arquivo convertido
    $outputFile = $outputDir . DIRECTORY_SEPARATOR . $file->getFilename();
    file_put_contents($outputFile, $content);
}

echo "Conversão concluída. Classes convertidas estão em: $outputDir\n";
