<?php

class SimpleExcelExporter
{
    private string $filename;
    private array $headers = [];
    private array $rows = [];

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function addRow(array $row): void
    {
        $this->rows[] = $row;
    }

    public function export(): void
    {
        // Criar arquivos XML internos
        $files = [
            '[Content_Types].xml' => $this->contentTypesXml(),
            '_rels/.rels' => $this->relsXml(),
            'xl/workbook.xml' => $this->workbookXml(),
            'xl/_rels/workbook.xml.rels' => $this->workbookRelsXml(),
            'xl/worksheets/sheet1.xml' => $this->worksheetXml(),
        ];

        // Criar ZIP
        $zip = new ZipArchive();
        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip->open($tmpFile, ZipArchive::OVERWRITE);

        foreach ($files as $path => $content) {
            $zip->addFromString($path, $content);
        }

        $zip->close();

        // Enviar para o browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $this->filename . '"');
        readfile($tmpFile);
        unlink($tmpFile);
        exit;
    }

    private function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
    <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
</Types>';
    }

    private function relsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';
    }

    private function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"
          xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <sheets>
        <sheet name="Sheet1" sheetId="1" r:id="rId1"/>
    </sheets>
</workbook>';
    }

    private function workbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
</Relationships>';
    }

    private function worksheetXml(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
    <sheetData>';

        $rows = [];
        if (!empty($this->headers)) {
            $rows[] = $this->arrayToRowXml($this->headers);
        }

        foreach ($this->rows as $row) {
            $rows[] = $this->arrayToRowXml($row);
        }

        $xml .= implode("\n", $rows);

        $xml .= '</sheetData></worksheet>';

        return $xml;
    }

    private function arrayToRowXml(array $row): string
    {
        $cells = [];
        foreach ($row as $cell) {
            $cells[] = '<c t="inlineStr"><is><t>' . htmlspecialchars($cell) . '</t></is></c>';
        }

        return '<row>' . implode('', $cells) . '</row>';
    }
}
