<?php

include 'vendor/autoload.php';


class BNCCImport
{
    public const CSV_PATH = '/extensions/bncc-import/aaaaaaa.csv';

    /**
     * Summary of importCSV
     * @return array
     */
    public function importCSV()
    {
        $csvData = $this->readCSV();
        $parsedData = $this->parseCSVData($csvData);

        return $parsedData;
    }

    private function readCSV()
    {
        $path = Yii::app()->basePath;
        $csvData = [];

        try {
            $filePath = $path . self::CSV_PATH;
            $file = fopen($filePath, 'r');

            if ($file !== false) {
                while (($data = fgetcsv($file, 1000, ";")) !== false) {
                    $csvData[] = $data;
                }

                fclose($file);
            } else {
                throw new Exception("Failed to open CSV file.");
            }
        } catch (Exception $e) {
            // Handle the exception, log or display an error message.
            echo "Error: " . $e->getMessage();
        }

        return $csvData;
    }

    private function parseCSVData($csvData)
    {
        $parsedData = [];
        $currentCategory = '';
        $currentSubCategory = '';

        foreach ($csvData as $line) {
            $category = trim($line[0]);
            $subcategory = trim($line[1]);
            $description = trim($line[2]);
    
            if (!empty($category)) {
                // Inicializar a categoria atual
                $currentCategory = $category;
                if (!isset($parsedData[$this->normalizeKey($currentCategory)])) {
                    $parsedData[$this->normalizeKey($currentCategory)] = ['name' => $currentCategory, 'subcategories' => []];
                }
                $currentSubCategory = '';
            }
    
            if (!empty($subcategory)) {
                $currentSubCategory = $subcategory;
                // Inicializar a subcategoria atual se ainda não existir
                $currentSubCategoryKey = $this->normalizeKey($currentSubCategory);
                if (!isset($parsedData[$this->normalizeKey($currentCategory)]['faixa_etaria'][$currentSubCategoryKey])) {
                    $parsedData[$this->normalizeKey($currentCategory)]['faixa_etaria'][$currentSubCategoryKey] = ['name' => $currentSubCategory, 'items' => []];
                }
            }
    
            if (!empty($description)) {
                // Adicionar a descrição à subcategoria atual
                $parsedData[$this->normalizeKey($currentCategory)]['faixa_etaria'][$this->normalizeKey($currentSubCategory)]['name'] = $currentSubCategory;
                $parsedData[$this->normalizeKey($currentCategory)]['faixa_etaria'][$this->normalizeKey($currentSubCategory)]['items'][] = $description;
            }
        }
    

        return $parsedData;
    }

    private function normalizeKey($key)
    {
        // Remove accents and special characters.
        $normalizedKey = preg_replace('/[^a-zA-Z0-9\s]/', '', $key);
        return strtolower(str_replace(' ', '', $normalizedKey));
    }
    
    private function deep_create($abilities){
        if(!empty($abilities["children"])){
            $this->deep_create($abilities["children"]);
        }
    }

}
