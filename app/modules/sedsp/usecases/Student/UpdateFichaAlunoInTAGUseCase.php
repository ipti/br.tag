<?php

class UpdateFichaAlunoInTAGUseCase
{
    public function exec($inAluno)
    {
        $studentDatasource = new StudentSEDDataSource();
        $response = $studentDatasource->exibirFichaAluno($inAluno);

        try {
            $mapper = (object) StudentMapper::parseToTAGExibirFichaAluno($response);

            $studentIdentification = $mapper->StudentIdentification;
            $studentDocumentsAndAddress = $mapper->StudentDocumentsAndAddress;

            if ($studentIdentification === null) {
                $studentIdentification = $this->createAndSaveStudentIdentification($mapper->StudentIdentification);
            } else {
                $aluno = StudentIdentification::model()->findByAttributes(array('gov_id' => '000124756545'));
                $aluno->register_type = '60';
                $aluno->sedsp_sync = 1;
                $aluno->school_inep_id_fk = '35245239';
                $aluno->name = 'JESUS RODRIGUES DOS SANT';
                $aluno->filiation = 1;
                $aluno->filiation_1 = 'GABRIELA MARCELA SOARES ROSA';
                $aluno->filiation_2 = 'ADRIEL DE JESUS RODRIGUES DOS SANTOS';
                $aluno->birthday = '09/09/2018';
                $aluno->color_race = '1';
                $aluno->sex = '1';
                $aluno->bf_participator = '0';
                $aluno->nationality = 1;
                $aluno->edcenso_nation_fk = 76;
                $aluno->edcenso_uf_fk = 35;
                $aluno->edcenso_city_fk = 3555406;
                $aluno->deficiency = 0;
                $aluno->send_year = 2023;
                $aluno->inep_id = null;
                $aluno->civil_name = null;
                $aluno->uf = null;
                $aluno->no_document_desc = null;
                $aluno->scholarity = null;
                $aluno->id_email = null;
                $aluno->deficiency_type_blindness = null;
                $aluno->deficiency_type_low_vision = null;
                $aluno->deficiency_type_monocular_vision = null;
                $aluno->deficiency_type_deafness = null;
                $aluno->deficiency_type_disability_hearing = null;
                $aluno->deficiency_type_deafblindness = null;
                $aluno->deficiency_type_phisical_disability = null;
                $aluno->deficiency_type_intelectual_disability = null;
                $aluno->deficiency_type_multiple_disabilities = null;
                $aluno->deficiency_type_autism = null;
                $aluno->deficiency_type_aspenger_syndrome = null;
                $aluno->deficiency_type_rett_syndrome = null;
                $aluno->deficiency_type_childhood_disintegrative_disorder = null;
                $aluno->deficiency_type_gifted = null;
                $aluno->resource_aid_lector = null;
                $aluno->resource_aid_transcription = null;
                $aluno->resource_interpreter_guide = null;
                $aluno->resource_interpreter_libras = null;
                $aluno->resource_lip_reading = null;
                $aluno->resource_zoomed_test_16 = null;
                $aluno->resource_zoomed_test_18 = null;
                $aluno->resource_zoomed_test_20 = null;
                $aluno->resource_zoomed_test_24 = null;
                $aluno->resource_cd_audio = null;
                $aluno->resource_proof_language = null;
                $aluno->resource_video_libras = null;
                $aluno->resource_braille_test = null;
                $aluno->resource_none = null;
                $aluno->last_change = null;
                $aluno->responsable = null;
                $aluno->responsable_name = null;
                $aluno->responsable_rg = null;
                $aluno->responsable_cpf = null;
                $aluno->responsable_scholarity = null;
                $aluno->responsable_job = null;
                $aluno->food_restrictions = null;
                $aluno->responsable_telephone = null;
                $aluno->hash = null;
                $aluno->filiation_1_rg = null;
                $aluno->filiation_1_cpf = null;
                $aluno->filiation_1_scholarity = null;
                $aluno->filiation_1_job = null;
                $aluno->filiation_2_rg = null;
                $aluno->filiation_2_cpf = null;
                $aluno->filiation_2_scholarity = null;
                $aluno->filiation_2_job = null;
                $aluno->filiation_1_birthday = null;
                $aluno->save();
            }

            if ($studentDocumentsAndAddress === null) {
                $studentDocumentsAndAddress = $this->createAndSaveStudentDocumentsAndAddress($mapper->StudentDocumentsAndAddress, $studentIdentification, $mapper->StudentDocumentsAndAddress->gov_id);
            } else {
                $studentDocumentsAndAddress->attributes = $mapper->StudentDocumentsAndAddress->attributes;
                $studentDocumentsAndAddress->save();
            }

            return $studentIdentification;
        } catch (Exception $e) {
            $log = new LogError();
            $log->salvarDadosEmArquivo($e->getMessage());
        }
    }

    public function createAndSaveStudentDocumentsAndAddress($attributes, $studentIdentification, $gov_id)
    {
        $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
        $studentDocumentsAndAddress->attributes = $attributes->getAttributes();
        $studentDocumentsAndAddress->edcenso_city_fk = $attributes->edcenso_city_fk;
        $studentDocumentsAndAddress->gov_id = $gov_id;
        $studentDocumentsAndAddress->id = $studentIdentification->id;

        if ($studentDocumentsAndAddress->save()) {
            return true;
        }
    }

    public function createAndSaveStudentIdentification($attributes)
    {
        $studentIdentification = new StudentIdentification();
        $studentIdentification->attributes = $attributes->getAttributes();
        $studentIdentification->gov_id = $attributes->gov_id;

        if ($studentIdentification->save()) {
            return $studentIdentification;
        }
    }
}
