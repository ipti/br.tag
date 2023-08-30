<?php

class FundamentalDisciplinesMap
{
    /**
     * LÍNGUA PORTUGUESA
     * Colunas do CSV:
     * Competências específicas de componente
     * Campos de atuação
     * Práticas de linguagem
     * Objetos de conhecimento	
     * @return array
     */
    public static function parsePortugueseLanguageData($line)
    {
        $parsedData = [];

        $component = trim($line[0]);
        $skills = trim($line[1]);
        $fields = trim($line[2]);
        $practices = trim($line[3]);
        $objective = trim($line[4]);
        $abilitie = trim($line[5]);

        if (!empty($component)) {
            // Inicializar a categoria atual
            $currentCategory = $component;
            if (!isset($parsedData[$currentCategory])) {
                $parsedData[$currentCategory] = [
                    'name' => $currentCategory,
                    'type' => "COMPONENTE",
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

        return $parsedData;
    }

    /**
     * LÍNGUA INGLESA
     * Colunas do CSV:
     * Competências específicas de componente
     * Eixo
     * Unidades temáticas
     * Objetos de conhecimento
     * Habilidades
     * @return array
     */
    public static function parseEnglishLanguage($line)
    {
        $parsedData = [];

        $component = trim($line[0]);
        $skills = trim($line[1]);
        $axle = trim($line[2]);
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

        return $parsedData;
    }

    /**
     * ARTE - EDUCAÇÃO FÍSICA - GEOGRAFIA
     * HISTÓRIA - ENSINO RELIGIOSO
     * Colunas do CSV:
     * Competências específicas de componente
     * Unidades temáticas
     * Objetos de conhecimento
     * Habilidades
     * @return array
     */
    public static function parseArtEducationPhysicalHumanitiesAndReligion($line)
    {
        $parsedData = [];

        $component = trim($line[0]);
        $skills = trim($line[1]);
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

        return $parsedData;
    }


    /**
     * MATEMÁTICA
     * Colunas do CSV:
     * Unidades temáticas	
     * Objetos de conhecimento	
     * Práticas de linguagem
     * Habilidades	
     * @return array
     */
    public static function parseMathemathicAndSciencesData($line)
    {
        $parsedData = [];

        $component = trim($line[0]);
        $unity = trim($line[1]);
        $objective = trim($line[2]);
        $abilitie = trim($line[3]);

        if (!empty($component)) {
            // Inicializar a categoria atual
            $currentCategory = $component;
            if (!isset($parsedData[$currentCategory])) {
                $parsedData[$currentCategory] = [
                    'name' => $currentCategory,
                    'type' => "COMPONENTE",
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

        return $parsedData;
    }
}

?>