<?php

class ClassroomMapper
{
    /**
     * Summary of parseToTAGFormacaoClasse
     * @param OutFormacaoClasse $outFormacaoClasse
     * @return array<Classroom|StudentIdentification[]>
     */
    public static function parseToTAGFormacaoClasse(OutFormacaoClasse $outFormacaoClasse)
    {
        $stage = self::convertTipoEnsinoToStage(
            $outFormacaoClasse->getOutCodTipoEnsino(), $outFormacaoClasse->getOutCodSerieAno()
        );

        $classroomTag = new Classroom();
        $classroomTag->school_inep_fk = SchoolMapper::mapToTAGInepId($outFormacaoClasse->getOutCodEscola());
        $classroomTag->gov_id = $outFormacaoClasse->getOutNumClasse();

        $classroomSEDDataSource = new ClassroomSEDDataSource();
        $response = $classroomSEDDataSource->getConsultClass(
            new InConsultaTurmaClasse($outFormacaoClasse->getOutAnoLetivo(), $outFormacaoClasse->getOutNumClasse())
        );

        $classroomTag->name = $response->getOutDescricaoTurma();
        $classroomTag->edcenso_stage_vs_modality_fk = $stage;
        $classroomTag->schooling = 1;
        $classroomTag->assistance_type = 0;
        $classroomTag->modality = 1;
        $classroomTag->initial_hour = substr($outFormacaoClasse->getOutHorarioInicio(), 0, 2);
        $classroomTag->initial_minute = substr($outFormacaoClasse->getOutHorarioInicio(), -2);
        $classroomTag->final_hour = substr($outFormacaoClasse->getOutHorarioFim(), 0, 2);
        $classroomTag->final_minute = substr($outFormacaoClasse->getOutHorarioFim(), -2);
        $classroomTag->week_days_sunday = 0;
        $classroomTag->week_days_monday = 1;
        $classroomTag->week_days_tuesday = 1;
        $classroomTag->week_days_wednesday = 1;
        $classroomTag->week_days_thursday = 1;
        $classroomTag->week_days_friday = 1;
        $classroomTag->week_days_saturday = 1;
        $classroomTag->school_year = $outFormacaoClasse->getOutAnoLetivo();
        $classroomTag->pedagogical_mediation_type = 1;
        $classroomTag->sedsp_acronym = $response->getOutTurma();
        $schoolUnity = SedspSchoolUnities::model()->find('code = :code', [':code' => $response->getOutCodUnidade()]);
        $classroomTag->sedsp_school_unity_fk = $schoolUnity->id;
        $classroomTag->sedsp_classnumber = $response->getOutNumeroSala();
        $classroomTag->sedsp_max_physical_capacity = $response->getOutNrCapacidadeFisicaMaxima();
        $classroomTag->sedsp_sync = 1;

        $indexedByAcronym = [];
        $edcensoUf = EdcensoUf::model()->findAll();
        foreach ($edcensoUf as $uf) {
            $indexedByAcronym[$uf['acronym']] = $uf;
        }

        $studentDatasource = new StudentSEDDataSource();
        $listStudents = [];
        $students = $outFormacaoClasse->getOutAlunos();
        foreach ($students as $student) {
            #if ($student->getOutDescSitMatricula() === 'ATIVO') {
                $studentIdentification = new StudentIdentification();
                $studentIdentification->gov_id = $student->getOutNumRa();
                $studentIdentification->name = $student->getOutNomeAluno();
                $studentIdentification->birthday = $student->getOutDataNascimento();

                $outExibirFichaAluno = $studentDatasource->exibirFichaAluno(
                    new InAluno($student->getOutNumRa(), $student->getOutDigitoRA(), "SP")
                )->getOutDadosPessoais();

                if ($outExibirFichaAluno === null) {
                    continue;
                }

                $studentIdentification->sex = $outExibirFichaAluno->getOutCodSexo();

                $studentIdentification->color_race = $outExibirFichaAluno->getOutCorRaca() === '6' ? '0' : $outExibirFichaAluno->getOutCorRaca();
                $studentIdentification->filiation = 1;
                $studentIdentification->filiation_1 = $outExibirFichaAluno->getOutNomeMae();
                $studentIdentification->filiation_2 = $outExibirFichaAluno->getOutNomePai();
                $studentIdentification->nationality = $outExibirFichaAluno->getOutNacionalidade();
                $studentIdentification->uf = $student->getOutSiglaUfra();

                if ($outExibirFichaAluno->getOutNacionalidade() == 1) { //1 - Brasileira
                    $studentIdentification->edcenso_nation_fk = 76;
                } elseif ($outExibirFichaAluno->getOutNacionalidade() == 2) { //2 - Estrangeira
                    $studentIdentification->edcenso_nation_fk = $outExibirFichaAluno->getOutCodPaisOrigem();
                }

                $studentIdentification->edcenso_uf_fk = intval(
                    $indexedByAcronym[$outExibirFichaAluno->getOutSiglaUfra()]->id
                );

                $schoolInepIdFk = $studentIdentification->edcenso_uf_fk . $outFormacaoClasse->getOutCodEscola();
                $studentIdentification->school_inep_id_fk = $schoolInepIdFk;
                $studentIdentification->deficiency = 0;
                $studentIdentification->send_year = $outFormacaoClasse->getOutAnoLetivo();
                $studentIdentification->scholarity = $student->getOutSerieNivel();

                $listStudents[] = $studentIdentification;
            #}
        }

        $parseResult = [];
        $parseResult["Classroom"] = $classroomTag;
        $parseResult["Students"] = $listStudents;

        return $parseResult;
    }

    public static function parseToTAGRelacaoClasses(OutRelacaoClasses $outRelacaoClasses)
    {
        $schoolInepFk = SchoolMapper::mapToTAGInepId($outRelacaoClasses->getOutCodEscola());
        $outClasses = $outRelacaoClasses->getOutClasses();

        $arrayClasses = [];
        foreach ($outClasses as $classe) {
            $classroom = Classroom::model()->find("gov_id = :gov_id", ["gov_id" => $classe->getOutNumClasse()]);
            if ($classroom == null) {
                $classroom = new Classroom();
                $classroom->school_inep_fk = $schoolInepFk;
                $classroom->gov_id = $classe->getOutNumClasse();

                $classroom->pedagogical_mediation_type = 1;
                $classroom->week_days_sunday = 0;
                $classroom->week_days_monday = 1;
                $classroom->week_days_tuesday = 1;
                $classroom->week_days_wednesday = 1;
                $classroom->week_days_thursday = 1;
                $classroom->week_days_friday = 1;
                $classroom->week_days_saturday = 1;
                $classroom->assistance_type = 0;
                $classroom->modality = 1;
                $classroom->schooling = 1;
            }
            $classroomSEDDataSource = new ClassroomSEDDataSource();
            $response = $classroomSEDDataSource->getConsultClass(
                new InConsultaTurmaClasse($outRelacaoClasses->getOutAnoLetivo(), $classe->getOutNumClasse())
            );
            $classroom->name = $response->getOutDescricaoTurma();
            $classroom->initial_hour = substr($classe->getOutHorarioInicio(), 0, 2);
            $classroom->initial_minute = substr($classe->getOutHorarioInicio(), -2);
            $classroom->final_hour = substr($classe->getOutHorarioFim(), 0, 2);
            $classroom->final_minute = substr($classe->getOutHorarioFim(), -2);
            $classroom->edcenso_stage_vs_modality_fk = self::convertTipoEnsinoToStage(
                $classe->getOutCodTipoEnsino(), $classe->getOutCodSerieAno()
            );
            $classroom->school_year = $outRelacaoClasses->getOutAnoLetivo();
            $classroom->turn = self::convertCodTurno($classe->getOutCodTurno());
            $classroom->sedsp_acronym = $response->getOutTurma();
            $schoolUnity = SedspSchoolUnities::model()->find('code = :code', [':code' => $response->getOutCodUnidade()]);
            $classroom->sedsp_school_unity_fk = $schoolUnity->id;
            $classroom->sedsp_classnumber = $response->getOutNumeroSala();
            $classroom->sedsp_max_physical_capacity = $response->getOutNrCapacidadeFisicaMaxima();
            $classroom->sedsp_sync = 1;

            $arrayClasses[] = $classroom;
        }

        $parseResult = [];
        $parseResult["Classrooms"] = $arrayClasses;

        return $parseResult;
    }

    /**
     * Summary of parseToTAGConsultaClasse
     * @param string $inNumClassrom
     * @param OutConsultaTurmaClasse $outConsultaTurmaClasse
     *
     * @return Classroom
     */
    public static function parseToTAGConsultaClasse($inNumClassroom, OutConsultaTurmaClasse $outConsultaTurmaClasse)
    {
        $classroom = Classroom::model()->find("gov_id = :gov_id", ["gov_id" => $inNumClassroom]);
        $classroom->name = $outConsultaTurmaClasse->getOutDescricaoTurma();
        $classroom->initial_hour = substr($outConsultaTurmaClasse->getOutHorarioInicioAula(), 0, 2);
        $classroom->initial_minute = substr($outConsultaTurmaClasse->getOutHorarioInicioAula(), -2);
        $classroom->final_hour = substr($outConsultaTurmaClasse->getOutHorarioFimAula(), 0, 2);
        $classroom->final_minute = substr($outConsultaTurmaClasse->getOutHorarioFimAula(), -2);
        $classroom->edcenso_stage_vs_modality_fk = self::convertTipoEnsinoToStage(
            $outConsultaTurmaClasse->getOutCodTipoEnsino(), $outConsultaTurmaClasse->getOutCodSerieAno()
        );
        $classroom->school_year = $outConsultaTurmaClasse->getOutAnoLetivo();
        $classroom->turn = self::convertCodTurno($outConsultaTurmaClasse->getOutCodTurno());
        $classroom->sedsp_acronym = $outConsultaTurmaClasse->getOutTurma();
        $schoolUnity = SedspSchoolUnities::model()->find('code = :code', [':code' => $outConsultaTurmaClasse->getOutCodUnidade()]);
        $classroom->sedsp_school_unity_fk = $schoolUnity->id;
        $classroom->sedsp_classnumber = $outConsultaTurmaClasse->getOutNumeroSala();
        $classroom->sedsp_max_physical_capacity = $outConsultaTurmaClasse->getOutNrCapacidadeFisicaMaxima();
        $classroom->sedsp_sync = 1;

        return $classroom;
    }

    private static function convertCodTurno($outCodTurno)
    {
        $mapperCodTurno = [
            "1" => 'M', //Manhã
            "2" => 'M', //Manhã
            "3" => 'T', //Tarde
            "4" => 'T', //Tarde
            "5" => 'N', //Noite
            "6" => 'I'  //Integral
        ];

        if (isset($mapperCodTurno[$outCodTurno])) {
            return $mapperCodTurno[$outCodTurno];
        }

        throw new Exception("Código do turno não existe.", 1);
    }

    public static function revertCodTurno($turn)
    {
        $mapperTurn = [
            "M" => '1', //Manhã
            "T" => '3', //Targe
            "N" => '5', //Noite
            "I" => '6', //Integral
        ];

        if (isset($mapperTurn[$turn])) {
            return $mapperTurn[$turn];
        }

        throw new Exception("Código do turno não existe.", 1);
    }


    /**
     * Summary of convertTipoEnsinoToStage
     * @param string $codTipoEnsino
     * @param string $codSerieAno
     * @throws \Exception
     * @return int
     */
    public static function convertTipoEnsinoToStage($codTipoEnsino, $codSerieAno)
    {
        $mapperTipoEnsino = [
            "3" => [
                "0" => 43
            ],
            "6" => [
                "1" => 2,
                "2" => 2,
                "4" => 1,
                "5" => 1,
                "6" => 1,
                "7" => 1,
                "0" => 3
            ],
            "14" => [
                "1" => 14,
                "2" => 15,
                "3" => 16,
                "4" => 17,
                "5" => 18,
                "6" => 19,
                "7" => 20,
                "8" => 21,
                "9" => 41,
                "0" => 24
            ],
            "25" => [
                "1" => 1,
                "2" => 1,
                "3" => 1
            ],
            "26" => [
                "2" => 1,
                "3" => 1
            ],
            "32" => [
                "0" => 2
            ],
            "35" => [
                "1" => 2
            ]
        ];

        if (isset($mapperTipoEnsino[$codTipoEnsino][$codSerieAno])) {
            return $mapperTipoEnsino[$codTipoEnsino][$codSerieAno];
        }

        throw new TipoEnsinoConversionException($codTipoEnsino, $codSerieAno);
    }

    public static function convertStageToTipoEnsino($stage)
    {
        $mapperStage = [
            "43" => [
                "tipoEnsino" => 3,
                "serieAno" => 1
            ],
            "2" => [
                "tipoEnsino" => 6,
                "serieAno" => 1
            ],
            "1" => [
                "tipoEnsino" => 6,
                "serieAno" => 4
            ],
            "3" => [
                "tipoEnsino" => 6,
                "serieAno" => 6
            ],
            "14" => [
                "tipoEnsino" => 14,
                "serieAno" => 1
            ],
            "15" => [
                "tipoEnsino" => 14,
                "serieAno" => 2
            ],
            "16" => [
                "tipoEnsino" => 14,
                "serieAno" => 3
            ],
            "17" => [
                "tipoEnsino" => 14,
                "serieAno" => 4
            ],
            "18" => [
                "tipoEnsino" => 14,
                "serieAno" => 5
            ],
            "19" => [
                "tipoEnsino" => 14,
                "serieAno" => 6
            ],
            "20" => [
                "tipoEnsino" => 14,
                "serieAno" => 7
            ],
            "21" => [
                "tipoEnsino" => 14,
                "serieAno" => 8
            ],
            "41" => [
                "tipoEnsino" => 14,
                "serieAno" => 9
            ],
            "24" => [
                "tipoEnsino" => 14,
                "serieAno" => 0
            ],
            "25" => [
                "tipoEnsino" => 2,
                "serieAno" => 1
            ],
            "26" => [
                "tipoEnsino" => 2,
                "serieAno" => 2
            ],
            "27" => [
                "tipoEnsino" => 2,
                "serieAno" => 3
            ],
            "28" => [
                "tipoEnsino" => 2,
                "serieAno" => 4
            ],
            "29" => [
                "tipoEnsino" => 2,
                "serieAno" => 0
            ],
            "35" => [
                "tipoEnsino" => 2,
                "serieAno" => 1
            ],
            "36" => [
                "tipoEnsino" => 2,
                "serieAno" => 2
            ],
            "37" => [
                "tipoEnsino" => 2,
                "serieAno" => 3
            ],
            "38" => [
                "tipoEnsino" => 2,
                "serieAno" => 4
            ],
        ];

        if (isset($mapperStage[$stage])) {
            return $mapperStage[$stage];
        }

        throw new StageConversionException($stage);
    }

    /**
     * Summary of getNameSerieFromClasse
     * @param OutFormacaoClasse $outFormacaoClasse
     * @param OutTiposEnsino $tiposEnsino
     * @return OutTipoEnsino
     */
    private static function getNameSerieFromClasse($codTipoEnsino, $listaTiposEnsino)
    {
        $codTypeEducation = array_column($listaTiposEnsino->getOutTipoEnsino(), "outCodTipoEnsino");
        $educationTypeIndex = array_search($codTipoEnsino, $codTypeEducation);

        return $listaTiposEnsino->getOutTipoEnsino()[$educationTypeIndex];
    }
}
