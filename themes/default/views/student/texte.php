Preciso que você observe esses dois códigos abaixo, para responder minhas próximas perguntas:

código 01:

public function getStudentCertificate ($enrollment_id): array
    {
        $studentIdent = StudentIdentification::model()->findByPk($enrollment_id);
        
        if (!$studentIdent) {
            return ["student" => null];
        }

        $city = null;
        $uf_acronym = null;
        $uf_name = null;
        $class_name = null;
        $tipo_ensino = '';
        $ano = '';

        if ($cityObj = EdcensoCity::model()->findByPk($studentIdent->edcenso_city_fk)) {
            $city = $cityObj->name;
            if ($ufObj = EdcensoUf::model()->findByPk($cityObj->edcenso_uf_fk)) {
                $uf_acronym = $ufObj->acronym;
                $uf_name = $ufObj->name;
            }
        }

        $command = Yii::app()->db->createCommand("
            SELECT c.name, esv.name as etapa
            FROM classroom c
            JOIN student_enrollment se ON c.id = se.classroom_fk
            JOIN edcenso_stage_vs_modality esv ON c.edcenso_stage_vs_modality_fk = esv.id
            WHERE se.student_fk = :student_fk AND se.status = 1
            ORDER BY se.id DESC
            LIMIT 1
        ");
        $command->bindValue(':student_fk', $enrollment_id);
        $row = $command->queryRow();
        if ($row) {
            $class_name = $row['name'];
            $etapa = $row['etapa'];

            $etapa_parts = explode(' - ', $etapa);

            if (count($etapa_parts) == 2) {
                $tipo_ensino = $etapa_parts[0];
                $ano = $etapa_parts[1];
            }
        }

        $studentData = [
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
            'class_name' => $class_name,
            'tipo_ensino' => $tipo_ensino,
            'ano' => $ano,
        ];
        return ["student" => $studentData];
    }

como fazer para criar uma variável que vai até o banco StudentEnrollment::model() e verificar algo parecidso com esse comando sql: 
SELECT MAX(id) AS max_id  FROM student_enrollment  WHERE student_fk = 2551 AND status = 1; onde 2551 é $enrollment_id preciso apenas mostrar em 
CVarDumper::dump

lembre-se de usar criteria e adicionar tudo na função enviada