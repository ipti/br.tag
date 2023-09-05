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

        // CVarDumper::dump($parsedData, 10, true);
        // exit;

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
        $parsedData = $this->parseCSVDataFundamental($csvData);

        // CVarDumper::dump($parsedData, 10, true);
        // exit;

        foreach ($parsedData as $key => $value) {
            $discipline = $this->map_discipline($value["name"]);
            self::deep_create($value, null, $discipline, null);
        }

        !d($parsedData["Matemática"]["children"]["Números"]);

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
            $stage = trim($line[1]);
            $description = trim($line[2]);

            if (!empty($category)) {
                // Inicializar a categoria atual
                $currentCategory = $category;
                if (!isset($parsedData[$currentCategory])) {
                    $parsedData[$currentCategory] = [
                        'name' => $currentCategory,
                        'type' => "Campo de experiências",
                        'stage' =>  $stage,
                        'children' => []
                    ];
                }
                $currentSubCategory = '';
            }

            if (!empty($stage)) {
                $currentSubCategory = $stage;
                // Inicializar a subcategoria atual se ainda não existir
                $currentSubCategoryKey = $currentSubCategory;
                if (!isset($parsedData[$currentCategory]['children'][$currentSubCategoryKey])) {
                    $parsedData[$currentCategory]['children'][$currentSubCategoryKey] = ['name' => $currentSubCategory, 'stage'=> $stage, 'type' => "Faixas Etárias", 'children' => []];
                }
            }

            if (!empty($description)) {
                // Adicionar a descrição à subcategoria atual
                $parsedData[$currentCategory]['children'][$currentSubCategory]['name'] = $currentSubCategory;
                $parsedData[$currentCategory]['children'][$currentSubCategory]['children'][] = ["name" => $description, 'stage'=> $stage, 'type' => "Objetivos de aprendizagem e desenvolvimento"];
            }
        }


        return $parsedData;
    }

    private function parseCSVDataFundamental($csvData)
    {
        $arrayDisciplines = ["Arte", "Educação Física", "Geografia", "História", "Ensino Religioso"];

        $parsedData = [];

        foreach ($csvData as $key => $line) {
            $disciplineName = $line[0];

            if (in_array($disciplineName, $arrayDisciplines)) { //ARTE - EDUCAÇÃO FÍSICA - GEOGRAFIA - HISTÓRIA - ENSINO RELIGIOSO
                $component = trim($line[0]);
                $stage = trim($line[1]);
                $skills = trim($line[2]);
                $unity = trim($line[3]);
                $objective = trim($line[4]);
                $abilitie = trim($line[5]);

                if (!empty($component)) {
                    // Inicializar a categoria atual
                    $currentCategory = $component;
                    if (!isset($parsedData[$currentCategory])) {
                        $parsedData[$currentCategory] = [
                            'name' => $currentCategory,
                            'type' => "COMPONENTE",
                            'stage' =>  $stage,
                            'children' => []
                        ];
                    }
                    $currentSubCategory = '';
                }

                // Competências específicas de componente
                if (!empty($skills)) {
                    if (!isset($parsedData[$component]['children'][$skills])) {
                        $parsedData[$component]['children'][$skills] = ['name' => $skills, 'type' => "COMPETÊNCIAS ESPECÍFICAS DE COMPONENTE", 'children' => []];
                    }
                }

                // Unidades temáticas
                if (!empty($fields)) {                                
                    $parsedData[$component]['children'][$skills]['children'][$unity] = ["name" => $unity, 'type' => "UNIDADE TEMÁTICA", 'children' => []];
                }

                // Objetos de conhecimento	
                if (!empty($objective)) {                                
                    $parsedData[$component]['children'][$skills]['children'][$unity]['children'][$objective] = ["name" => $objective, 'type' => "OBJETO DE CONHECIMENTO", 'children' => []];
                }

                // Habilidades
                if (!empty($abilitie)) {
                    $parsedData[$component]['children'][$skills]['children'][$unity]['children'][$objective]['children'][$abilitie]['children'][] = ["name" => $abilitie, 'type' => "HABILIDADE"];                
                }
            }else if ($disciplineName == "Matemática" || $disciplineName == "Ciências") { // Matematica e Ciencias
                $component = trim($line[0]);
                $stage = trim($line[1]);
                $unity = trim($line[2]);
                $objective = trim($line[3]);
                $abilitie = trim($line[4]);

                if (!empty($component)) {
                    // Inicializar a categoria atual
                    $currentCategory = $component;
                    if (!isset($parsedData[$currentCategory])) {
                        $parsedData[$currentCategory] = [
                            'name' => $currentCategory,
                            'type' => "COMPONENTE",
                            'stage' =>  $stage,
                            'children' => []
                        ];
                    }
                    $currentSubCategory = '';
                }

                if (!empty($unity)) {
                    if (!isset($parsedData[$component]['children'][$unity])) {
                        $parsedData[$component]['children'][$unity] = ['name' => $unity, 'type' => "UNIDADE TEMÁTICA", 'children' => []];
                    }
                }

                if (!empty($objective)) {                                
                    $parsedData[$component]['children'][$unity]['children'][$objective] = ["name" => $objective, 'type' => "OBJETO DE CONHECIMENTO", 'children' => []];
                }

                if (!empty($abilitie)) {
                    $parsedData[$component]['children'][$unity]['children'][$objective]['children'][$abilitie]['children'][] = ["name" => $abilitie, 'type' => "HABILIDADE"];                
                }
            }else if ($disciplineName == "Língua Portuguesa") { // Lingua Portuguesa
                $component = trim($line[0]);
                $stage = trim($line[1]);
                $skills = trim($line[2]);
                $fields = trim($line[3]);
                $practices = trim($line[4]);
                $objective = trim($line[5]);
                $abilitie = trim($line[6]);

                if (!empty($component)) {
                    // Inicializar a categoria atual
                    $currentCategory = $component;
                    if (!isset($parsedData[$currentCategory])) {
                        $parsedData[$currentCategory] = [
                            'name' => $currentCategory,
                            'type' => "COMPONENTE",
                            'stage' =>  $stage,
                            'children' => []
                        ];
                    }
                    $currentSubCategory = '';
                }

                // Competências específicas de componente
                if (!empty($skills)) {
                    if (!isset($parsedData[$component]['children'][$skills])) {
                        $parsedData[$component]['children'][$skills] = ['name' => $skills, 'type' => "COMPETÊNCIAS ESPECÍFICAS DE COMPONENTE", 'children' => []];
                    }
                }

                // Campos de atuação
                if (!empty($fields)) {                                
                    $parsedData[$component]['children'][$skills]['children'][$fields] = ["name" => $fields, 'type' => "CAMPOS DE ATUAÇÃO", 'children' => []];
                }

                // Práticas de linguagem
                if (!empty($practices)) {                                
                    $parsedData[$component]['children'][$skills]['children'][$fields]['children'][$practices] = ["name" => $practices, 'type' => "PRÁTICAS DE LINGUAGEM", 'children' => []];
                }

                // Objetos de conhecimento	
                if (!empty($objective)) {                                
                    $parsedData[$component]['children'][$skills]['children'][$fields]['children'][$practices]['children'][$objective] = ["name" => $objective, 'type' => "OBJETO DE CONHECIMENTO", 'children' => []];
                }

                // Habilidades
                if (!empty($abilitie)) {
                    $parsedData[$component]['children'][$skills]['children'][$fields]['children'][$practices]['children'][$objective]['children'][$abilitie]['children'][] = ["name" => $abilitie, 'type' => "HABILIDADE"];                
                }
            }else if ($disciplineName == "Língua Inglesa") {
                $component = trim($line[0]);
                $stage = trim($line[1]);
                $skills = trim($line[2]);
                $axle = trim($line[3]);
                $unity = trim($line[4]);
                $objective = trim($line[5]);
                $abilitie = trim($line[6]);

                if (!empty($component)) {
                    // Inicializar a categoria atual
                    $currentCategory = $component;
                    if (!isset($parsedData[$currentCategory])) {
                        $parsedData[$currentCategory] = [
                            'name' => $currentCategory,
                            'type' => "COMPONENTE",
                            'stage' =>  $stage,
                            'children' => []
                        ];
                    }
                    $currentSubCategory = '';
                }

                // Competências específicas de componente
                if (!empty($skills)) {
                    if (!isset($parsedData[$component]['children'][$skills])) {
                        $parsedData[$component]['children'][$skills] = ['name' => $skills, 'type' => "COMPETÊNCIAS ESPECÍFICAS DE COMPONENTE", 'children' => []];
                    }
                }

                // Eixo
                if (!empty($fields)) {                                
                    $parsedData[$component]['children'][$skills]['children'][$axle] = ["name" => $axle, 'type' => "EIXO", 'children' => []];
                }

                // Unidades temáticas
                if (!empty($fields)) {                                
                    $parsedData[$component]['children'][$skills]['children'][$axle]['children'][$unity] = ["name" => $unity, 'type' => "UNIDADE TEMÁTICA", 'children' => []];
                }

                // Objetos de conhecimento	
                if (!empty($objective)) {                                
                    $parsedData[$component]['children'][$skills]['children'][$axle]['children'][$unity]['children'][$objective] = ["name" => $objective, 'type' => "OBJETO DE CONHECIMENTO", 'children' => []];
                }

                // Habilidades
                if (!empty($abilitie)) {
                    $parsedData[$component]['children'][$skills]['children'][$axle]['children'][$unity]['children'][$objective]['children'][$abilitie]['children'][] = ["name" => $abilitie, 'type' => "HABILIDADE"];                
                }
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
            '1º' => 14,
            '2º' => 15,
            '3º' => 16,
            '4º' => 17,
            '5º' => 18,
            '6º' => 19,
            '7º' => 20,
            '8º' => 21,
            '9º' => 41
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
            "Espaços, tempos, quantidades, relações e transformações" => 10008,
            "Escuta, fala, pensamento e imaginação" => 10007,
            "Corpo, gestos e movimentos" => 10009,
            "O eu, o outro e o nós" => 10011,
            "Traços, sons, cores e formas" => 10010,
            "Matemática" => 3,
            "Ciências" => 5,
            "Língua Portuguesa" => 6,
            "Língua Inglesa" => 7,
            "Arte" => 10,
            "Educação Física" => 11,
            "História" => 12,
            "Geografia" => 13,
            "Ensino religioso" => 26
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
            $edcenso_stage_vs_modality_fk = $this->map_stage($abilities["stage"]);
            CVarDumper::dump($edcenso_stage_vs_modality_fk, 10, true);
        }
        $new_abilities->edcenso_stage_vs_modality_fk = $edcenso_stage_vs_modality_fk;

        CVarDumper::dump($abilities, 10, true);
        CVarDumper::dump($new_abilities, 10, true);

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