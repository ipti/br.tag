<?php

include_once 'vendor/autoload.php';


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

        foreach ($parsedData as $value) {
            $discipline = $this->mapDiscipline($value["name"]);
            self::deepCreate($value, null, $discipline, null);
        }

        return $parsedData;
    }

    public function importCSVFundamental($discipline)
    {
        $csvData = $this->readCSV('/extensions/bncc-import/' . $discipline . '.csv');
        $parsedData = $this->parseCSVDataFundamental($csvData);

        foreach ($parsedData as $value) {
            $discipline = $this->mapDiscipline($value["name"]);
            self::deepCreate($value, null, $discipline, null);
        }

        return $parsedData;
    }

    private function readCSV($csvPath)
    {
        $path = Yii::app()->basePath;
        $csvData = [];

        try {
            $filePath = $path . $csvPath;
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

        foreach ($csvData as $line) {

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
                        'stage' => $stage,
                        'children' => []
                    ];
                }
                $currentSubCategory = '';
            }

            if (!empty($stage)) {
                $currentSubCategory = $stage;
                // Inicializar a subcategoria atual se ainda não existir
                if (!isset($parsedData[$currentCategory]['children'][$currentSubCategory])) {
                    $parsedData[$currentCategory]['children'][$currentSubCategory] = ['name' => $currentSubCategory, 'stage' => $stage, 'type' => "Faixas Etárias", 'children' => []];
                }
            }

            if (!empty($description)) {
                // Adicionar a descrição à subcategoria atual
                $parsedData[$currentCategory]['children'][$currentSubCategory]['name'] = $currentSubCategory;

                $parsedData[$currentCategory]['children'][$currentSubCategory]['children'][] = ["name" => $description, 'stage' => $stage, 'type' => "Objetivos de aprendizagem e desenvolvimento"];
            }
        }


        return $parsedData;
    }

    private function parseCSVDataFundamental($csvData)
    {
        $arrayDisciplines = ["Arte", "Educação Física", "Geografia", "História", "Ensino Religioso"];

        $parsedData = [];

        foreach ($csvData as $line) {
            $disciplineName = $line[0];

            if (in_array($disciplineName, $arrayDisciplines)) { //ARTE - EDUCAÇÃO FÍSICA - GEOGRAFIA - HISTÓRIA - ENSINO RELIGIOSO OK
                $component = trim($line[0]);
                $stages = explode(';', trim($line[1]));
                $unity = trim($line[2]);
                $objective = trim($line[3]);
                $abilitie = trim($line[4]);

                foreach ($stages as $stage) {
                    if (!empty($component)) {
                        // Inicializar a categoria atual
                        $currentCategory = $component;
                        if (!isset($parsedData[$currentCategory])) {
                            $parsedData[$currentCategory] = [
                                'name' => $currentCategory,
                                'type' => "COMPONENTE",
                                'stage' => $stage,
                                'children' => []
                            ];
                        }
                    }

                    // UNIDADE TEMÁTICA
                    if (!empty($unity)) {
                        if (!isset($parsedData[$component]['children'][$unity])) {
                            $parsedData[$component]['children'][$unity] = ['name' => $unity, 'type' => "UNIDADE TEMÁTICA", 'children' => []];
                        }
                    }

                    // Objetos de conhecimento	
                    if (!empty($objective) && !isset($parsedData[$component]['children'][$unity]['children'][$objective])) {
                        $parsedData[$component]['children'][$unity]['children'][$objective] = ["name" => $objective, 'type' => "OBJETO DE CONHECIMENTO", 'children' => []];
                    }

                    // Habilidades
                    if (!empty($abilitie)) {
                        $parsedData[$component]['children'][$unity]['children'][$objective]['children'][$abilitie] = ["name" => $abilitie, 'type' => "HABILIDADE"];
                    }
                }

            } else if ($disciplineName == "Matemática" || $disciplineName == "Ciências") { // Matematica e Ciencias
                $component = trim($line[0]);
                $stages = explode(';', trim($line[1]));
                $unity = trim($line[2]);
                $objective = trim($line[3]);
                $abilitie = trim($line[4]);

                foreach ($stages as $stage) {
                    if (!empty($component)) {
                        // Inicializar a categoria atual
                        $currentCategory = $component;
                        if (!isset($parsedData[$currentCategory])) {
                            $parsedData[$currentCategory] = [
                                'name' => $currentCategory,
                                'type' => "COMPONENTE",
                                'stage' => $stage,
                                'children' => []
                            ];
                        }
                    }

                    if (!empty($unity) && !isset($parsedData[$component]['children'][$unity])) {
                        $parsedData[$component]['children'][$unity] = ['name' => $unity, 'type' => "UNIDADE TEMÁTICA", 'children' => []];
                    }

                    if (!empty($objective) && !isset($parsedData[$component]['children'][$unity]['children'][$objective])) {
                        $parsedData[$component]['children'][$unity]['children'][$objective] = ["name" => $objective, 'type' => "OBJETO DE CONHECIMENTO", 'children' => []];
                    }

                    if (!empty($abilitie)) {
                        $parsedData[$component]['children'][$unity]['children'][$objective]['children'][$abilitie] = ["name" => $abilitie, 'type' => "HABILIDADE"];
                    }
                }

            } else if ($disciplineName == "Língua Portuguesa") { // Lingua Portuguesa OK
                $component = trim($line[0]);
                $stages = explode(';', trim($line[1]));
                $fields = trim($line[2]);
                $practices = trim($line[3]);
                $objective = trim($line[4]);
                $abilitie = trim($line[5]);

                foreach ($stages as $stage) {
                    if (!empty($component)) {
                        // Inicializar a categoria atual
                        $currentCategory = $component;
                        if (!isset($parsedData[$currentCategory])) {
                            $parsedData[$currentCategory] = [
                                'name' => $currentCategory,
                                'type' => "COMPONENTE",
                                'stage' => $stage,
                                'children' => []
                            ];
                        }
                    }

                    // Campos de Atuação
                    if (!empty($fields)) {
                        if (!isset($parsedData[$component]['children'][$fields])) {
                            $parsedData[$component]['children'][$fields] = ['name' => $fields, 'type' => "CAMPOS DE ATUAÇÃO", 'children' => []];
                        }
                    }

                    // Práticas de linguagem
                    if (!empty($practices) && !isset($parsedData[$component]['children'][$fields]['children'][$practices])) {
                        $parsedData[$component]['children'][$fields]['children'][$practices] = ["name" => $practices, 'type' => "PRÁTICAS DE LINGUAGEM", 'children' => []];
                    }

                    // Objetos de conhecimento	
                    if (!empty($objective) && !isset($parsedData[$component]['children'][$fields]['children'][$practices]['children'][$objective])) {
                        $parsedData[$component]['children'][$fields]['children'][$practices]['children'][$objective] = ["name" => $objective, 'type' => "OBJETO DE CONHECIMENTO", 'children' => []];
                    }

                    // Habilidades
                    if (!empty($abilitie)) {
                        $parsedData[$component]['children'][$fields]['children'][$practices]['children'][$objective]['children'][$abilitie] = ["name" => $abilitie, 'type' => "HABILIDADE"];
                    }
                }

            } else if ($disciplineName == "Língua Inglesa") {
                $component = trim($line[0]);
                $stages = explode(';', trim($line[1]));
                $axle = trim($line[2]);
                $unity = trim($line[3]);
                $objective = trim($line[4]);
                $abilitie = trim($line[5]);

                foreach ($stages as $stage) {
                    if (!empty($component)) {
                        // Inicializar a categoria atual
                        $currentCategory = $component;
                        if (!isset($parsedData[$currentCategory])) {
                            $parsedData[$currentCategory] = [
                                'name' => $currentCategory,
                                'type' => "COMPONENTE",
                                'stage' => $stage,
                                'children' => []
                            ];
                        }
                    }

                    // Eixo
                    if (!empty($axle)) {
                        if (!isset($parsedData[$component]['children'][$axle])) {
                            $parsedData[$component]['children'][$axle] = ['name' => $axle, 'type' => "EIXO", 'children' => []];
                        }
                    }

                    // Unidades temáticas
                    if (!empty($unity)  && !isset($parsedData[$component]['children'][$axle]['children'][$unity])) {
                        $parsedData[$component]['children'][$axle]['children'][$unity] = ["name" => $unity, 'type' => "UNIDADE TEMÁTICA", 'children' => []];
                    }

                    // Objetos de conhecimento	
                    if (!empty($objective) && !isset($parsedData[$component]['children'][$axle]['children'][$unity]['children'][$objective])) {
                        $parsedData[$component]['children'][$axle]['children'][$unity]['children'][$objective] = ["name" => $objective, 'type' => "OBJETO DE CONHECIMENTO", 'children' => []];
                    }

                    // Habilidades
                    if (!empty($abilitie)) {
                        $parsedData[$component]['children'][$axle]['children'][$unity]['children'][$objective]['children'][$abilitie] = ["name" => $abilitie, 'type' => "HABILIDADE"];
                    }
                }

            }

        }
        return $parsedData;
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

    private function mapStage($stage)
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

        if (array_key_exists($stage, $stages)) {
            $result = $stages[$stage];
        }

        return $result;
    }

    private function mapDiscipline($disciplineName)
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
            "Ensino Religioso" => 26
        ];

        return $disciplines[$disciplineName];
    }

    private function deepCreate($abilities, $parent, $edcensoDisciplineFk, $edcensoStageFk)
    {
        $newAbilities = new CourseClassAbilities();

        $newAbilities->description = self::getText($abilities["name"]);
        $newAbilities->parent_fk = $parent;
        $newAbilities->type = $abilities["type"];
        $newAbilities->code = $this->getCode($abilities["name"]);
        $newAbilities->edcenso_discipline_fk = $edcensoDisciplineFk;
        if (is_null($edcensoStageFk)) {
            $edcensoStageFk = $this->mapStage($abilities["stage"]);
        }
        $newAbilities->edcenso_stage_vs_modality_fk = $edcensoStageFk;
        try {
            if ($newAbilities->save()) {
                if (!empty($abilities["children"])) {
                    foreach ($abilities["children"] as $child) {
                        $this->deepCreate(
                            $child,
                            $newAbilities->id,
                            $newAbilities->edcenso_discipline_fk ,
                            $newAbilities->edcenso_stage_vs_modality_fk
                        );
                    }
                }
            } else {
                Yii::log($newAbilities);
            }
        } catch (\Exception $e) {
            Yii::log($newAbilities);
            echo $e;
        }
        
    }

}