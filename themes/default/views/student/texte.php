Agora nesse código abaixo:
public function getStudentCertificate($enrollment_id): array
    {
        $studentIdent = StudentIdentification::model()->findByPk($enrollment_id);
    
        if (!$studentIdent) {
            return array("student" => null);
        }
    
        $city = null;
        $uf_acronym = null;
        $uf_name = null;
    
        $cityObj = EdcensoCity::model()->findByPk($studentIdent->edcenso_city_fk);
    
        if ($cityObj) {
            $city = $cityObj->name;
    
            // Busca a UF (estado) usando o edcenso_uf_fk da cidade
            $ufObj = EdcensoUf::model()->findByPk($cityObj->edcenso_uf_fk);
    
            if ($ufObj) {
                // Verifica se o ID da UF encontrado é igual ao ID da UF da cidade do estudante
                if ($ufObj->id == $cityObj->edcenso_uf_fk) {
                    $uf_acronym = $ufObj->acronym;
                    $uf_name = $ufObj->name;
                }
            }
        }
    
        $studentData = array(
            'name' => $studentIdent->name,
            'civil_name' => $studentIdent->civil_name,
            'birthday' => $studentIdent->birthday,
            'sex' => $studentIdent->sex,
            'color_race' => $studentIdent->color_race,
            'filiation' => $studentIdent->filiation,
            'filiation_1' => $studentIdent->filiation_1,
            'filiation_2' => $studentIdent->filiation_2,
            'city' => $city,
            'uf_acronym' => $uf_acronym,
            'uf_name' => $uf_name,
        );
    
        return array("student" => $studentData);
    }
    

    