<?php

class StudentMapper
{
    /**
     * Summary of parseToSEDAlunoFicha
     * @param StudentIdentification $studentIdentificationTag
     * @param StudentDocumentsAndAddress $studentDocumentsAndAddressTag
     * @return array<InDadosPessoais|InDeficiencia|InDocumentos|InEnderecoResidencial|InRecursoAvaliacao>
     */
    public static function parseToSEDAlunoFicha(
        StudentIdentification $studentIdentificationTag,
        StudentDocumentsAndAddress $studentDocumentsAndAddressTag
    ) {

        $parseResult = [];

        $ufCidade = EdcensoUf::model()->findByPk($studentIdentificationTag->edcenso_uf_fk);

        $inAluno = new InAluno();
        $inAluno->setInNumRA($studentIdentificationTag->gov_id);
        $inAluno->setInDigitoRA(null);
        $inAluno->setInSiglaUFRA($ufCidade->acronym);
        
        // Dados Pessoais
        $inDadosPessoais = new InDadosPessoais();
        $inDadosPessoais->setInNomeAluno($studentIdentificationTag->name);
        $inDadosPessoais->setInNomeMae($studentIdentificationTag->filiation_1);
        $inDadosPessoais->setInNomePai($studentIdentificationTag->filiation_2);
        $inDadosPessoais->setInDataNascimento($studentIdentificationTag->birthday);
        $inDadosPessoais->setInCorRaca($studentIdentificationTag->color_race);
        $inDadosPessoais->setInSexo($studentIdentificationTag->sex);
        $inDadosPessoais->setInBolsaFamilia($studentIdentificationTag->bf_participator);
        $inDadosPessoais->setInQuilombola(0);
        $inDadosPessoais->setInNumeroCNS($studentDocumentsAndAddressTag->cns);
        $inDadosPessoais->setInNacionalidade(empty($studentIdentificationTag->nationality) ? '1' : $studentIdentificationTag->nationality);
        $inDadosPessoais->setInCodPaisOrigem($studentIdentificationTag->edcenso_nation_fk);
        $inDadosPessoais->setInPaisOrigem($studentIdentificationTag->edcensoNationFk->name);
        $inDadosPessoais->setInBolsaFamilia($studentIdentificationTag->bf_participator);

        if($studentIdentificationTag->nationality == '1'){
            //Obrigatórios quando inNacionalidade = 1
            $inDadosPessoais->setInNomeMunNascto($studentIdentificationTag->edcensoCityFk->name);
            $inDadosPessoais->setInUfMunNascto($studentIdentificationTag->edcensoUfFk->acronym);
        }

        // Deficiências
        $inDeficiencia = new InDeficiencia();
        if($studentIdentificationTag->deficiency == '1') {
            $query = "SELECT
                        'deficiency_type_blindness'
                    FROM student_identification
                    WHERE deficiency_type_blindness = 1 and id = :id 
                    UNION ALL
                    SELECT
                        'deficiency_type_low_vision'
                    FROM student_identification
                    WHERE deficiency_type_low_vision = 1 and id = :id 
                    UNION ALL
                    SELECT
                        'deficiency_type_monocular_vision'
                    FROM student_identification
                    WHERE deficiency_type_monocular_vision = 1 and id = :id
                    UNION ALL
                    SELECT
                        'deficiency_type_deafness'
                    FROM student_identification
                    WHERE deficiency_type_deafness = 1 and id = :id 
                    UNION ALL
                    SELECT
                        'deficiency_type_disability_hearing'
                    FROM student_identification
                    WHERE deficiency_type_disability_hearing = 1 and id = :id
                    UNION ALL
                    SELECT
                        'deficiency_type_deafblindness'
                    FROM student_identification
                    WHERE deficiency_type_deafblindness = 1 and id = :id
                    UNION ALL
                    SELECT
                        'deficiency_type_phisical_disability'
                    FROM student_identification
                    WHERE deficiency_type_phisical_disability = 1 and id = :id
                    UNION ALL
                    SELECT
                        'deficiency_type_intelectual_disability'
                    FROM student_identification
                    WHERE deficiency_type_intelectual_disability = 1 and id = :id
                    UNION ALL
                    SELECT
                        'deficiency_type_autism'
                    FROM student_identification
                    WHERE deficiency_type_autism = 1 and id = :id 
                    UNION ALL
                    SELECT
                        'deficiency_type_aspenger_syndrome'
                    FROM student_identification
                    WHERE deficiency_type_aspenger_syndrome = 1 and id = :id 
                    UNION ALL
                    SELECT
                        'deficiency_type_rett_syndrome'
                    FROM student_identification
                    WHERE deficiency_type_rett_syndrome = 1 and id = :id 
                    UNION ALL
                    SELECT
                        'deficiency_type_childhood_disintegrative_disorder'
                    FROM student_identification
                    WHERE deficiency_type_childhood_disintegrative_disorder = 1 and id = :id 
                    UNION ALL
                    SELECT
                        'deficiency_type_gifted'
                    FROM student_identification
                    WHERE deficiency_type_gifted = 1 and id = :id ";


            if($studentIdentificationTag->deficiency_type_multiple_disabilities == '1') {
                $inDeficiencia->setInCodNecessidade('1');  //1 Múltipla
            } else {
                $res = Yii::app()->db->createCommand($query)->bindValue(':id', $studentIdentificationTag->id)->queryAll();
                $deficiencyMap = [
                    'deficiency_type_blindness' => '2', // Cegueira
                    'deficiency_type_low_vision' => '3', // Baixa Visão
                    'deficiency_type_deafness' => '4', // Surdez Severa ou Profunda
                    'deficiency_type_disability_hearing' => '5', // Surdez Leve ou Moderada
                    'deficiency_type_deafblindness' => '6', // Surdocegueira
                    'deficiency_type_intelectual_disability' => '7', // Física – Paralisia Cerebral
                    'deficiency_type_phisical_disability' => '8', // Física – Cadeirante
                    'deficiency_type_autism' => '10', // Síndrome de Down
                    'deficiency_type_gifted' => '30', // Altas Habilidades / Superdotação
                ];
                
                foreach ($res as $re) {
                    if (isset($deficiencyMap[$re])) {
                        $inDeficiencia->setInCodNecessidade($deficiencyMap[$re]);
                    }
                }
            }
        }

        //Matrículas


        // Documentos  
        $inDocuments = new InDocumentos();
        $inDocuments->setInNumInep($studentDocumentsAndAddressTag->gov_id);
        $inDocuments->setInCpf($studentDocumentsAndAddressTag->cpf);
        $inDocuments->setInNumNis(null);
        $inDocuments->setInNumRg($studentDocumentsAndAddressTag->rg_number);
        $inDocuments->setInUfrg($studentDocumentsAndAddressTag->rgNumberEdcensoUfFk->acronym);
        
        // Endereco
        $inEnderecoResidencial = new InEnderecoResidencial;
        $inEnderecoResidencial->setInLogradouro($studentDocumentsAndAddressTag->address);
        $inEnderecoResidencial->setInNumero($studentDocumentsAndAddressTag->number);
        $inEnderecoResidencial->setInBairro($studentDocumentsAndAddressTag->neighborhood);

        $nameCidade = EdcensoCity::model()->findByPk($studentIdentificationTag->edcenso_city_fk);

        $inEnderecoResidencial->setInNomeCidade($nameCidade->name);
        $inEnderecoResidencial->setInUfCidade($ufCidade->acronym);
        $inEnderecoResidencial->setInComplemento($studentDocumentsAndAddressTag->complement);
        $inEnderecoResidencial->setInCep($studentDocumentsAndAddressTag->cep);
        $inEnderecoResidencial->setInAreaLogradouro($studentDocumentsAndAddressTag->residence_zone); // 1 - Rural; 2 - Urbana
        $inEnderecoResidencial->setInCodLocalizacaoDiferenciada($studentDocumentsAndAddressTag->diff_location);
        $inEnderecoResidencial->setInLatitude('0');
        $inEnderecoResidencial->setInLongitude('0');

        // Recursos
        $inRecursoAvaliacao = new InRecursoAvaliacao;
        $inRecursoAvaliacao->setInNenhum(null);
        $inRecursoAvaliacao->setInAuxilioLeitor(null);
        $inRecursoAvaliacao->setInAuxilioTranscricao(null);
        $inRecursoAvaliacao->setInLeituraLabial(null);
        $inRecursoAvaliacao->setInProvaBraile(null);
        $inRecursoAvaliacao->setInProvaAmpliada(null);
        
        $inRecursoAvaliacao->setInFonteProva('12');
        $inRecursoAvaliacao->setInProvaVideoLibras(null);
        $inRecursoAvaliacao->setInProvaLinguaPortuguesa(null);


        $parseResult["InAluno"] = $inAluno;
        $parseResult["InDadosPessoais"] = $inDadosPessoais;
        $parseResult["InDeficiencia"] = $inDeficiencia;
        $parseResult["InRecursoAvaliacao"] = $inRecursoAvaliacao;
        $parseResult["InDocumentos"] = $inDocuments;
        $parseResult["InEnderecoResidencial"] = $inEnderecoResidencial;

        return $parseResult;
    }
    /**
     * Summary of parseToTAGExibirFichaAluno
     * @param OutExibirFichaAluno $exibirFichaAluno
     */
    public static function parseToTAGExibirFichaAluno(OutExibirFichaAluno $exibirFichaAluno)
    {
        $parseResult = [];

        $outDadosPessoais = $exibirFichaAluno->getOutDadosPessoais();
        $outDocumentos = $exibirFichaAluno->getOutDocumentos();
        $outCertidaoNova = $exibirFichaAluno->getOutCertidaoNova();
        $outCertidaoAntiga = $exibirFichaAluno->getOutCertidaoAntiga();
        $outEnderecoResidencial = $exibirFichaAluno->getOutEnderecoResidencial();

        $inepId = Yii::app()->user->school;

        $studentIdentification = new StudentIdentification;
        $studentIdentification->school_inep_id_fk = $inepId;
        $studentIdentification->gov_id = $outDadosPessoais->getOutNumRa();
        $studentIdentification->name = $outDadosPessoais->getOutNomeAluno();
        $studentIdentification->filiation = $outDadosPessoais->getOutNomeMae() != "" || $outDadosPessoais->getOutNomePai() != "" ? 1 : 0;
        $studentIdentification->filiation_1 = $outDadosPessoais->getOutNomeMae();
        $studentIdentification->filiation_2 = $outDadosPessoais->getOutNomePai();
        $studentIdentification->birthday = $outDadosPessoais->getOutDataNascimento();
        $studentIdentification->color_race = empty($outDadosPessoais->getOutCorRaca()) ? 0 : $outDadosPessoais->getOutCorRaca();
        $studentIdentification->sex = $outDadosPessoais->getOutCodSexo();
        $studentIdentification->bf_participator = $outDadosPessoais->getOutCodBolsaFamilia();
        $studentIdentification->nationality = intval($outDadosPessoais->getOutNacionalidade());
        if ($outDadosPessoais->getOutNacionalidade() == 1) { //1 - Brasileira
            $studentIdentification->edcenso_nation_fk = 76;
        } elseif ($outDadosPessoais->getOutNacionalidade() == 2) { //2 - Estrangeira
            $studentIdentification->edcenso_nation_fk = $outDadosPessoais->getOutCodPaisOrigem();
        }
        $studentIdentification->edcenso_uf_fk = intval(EdcensoUf::model()->find("acronym = :acronym", [":acronym" => $outDadosPessoais->getOutUfMunNascto()])->id);
        $studentIdentification->edcenso_city_fk = intval(EdcensoCity::model()->find("name = :name", [":name" => $outEnderecoResidencial->getOutNomeCidade()])->id);
        $studentIdentification->deficiency = 0;
        $studentIdentification->send_year = intval(Yii::app()->user->year);

        //StudentDocuments
        $studentDocumentsAndAddress = new StudentDocumentsAndAddress;
        $studentDocumentsAndAddress->school_inep_id_fk = $inepId;
        $studentDocumentsAndAddress->gov_id = $outDadosPessoais->getOutNumRa();
        $studentDocumentsAndAddress->cpf = $outDocumentos->getOutCpf();
        $studentDocumentsAndAddress->nis = $outDocumentos->getOutNumNis();
        $studentDocumentsAndAddress->rg_number = $outDocumentos->getOutNumDoctoCivil() + $outDocumentos->getOutDigitoDoctoCivil();
        

        if ($outDocumentos->getOutDataEmissaoDoctoCivil()) {
            $studentDocumentsAndAddress->rg_number_expediction_date = $outDocumentos->getOutDataEmissaoDoctoCivil();
        }
        if ($outDocumentos->getOutDataEmissaoCertidao()) {
            $studentDocumentsAndAddress->civil_certification_date = $outDocumentos->getOutDataEmissaoCertidao();
        }


        //Address
        $studentDocumentsAndAddress->address = $outEnderecoResidencial->getOutLogradouro();
        $studentDocumentsAndAddress->number = $outEnderecoResidencial->getOutNumero();
        $studentDocumentsAndAddress->neighborhood = $outEnderecoResidencial->getOutBairro();
        $studentDocumentsAndAddress->complement = $outEnderecoResidencial->getOutComplemento();
        $studentDocumentsAndAddress->cep = $outEnderecoResidencial->getOutCep();
        $studentDocumentsAndAddress->residence_zone = $outEnderecoResidencial->getOutAreaLogradouro() == "URBANA" ? 1 : 2;
        $studentDocumentsAndAddress->edcenso_uf_fk = intval(EdcensoUf::model()->find("acronym = :acronym", [":acronym" => $outEnderecoResidencial->getOutUfCidade()])->id);
        $studentDocumentsAndAddress->edcenso_city_fk = intval(EdcensoCity::model()->find("name = :name", [":name" => $outEnderecoResidencial->getOutNomeCidade()])->id);
        
        if ($outEnderecoResidencial->getOutLocalizacaoDiferenciada() == "Não está localizado em área de localização diferenciada") {
            $studentDocumentsAndAddress->diff_location = 7;
        } else if ($outEnderecoResidencial->getOutLocalizacaoDiferenciada() == "Área onde se localizada em Comunidade remanescente de Quilombos") {
            $studentDocumentsAndAddress->diff_location = 3;
        } else if ($outEnderecoResidencial->getOutLocalizacaoDiferenciada() == "Terra indígena") {
            $studentDocumentsAndAddress->diff_location = 2;
        } else if ($outEnderecoResidencial->getOutLocalizacaoDiferenciada() == "Área de assentamento") {
            $studentDocumentsAndAddress->diff_location = 1;
        }

        //Civil_certification
        if (isset($outCertidaoNova)) {
            // @todo identificar como descobrir o município de certidao nova
            $studentDocumentsAndAddress->civil_certification = 2;
            $studentDocumentsAndAddress->civil_certification_term_number = $outCertidaoNova->getOutCertMatr08();
            $studentDocumentsAndAddress->civil_certification_sheet = $outCertidaoNova->getOutCertMatr07();
            $studentDocumentsAndAddress->civil_certification_book = $outCertidaoNova->getOutCertMatr06();
        } elseif (isset($outCertidaoAntiga)) {
            $studentDocumentsAndAddress->civil_certification = 1;
            $studentDocumentsAndAddress->civil_certification_type = 1;
            $studentDocumentsAndAddress->civil_certification_term_number = $outCertidaoAntiga->getOutNumCertidao();
            $studentDocumentsAndAddress->civil_certification_sheet = $outCertidaoAntiga->getOutFolhaRegNum();
            $studentDocumentsAndAddress->civil_certification_book = $outCertidaoAntiga->getOutNumLivroReg();
            $studentDocumentsAndAddress->notary_office_uf_fk = intval(EdcensoUf::model()->find("acronym = :acronym", [":acronym" => $outCertidaoAntiga->getOutUfComarca()])->id);
            $studentDocumentsAndAddress->notary_office_city_fk = intval(EdcensoCity::model()->find("name = :name", [":name" => $outCertidaoAntiga->getOutNomeMunComarca()])->id);
        }

        $parseResult["StudentIdentification"] = $studentIdentification;
        $parseResult["StudentDocumentsAndAddress"] = $studentDocumentsAndAddress;

        return $parseResult;
    }

    private static function findSchoolById($schoolId)
    {
        return SchoolIdentification::model()->find('inep_id = :inep_id', [':inep_id' => $schoolId]);
    }

    public static function fetchSchoolData(InEscola $inEscola)
    {
        $dataSource = new SchoolSEDDataSource();
        return $dataSource->getSchool($inEscola);
    }
}
