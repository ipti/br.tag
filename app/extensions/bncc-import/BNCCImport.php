<?php

include 'vendor/autoload.php';


class BNCCImport
{
    /**
     * Summary of importCSV
     * @return array
     */
    public function importCSVInfantil()
    {
        $csvData = $this->readCSV('/extensions/bncc-import/infantil.csv');
        $parsedData = $this->parseCSVDataInfantil($csvData);

        // !d($parsedData);

        foreach ($parsedData as $key => $value) {
            $discipline = $this->map_discipline($value["name"]);
            self::deep_create($value, null, $discipline, null);
        }

        return $parsedData;
    }

    public function importCSVFundamental()
    {
        $csvData = $this->readCSV('/extensions/bncc-import/matematica.csv');
        $parsedData = $this->parseCSVDataInfantil($csvData);

        // !d($parsedData);

        foreach ($parsedData as $key => $value) {
            $discipline = $this->map_discipline($value["name"]);
            self::deep_create($value, null, $discipline, null);
        }

        return $parsedData;
    }

    private function readCSV($csv_path)
    {
        $path = Yii::app()->basePath;
        $csvData = [];

        try {
            $filePath = $path . $csv_path;
            file_put_contents($filePath, str_replace("\xEF\xBB\xBF", "", file_get_contents($filePath)));
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

    private function parseCSVDataInfantil($csvData)
    {
        $parsedData = [];
        $currentCategory = '';
        $currentSubCategory = '';

        foreach ($csvData as $key => $line) {

            $category = trim($line[0]);
            $subcategory = trim($line[1]);
            $description = trim($line[2]);

            if (!empty($category)) {
                // Inicializar a categoria atual
                $currentCategory = $category;
                if (!isset($parsedData[$currentCategory])) {
                    $parsedData[$currentCategory] = [
                        'name' => $currentCategory,
                        'type' => "Campo de experiências",
                        'children' => []
                    ];
                }
                $currentSubCategory = '';
            }

            if (!empty($subcategory)) {
                $currentSubCategory = $subcategory;
                // Inicializar a subcategoria atual se ainda não existir
                $currentSubCategoryKey = $currentSubCategory;
                if (!isset($parsedData[$currentCategory]['children'][$currentSubCategoryKey])) {
                    $parsedData[$currentCategory]['children'][$currentSubCategoryKey] = ['name' => $currentSubCategory, 'type' => "Faixas Etárias", 'children' => []];
                }
            }

            if (!empty($description)) {
                // Adicionar a descrição à subcategoria atual
                $parsedData[$currentCategory]['children'][$currentSubCategory]['name'] = $currentSubCategory;
                $parsedData[$currentCategory]['children'][$currentSubCategory]['children'][] = ["name" => $description, 'type' => "Objetivos de aprendizagem e desenvolvimento"];
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

    private function getCode($abilitie)
    {
        $pattern = '/\(([A-Z0-9]{0,10})\)/'; // Expressão regular para capturar o texto entre parênteses
        preg_match($pattern, $abilitie, $matches);

        if (isset($matches[1])) {
            $result = $matches[1];
            return $result;
        }

        return null;
    }

    private function getText($abilitie)
    {
        $startPosInit = strpos($abilitie, '(');
        $startPos = strpos($abilitie, ')') + 1; // Encontra a posição após o parêntese fechado
        if ($startPos !== false && $startPosInit === 0) {
            $result = trim(substr($abilitie, $startPos)); // Extrai o texto após o parêntese fechado e remove espaços em branco
            return $result;
        }
        return $abilitie;
    }

    private function map_stage($stage)
    {
        $stages = [
            "Bebês (zero a 1 ano e 6 meses)" => 1,
            "Crianças bem pequenas (1 ano e 7 meses a 3 anos e 11 meses)" => 1,
            "Crianças pequenas (4 anos a 5 anos e 11 meses)" => 2,
        ];

        $result = null;

        if(array_key_exists($stage, $stages)) {
            $result = $stages[$stage];
        }

        return $result;
    }

    private function map_discipline($discipline_name)
    {
        $disciplines = [
            "Espaços, tempos, quantidades, relações e transformações"  => 10008,
            "Escuta, fala, pensamento e imaginação"  => 10007,
            "Corpo, gestos e movimentos"  => 10009,
            "O eu, o outro e o nós"  => 10011,
            "Traços, sons, cores e formas" => 10010
        ];

        return $disciplines[$discipline_name];
    }

    private function deep_create($abilities, $parent, $edcenso_discipline_fk, $edcenso_stage_vs_modality_fk)
    {
        $new_abilities = new CourseClassAbilities();

        $new_abilities->description = self::getText($abilities["name"]);
        $new_abilities->parent_fk = $parent;
        $new_abilities->type = $abilities["type"];
        $new_abilities->code = $this->getCode($abilities["name"]);
        $new_abilities->edcenso_discipline_fk = $edcenso_discipline_fk;
        if(is_null($edcenso_stage_vs_modality_fk)) {
            $edcenso_stage_vs_modality_fk = $this->map_stage($abilities["name"]);
        }
        $new_abilities->edcenso_stage_vs_modality_fk = $edcenso_stage_vs_modality_fk;

        if ($new_abilities->save()) {
            if(!empty($abilities["children"])) {
                foreach ($abilities["children"] as $child) {
                    $this->deep_create(
                        $child,
                        $new_abilities->id,
                        $edcenso_discipline_fk,
                        $new_abilities->edcenso_stage_vs_modality_fk
                    );
                }
            }
        } else {
            d($new_abilities);
        }
    }



}
