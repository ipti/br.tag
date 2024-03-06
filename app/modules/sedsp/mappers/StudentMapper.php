<?php

class StudentMapper
{
    private const CODIGO_UF = "35";

    /**
     * Summary of parseToSEDAlunoFicha
     * @param StudentIdentification $studentIdentificationTag
     * @param StudentDocumentsAndAddress $studentDocumentsAndAddressTag
     * @return array<InDadosPessoais|InDeficiencia|InDocumentos|InEnderecoResidencial|InRecursoAvaliacao>
     */
    public static function parseToSEDAlunoFicha(
        StudentIdentification $studentIdentificationTag,
        StudentDocumentsAndAddress $studentDocumentsAndAddressTag
    )
    {

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

        // Converte o valor '0' (Não declarada) para '6' no campo de cor/raça da sedsp
        $inDadosPessoais->setInCorRaca(
            $studentIdentificationTag->color_race === '0' ? '6' : $studentIdentificationTag->color_race
        );

        $inDadosPessoais->setInSexo($studentIdentificationTag->sex);
        $inDadosPessoais->setInBolsaFamilia($studentIdentificationTag->bf_participator);
        $inDadosPessoais->setInEmail($studentIdentificationTag->id_email);
        $inDadosPessoais->setInQuilombola(0);
        $inDadosPessoais->setInNumeroCNS($studentDocumentsAndAddressTag->cns);
        $inDadosPessoais->setInNacionalidade(
            empty($studentIdentificationTag->nationality) ? '1' : $studentIdentificationTag->nationality
        );
        $inDadosPessoais->setInCodPaisOrigem($studentIdentificationTag->edcenso_nation_fk);
        $inDadosPessoais->setInPaisOrigem($studentIdentificationTag->edcensoNationFk->name);
        $inDadosPessoais->setInBolsaFamilia($studentIdentificationTag->bf_participator);
        $inDadosPessoais->setInNomeSocial($studentIdentificationTag->civil_name);

        if ($studentIdentificationTag->nationality == '1') {
            //Obrigatórios quando inNacionalidade = 1
            $inDadosPessoais->setInNomeMunNascto($studentIdentificationTag->edcensoCityFk->name);
            $inDadosPessoais->setInUfMunNascto($studentIdentificationTag->edcensoUfFk->acronym);
        }

        // Deficiências
        $inDeficiencia = new InDeficiencia();
        if ($studentIdentificationTag->deficiency == '1') {
            $query = "SELECT
                        'deficiency_type_blindness' as 'deficiency'
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

            $res = Yii::app()->db->createCommand($query)->bindValue(':id', $studentIdentificationTag->id)->queryAll();

            $deficiencyMap = [
                'deficiency_type_blindness' => false,                              // Cegueira
                'deficiency_type_low_vision' => false,                             // Baixa Visão
                'deficiency_type_deafness' => false,                               // Sudez
                'deficiency_type_disability_hearing' => false,                     // Deficiência auditiva
                'deficiency_type_deafblindness' => false,                          // Surdocegueira
                'deficiency_type_phisical_disability' => false,                    // Deficiência Física
                'deficiency_type_intelectual_disability' => false,                 // Deficiência Intelectual
                'deficiency_type_multiple_disabilities' => false,                 // Deficiência Múltipla
                'deficiency_type_autism' => false,                                 // Transtorno do Espectro Autista
                'deficiency_type_gifted' => false,                                 // Altas Habilidades / Superdotação
            ];

            foreach ($res as $re) {
                $deficiencyMap[$re["deficiency"]] = true;
            }

            $inRecursoAvaliacao = new InRecursoAvaliacao;

            $resourceNone = $studentIdentificationTag->resource_none;

            if ($resourceNone == '1') {
                $inRecursoAvaliacao->setInNenhum('1');
            } else {
                $inArrayDeficiency = in_array(true, $deficiencyMap);

                if (
                    $resourceNone !== true &&
                    $inArrayDeficiency &&
                    $deficiencyMap["deficiency_type_deafness"] !== true
                ) {
                    $inRecursoAvaliacao->setInAuxilioLeitor('1');
                }

                if ($resourceNone !== true && $inArrayDeficiency) {
                    $inRecursoAvaliacao->setInAuxilioTranscricao('1');
                }

                if (
                    $resourceNone !== true &&
                    $inArrayDeficiency &&
                    $deficiencyMap["deficiency_type_deafblindness"] !== true
                ) {
                    $inRecursoAvaliacao->setInGuiaInterprete('1');
                }

                $allDeafnessConditions = $deficiencyMap["deficiency_type_deafness"] === true &&
                    $deficiencyMap["deficiency_type_disability_hearing"] === true &&
                    $deficiencyMap["deficiency_type_deafblindness"] === true;

                if (
                    $resourceNone !== true &&
                    $inArrayDeficiency &&
                    $allDeafnessConditions &&
                    $deficiencyMap["deficiency_type_deafness"] !== true
                ) {
                    $inRecursoAvaliacao->setInInterpreteLibras('1');
                }

                if ($resourceNone !== true && $inArrayDeficiency && $allDeafnessConditions) {
                    $inRecursoAvaliacao->setInLeituraLabial('1');
                }

                if (
                    $resourceNone !== true &&
                    $inArrayDeficiency &&
                    $deficiencyMap["deficiency_type_low_vision"] === true &&
                    $deficiencyMap["deficiency_type_deafblindness"] === true &&
                    $deficiencyMap["deficiency_type_blindness"] !== true &&
                    $studentIdentificationTag->resource_zoomed_test_24 !== true &&
                    $studentIdentificationTag->resource_braille_test !== true
                ) {
                    $inRecursoAvaliacao->setInProvaAmpliada('1');
                    $inRecursoAvaliacao->setInFonteProva('18');
                }

                if (
                    $resourceNone !== true &&
                    $inArrayDeficiency &&
                    $deficiencyMap["deficiency_type_low_vision"] === true &&
                    $deficiencyMap["deficiency_type_deafblindness"] === true &&
                    $deficiencyMap["deficiency_type_blindness"] !== true &&
                    $studentIdentificationTag->resource_braille_test !== true
                ) {
                    $inRecursoAvaliacao->setInProvaAmpliada('1');
                    $inRecursoAvaliacao->setInFonteProva('24');
                }

                if (
                    $resourceNone !== true &&
                    $inArrayDeficiency &&
                    $deficiencyMap["deficiency_type_deafness"] !== true
                ) {
                    $inRecursoAvaliacao->setInCdAudioDefVisual('1');
                }

                if (
                    $resourceNone !== true &&
                    $inArrayDeficiency &&
                    $allDeafnessConditions &&
                    $deficiencyMap["deficiency_type_blindness"] !== true
                ) {
                    $inRecursoAvaliacao->setInProvaLinguaPortuguesa('1');
                    $inRecursoAvaliacao->setInProvaVideoLibras('1');
                }

                if (
                    $resourceNone !== true &&
                    $inArrayDeficiency &&
                    $deficiencyMap["deficiency_type_blindness"] === true &&
                    $deficiencyMap["deficiency_type_deafblindness"] === true
                ) {
                    $inRecursoAvaliacao->setInProvaBraile('1');
                }

                if (
                    $inArrayDeficiency &&
                    $deficiencyMap["deficiency_type_blindness"] !== true &&
                    $deficiencyMap["deficiency_type_deafblindness"] !== true
                ) {
                    $inRecursoAvaliacao->setInNenhum('1');
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
        $inEnderecoResidencial->setInCodMunicipioDne('9756'); // Código Ubatuba
        
        
        $nameCidade = EdcensoCity::model()->findByPk($studentIdentificationTag->edcenso_city_fk);

        $inEnderecoResidencial->setInNomeCidade($nameCidade->name);
        $inEnderecoResidencial->setInUfCidade($ufCidade->acronym);
        $inEnderecoResidencial->setInComplemento($studentDocumentsAndAddressTag->complement);
        $inEnderecoResidencial->setInCep($studentDocumentsAndAddressTag->cep);
        //SED: 1:Rural; 2:Urbana  Tag: "1" => "URBANA", "2" => "RURAL"
        $inEnderecoResidencial->setInAreaLogradouro(($studentDocumentsAndAddressTag->residence_zone) === '1' ? '2' : '1');
        $inEnderecoResidencial->setInCodLocalizacaoDiferenciada($studentDocumentsAndAddressTag->diff_location);
        $inEnderecoResidencial->setInLatitude('0');
        $inEnderecoResidencial->setInLongitude('0');


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
     * @return array
     */
    public static function parseToTAGExibirFichaAluno(OutExibirFichaAluno $exibirFichaAluno)
    {
        $outDadosPessoais = $exibirFichaAluno->getOutDadosPessoais();
        $numRA = $outDadosPessoais->getOutNumRa();

        $outDocumentos = $exibirFichaAluno->getOutDocumentos();
        $outCertidaoNova = $exibirFichaAluno->getOutCertidaoNova();
        $outCertidaoAntiga = $exibirFichaAluno->getOutCertidaoAntiga();
        $outEnderecoResidencial = $exibirFichaAluno->getOutEnderecoResidencial();

        $inepId = Yii::app()->user->school;

        $studentIdentification = StudentIdentification::model()->find('name = :name and filiation_1 = :filiation_1', [':name' => $outDadosPessoais->getOutNomeAluno(), 'filiation_1' => $outDadosPessoais->getOutNomeMae()]);
        if ($studentIdentification == null) {
            $studentIdentification = new StudentIdentification;
            $studentIdentification->name = $outDadosPessoais->getOutNomeAluno();
            $studentIdentification->filiation_1 = $outDadosPessoais->getOutNomeMae();
            $studentIdentification->deficiency = 0;
        }
        $studentIdentification->school_inep_id_fk = $inepId;
        $studentIdentification->gov_id = $numRA;

        $filiation = $outDadosPessoais->getOutNomeMae() != "" || $outDadosPessoais->getOutNomePai() != "" ? 1 : 0;
        $studentIdentification->filiation = $filiation;
        $studentIdentification->filiation_2 = $outDadosPessoais->getOutNomePai();
        $studentIdentification->birthday = $outDadosPessoais->getOutDataNascimento();
        $studentIdentification->color_race = $outDadosPessoais->getOutCorRaca() === '6' ? '0' : $outDadosPessoais->getOutCorRaca();
        $studentIdentification->sex = $outDadosPessoais->getOutCodSexo();
        $studentIdentification->bf_participator = $outDadosPessoais->getOutCodBolsaFamilia();
        $studentIdentification->nationality = intval($outDadosPessoais->getOutNacionalidade());
        if ($outDadosPessoais->getOutNacionalidade() == 1) { //1 - Brasileira
            $studentIdentification->edcenso_nation_fk = 76;
        } elseif ($outDadosPessoais->getOutNacionalidade() == 2) { //2 - Estrangeira
            $studentIdentification->edcenso_nation_fk = $outDadosPessoais->getOutCodPaisOrigem();
        }
        $studentIdentification->edcenso_uf_fk = intval(
            EdcensoUf::model()->find(
                "acronym = :abbreviation", [":abbreviation" => $outDadosPessoais->getOutUfMunNascto()]
            )->id
        );
        $studentIdentification->edcenso_city_fk = intval(
            EdcensoCity::model()->find(
                "name = :nameCity", [":nameCity" => $outEnderecoResidencial->getOutNomeCidade()]
            )->id
        );
        $studentIdentification->send_year = intval(Yii::app()->user->year);
        $studentIdentification->sedsp_sync = 1;

        //StudentDocuments

        $studentDocumentsAndAddress = new StudentDocumentsAndAddress;
        $studentDocumentsAndAddress->school_inep_id_fk = $inepId;
        $studentDocumentsAndAddress->gov_id = $numRA;
        $studentDocumentsAndAddress->student_fk = $studentIdentification->id;
        $studentDocumentsAndAddress->id = $studentIdentification->id;
        $studentDocumentsAndAddress->cpf = $outDocumentos->getOutCpf();
        $studentDocumentsAndAddress->nis = $outDocumentos->getOutNumNis();

        $rgNumber = $outDocumentos->getOutNumDoctoCivil() + $outDocumentos->getOutDigitoDoctoCivil();
        $studentDocumentsAndAddress->rg_number = $rgNumber;

        if ($outDocumentos->getOutDataEmissaoDoctoCivil()) {
            $studentDocumentsAndAddress->rg_number_expediction_date = $outDocumentos->getOutDataEmissaoDoctoCivil();
        }
        if ($outDocumentos->getOutDataEmissaoCertidao()) {
            $studentDocumentsAndAddress->civil_certification_date = $outDocumentos->getOutDataEmissaoCertidao();
        }

        //Matrículas
        $listOfActiveEnrollments = self::getListMatriculasRa($numRA);

        $studentFk = StudentIdentification::model()->findByAttributes(['gov_id' => $numRA])->id;

        $arrayMapEnrollments = [];
        foreach ($listOfActiveEnrollments as $enrollment) {

            $numClass = $enrollment->getOutNumClasse();
            $codSchool = self::mapToTAGInepId($enrollment->getOutCodEscola());

            $classroomFk = self::getClassroomFk($numClass, $codSchool);
            if ($classroomFk === null) {
                $inRelacaoClasses = new InRelacaoClasses(Yii::app()->user->year, strval(intval(substr($codSchool, 2))), null, null, null, null);
                $classes = new GetRelacaoClassesFromSEDUseCase();
                $classes->exec($inRelacaoClasses);
            }
            $classroomFk = self::getClassroomFk($numClass, $codSchool);

            $studentEnrollment = new StudentEnrollment();
            $studentEnrollment->school_inep_id_fk = $codSchool;
            $studentEnrollment->student_fk = $studentFk;
            $studentEnrollment->classroom_fk = $classroomFk;
            $studentEnrollment->status = self::mapSituationEnrollmentToTag($enrollment->getOutCodSitMatricula());
            $studentEnrollment->create_date = DateTime::createFromFormat('d/m/Y', $enrollment->getOutDataInicioMatricula())->format('Y-m-d');

            $classroomMapper = new ClassroomMapper;
            $edcensoStage = $classroomMapper->convertTipoEnsinoToStage($enrollment->getOutCodTipoEnsino(), $enrollment->getOutCodSerieAno());
            $studentEnrollment->edcenso_stage_vs_modality_fk = $edcensoStage;

            $arrayMapEnrollments[] = $studentEnrollment;
        }


        //Address
        $studentDocumentsAndAddress->address = $outEnderecoResidencial->getOutLogradouro();
        $studentDocumentsAndAddress->number = $outEnderecoResidencial->getOutNumero();
        $studentDocumentsAndAddress->neighborhood = $outEnderecoResidencial->getOutBairro();
        $studentDocumentsAndAddress->complement = $outEnderecoResidencial->getOutComplemento();
        $studentDocumentsAndAddress->cep = $outEnderecoResidencial->getOutCep();
        $studentDocumentsAndAddress->DNE_city_code = $outEnderecoResidencial->getOutCodMunicipioDne();
        $studentDocumentsAndAddress->residence_zone = $outEnderecoResidencial->getOutAreaLogradouro() == "URBANA" ? 1 : 2;

        $studentDocumentsAndAddress->edcenso_uf_fk = intval(
            EdcensoUf::model()->find("acronym = :acronym", [":acronym" => $outEnderecoResidencial->getOutUfCidade()])->id
        );
        $studentDocumentsAndAddress->edcenso_city_fk = intval(
            EdcensoCity::model()->find("name = :name", [":name" => $outEnderecoResidencial->getOutNomeCidade()])->id
        );

        $locationMap = [
            "Área de assentamento" => 1,
            "Terra indígena" => 2,
            "Área onde se localizada em Comunidade remanescente de Quilombos" => 3,
            "Não está localizado em área de localização diferenciada" => 7
        ];

        $location = $outEnderecoResidencial->getOutLocalizacaoDiferenciada();
        $studentDocumentsAndAddress->diff_location = $locationMap[$location] ?? 7;


        //Civil_certification
        if (isset($outCertidaoNova)) {
            //identificar como descobrir o município de certidao nova
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
            $studentDocumentsAndAddress->notary_office_uf_fk = intval(
                EdcensoUf::model()->find("acronym = :acronym", [":acronym" => $outCertidaoAntiga->getOutUfComarca()])->id
            );
            $studentDocumentsAndAddress->notary_office_city_fk = intval(
                EdcensoCity::model()->find("name = :name", [":name" => $outCertidaoAntiga->getOutNomeMunComarca()])->id
            );
        }

        $parseResult["StudentEnrollment"] = $arrayMapEnrollments;
        $parseResult["StudentIdentification"] = $studentIdentification;
        $parseResult["StudentDocumentsAndAddress"] = $studentDocumentsAndAddress;

        return $parseResult;
    }

    public static function getClassroomFk($numClass, $codSchool)
    {
        return Classroom::model()->find([
            'condition' => '(gov_id = :numClass OR inep_id = :numClass) AND school_inep_fk = :codSchool',
            'params' => [
                ':numClass' => $numClass,
                ':codSchool' => $codSchool,
            ],
        ])->id;
    }

    public static function existsEnrollments($studentFk, $classroomInepId, $schoolInepIdFk)
    {
        $query = "SELECT EXISTS(SELECT 1 FROM student_enrollment
                WHERE student_fk = :studentFk
                AND classroom_inep_id = :classroomInepId
                AND school_inep_id_fk = :schoolInepIdFk
            ) AS result;";

        $command = Yii::app()->db->createCommand($query);
        $command->bindValues([
            ':studentFk' => $studentFk,
            ':classroomInepId' => $classroomInepId,
            ':schoolInepIdFk' => $schoolInepIdFk
        ]);

        return $command->queryScalar() === 1 ? true : false;
    }

    /**
     * Summary of getListMatriculasRa
     * @param mixed $inNumRA
     * @return array
     */
    public static function getListMatriculasRa($inNumRA)
    {
        $enrollment = new EnrollmentSEDDataSource;
        $outActiveEnrolments = $enrollment->getListarMatriculasRA(new InAluno($inNumRA, null, "SP"));

        return self::getListEnrollmentsActives($outActiveEnrolments);
    }

    /**
     * Summary of getListEnrollmentsActives
     * @param OutListaMatriculaRA $list
     * @return OutListaMatriculas[]
     */
    public static function getListEnrollmentsActives(OutListaMatriculaRA $list)
    {
        $listOfActiveEnrollments = [];
        $outListMatriculas = $list->getOutListaMatriculas();

        foreach ($outListMatriculas as $enrollment) {
            if ($enrollment->outAnoLetivo === Yii::app()->user->year) {
                $listOfActiveEnrollments[] = $enrollment;
            }
        }

        return $listOfActiveEnrollments;
    }

    public static function fetchSchoolData(InEscola $inEscola)
    {
        $dataSource = new SchoolSEDDataSource();
        return $dataSource->getSchool($inEscola);
    }

    public static function mapToTAGInepId($sedInepId)
    {
        if (strlen($sedInepId) < 6) {
            return self::CODIGO_UF . "0" . $sedInepId;
        }
        return self::CODIGO_UF . $sedInepId;
    }

    public static function mapSituationEnrollmentToTag($codSitMatriculaSed)
    {
        //SED => TAG
        $map = [
            '0' => '1',  // MATRICULADO
            '1' => '2',  // TRANSFERIDO
            '2' => '4',  // DEIXOU DE FREQUENTAR
            '3' => '5',  // REMANEJADO
            '4' => '11', // ÓBITO
            '5' => '4',  // DEIXOU DE FREQUENTAR
            '6' => '6',  // APROVADO
            '7' => '4',  // DEIXOU DE FREQUENTAR
            '8' => '4',  // DEIXOU DE FREQUENTAR
            '9' => '3',  // CANCELADO
            '10' => '5', // REMANEJADO
            '11' => '3',  // CANCELADO
            '12' => '3',  // CANCELADO
            '13' => '3',  // CANCELADO
            '14' => '3',  // CANCELADO
            '15' => '9',  // CONCLUINTE
            '16' => '2',  // TRANSFERIDO
            '17' => '5',  // REMANEJADO
            '18' => '4',  // DEIXOU DE FREQUENTAR
            '19' => '2',  // TRANSFERIDO
            '20' => '2',  // TRANSFERIDO
            '31' => '2'   // TRANSFERIDO
        ];

        return $map[$codSitMatriculaSed];
    }

    public static function mapSituationEnrollmentToSed($codSitMatriculaTag)
    {
        // TAG                                 =>   SED
        $map = [
            '1' => '0',                             // ATIVO / ENCERRADO
            '2' => '1',                             // TRANSFERIDO,
            '3' => '9',                             // CESSÃO POR DESISTÊNCIA
            '4' => '2',                             // ABANDONOU
            '5' => '3',                             // RECLASSIFICADO
            '6' => '5',                             // NÃO COMPARECIMENTO
            '9' => '15',                            // CESSÃO POR CONCLUSÃO DO CURSO
            '11' => '4',
        ];

        return $map[$codSitMatriculaTag];
    }
}
