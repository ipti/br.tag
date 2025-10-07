<?php

enum EnrollemntStatusTAG: int
{
    case STATUS_ACTIVE = 1;
    case STATUS_TRANSFERRED = 2;
    case STATUS_CANCELED = 3;
    case STATUS_ABANDONED = 4;
    case STATUS_RESTORED = 5;
    case STATUS_APPROVED = 6;
    case STATUS_APPROVEDBYCOUNCIL = 7;
    case STATUS_DISAPPROVED = 8;
    case STATUS_CONCLUDED = 9;
    case STATUS_INDETERMINED = 10;
    case STATUS_DEATH = 11;
    case STATUS_ADVANCED = 12;
    case STATUS_REINTEGRATED = 13;
}

enum StudentEnrollmentStatusSGP: int
{
    case INFORMACAO_INCORRETA = 1;
    case TRANSFERENCIA_MESMA_REDE = 2;
    case TRANSFERENCIA_OUTRA_REDE_PUBLICA = 3;
    case TRANSFERENCIA_OUTRA_REDE_PRIVADA = 4;
    case TRANSFERENCIA_REDE_NAO_IDENTIFICADA = 5;
    case EVASAO = 6;
    case ABANDONO = 7;
    case OBITO_INFORMADO = 8;
    case RECLASSIFICACAO = 9;
    case APROVADO = 10;
    case CONCLUINTE = 11;
    case REPROVADO = 12;
    case CONCLUINTE_ENCCEJA_400H = 19;
    case TRANSFERENCIA_ENTRE_MODALIDADES = 21;
    case TRANCAMENTO_CURSO_TECNICO = 22;

    public function label(): string
    {
        return match ($this) {
            self::INFORMACAO_INCORRETA => 'Informação Incorreta',
            self::TRANSFERENCIA_MESMA_REDE => 'Transferência para outra unidade escolar dentro da mesma rede',
            self::TRANSFERENCIA_OUTRA_REDE_PUBLICA => 'Transferência para outra unidade escolar em outra rede pública',
            self::TRANSFERENCIA_OUTRA_REDE_PRIVADA => 'Transferência para outra unidade escolar em outra rede privada',
            self::TRANSFERENCIA_REDE_NAO_IDENTIFICADA => 'Transferência para outra rede não identificada',
            self::EVASAO => 'Evasão',
            self::ABANDONO => 'Abandono',
            self::OBITO_INFORMADO => 'Óbito Informado',
            self::RECLASSIFICACAO => 'Reclassificação',
            self::APROVADO => 'Aprovado',
            self::CONCLUINTE => 'Concluinte',
            self::REPROVADO => 'Reprovado',
            self::CONCLUINTE_ENCCEJA_400H => 'Concluinte Encceja + 400h',
            self::TRANSFERENCIA_ENTRE_MODALIDADES => 'Transferência entre modalidades (EM <> EJA)',
            self::TRANCAMENTO_CURSO_TECNICO => 'Trancamento de matrícula em curso técnico',
        };
    }
}

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }

    // AlterarDadosPessoais
    public function actionExportPersonalData(): void
    {
        $query = <<<EOD
            select
                sdaa.cpf as ESTUDANTE_CPF,
                sdaa.nis as ESTUDANTE_NU_NIS,
                si.name as ESTUDANTE_NOME,
                si.name as ESTUDANTE_NOME_SOCIAL,
                si.birthday as ESTUDANTE_DT_NASCIMENTO,
                si.filiation_1 as ESTUDANTE_MAE_NOME,
                si2.inep_id as CO_ENTIDADE,
                si2.name as NO_ENTIDADE,
                se.id as CO_MATRICULA_REDE,
                DATE_FORMAT(c2.start_date, "%d/%m/%Y") as DATA_INICIO_PERIODO_LETIVO,
                DATE_FORMAT(IFNULL(se.enrollment_date, c2.start_date), "%d/%m/%Y") as DATA_INICIO_MATRICULA,
                c.school_year as NU_ANO_MATRICULA,
                case
                    when c.aee = 1 then 0
                    else esvm.edcenso_associated_stage_id
                end as ESTUDANTE_ETAPA_DE_ENSINO,
                esvm.organization_form_sgp as TURMA_FORMA_ORGANIZACAO,
                esvm.organization_total_quantity_sgp as TURMA_ORGANIZACAO_QUANTIDADE_TOTAL,
                '' as ESTUDANTE_ANO_PERIODO,
                '' as ESTUDANTE_E_MAIL,
                case
                    when c.turn = 'I' then 1
                    else 0
                end as ESTUDANTE_INTEGRAL,
                sdaa.rg_number as ESTUDANTE_NU_RG,
                eoie.name as ESTUDANTE_ORGAO_EMISSOR_RG,
                '' as ESTUDANTE_NU_CNH,
                sdaa.civil_register_enrollment_number as ESTUDANTE_NU_CERTIDAO_NASCIMENTO,
                si.responsable_cpf as RESPONSAVEL_NU_CPF,
                si.responsable_nis as RESPONSAVEL_NU_NIS,
                sdaa.address as ESTUDANTE_DS_LOGRADOURO_RES,
                sdaa.neighborhood as ESTUDANTE_BAIRRO_RES,
                sdaa.`number` as ESTUDANTE_NU_ENDERECO_RES,
                sdaa.cep as ESTUDANTE_CEP_RES,
                ec.id as ESTUDANTE_CO_MUNICIPIO_RES,
                ec.edcenso_uf_fk  as ESTUDANTE_CO_UF_RES,
                si.color_race as ESTUDANTE_RACA_COR,
                si.deficiency_type_low_vision as ESTUDANTE_DEFICIENCIA_BAIXA_VISAO,
                si.deficiency_type_blindness as ESTUDANTE_DEFICIENCIA_CEGUEIRA,
                si.deficiency_type_disability_hearing as ESTUDANTE_DEFICIENCIA_AUDITIVA,
                si.deficiency_type_phisical_disability as ESTUDANTE_DEFICIENCIA_FISICA,
                si.deficiency_type_intelectual_disability as ESTUDANTE_DEFICIENCIA_INTELECTUAL,
                si.deficiency_type_deafness as ESTUDANTE_DEFICIENCIA_SURDEZ,
                si.deficiency_type_deafblindness as ESTUDANTE_DEFICIENCIA_SURDOCEGUEIRA,
                si.deficiency_type_multiple_disabilities as ESTUDANTE_DEFICIENCIA_MULTIPLA,
                si.deficiency_type_autism as ESTUDANTE_TRANSTORNO_ESPECTRO_AUTISTA,
                si.deficiency_type_gifted as ESTUDANTE_ALTAS_HABILIDADES_SUPERDOTACAO,
                si.sex as ESTUDANTE_GENERO,
                '' as ESTUDANTE_PPL
            from
                student_identification si
            join student_documents_and_address sdaa
                    using(id)
            join student_enrollment se on
                se.student_fk = si.id
            join classroom c on
                c.id = se.classroom_fk
            join edcenso_stage_vs_modality esvm on
                esvm.id = c.edcenso_stage_vs_modality_fk
            join calendar c2 on
                c.calendar_fk = c2.id
            join school_identification si2 on
                c.school_inep_fk = si2.inep_id
            join edcenso_city ec on
                ec.id = si.edcenso_city_fk
            left join edcenso_organ_id_emitter eoie on
                sdaa.rg_number_edcenso_organ_id_emitter_fk = eoie.id
            where
                c.school_year = :year
            order by
                si2.inep_id,
                si.id,
                se.id;
        EOD;
        try {
            $command = Yii::app()->db->createCommand($query);
            $command->bindParam(':year', Yii::app()->user->year, PDO::PARAM_INT);
            $reader = $command->query();

            // Nome do arquivo
            $filename = 'alterar_dados_pessoais_estudantes_' . date('Y-m-d_H-i-s') . '.xlsx';

            // Criar writer
            // Criar exportador simples
            $exporter = new SimpleExcelExporter($filename);

            // Ler primeira linha para extrair cabeçalhos
            $firstRow = $reader->read();
            if ($firstRow !== false) {
                $exporter->setHeaders(array_keys($firstRow));
                $exporter->addRow(array_values($firstRow));
            }

            // Linhas restantes
            while (($row = $reader->read()) !== false) {
                $row = $this->parsePersonalData($row);
                $exporter->addRow(array_values($row));
            }

            // Exportar
            $exporter->export();
            Yii::app()->end();
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    }

    // Cadastro e matricula
    public function actionExportChangeEnrollment(): void
    {
        $query = <<<EOD
           SELECT
                '' AS ID_SGP_MATRICULA, -- A
                1 AS EDITAR_MATRICULA, -- B
                '' AS STATUS_MATRICULA, -- C
                se.status AS MOTIVO_STATUS_MATRICULA, -- D
                '' AS DT_CRIACAO_MATRICULA, -- E
                sdaa.cpf AS ESTUDANTE_CPF, -- F
                si.name AS ESTUDANTE_NOME, -- G
                si.name AS ESTUDANTE_NOME_SOCIAL, -- H
                si2.inep_id AS CO_ENTIDADE, -- I
                si2.name AS NO_ENTIDADE, -- J
                case when c.aee = 1 then 0 else esvm.edcenso_associated_stage_id end AS ESTUDANTE_ETAPA_DE_ENSINO, -- K
                '' AS ESTUDANTE_ANO_PERIODO, -- L
                c.school_year AS NU_ANO_MATRICULA, -- M
                DATE_FORMAT(IFNULL(se.enrollment_date, c2.start_date), "%d/%m/%Y") AS DATA_INICIO_MATRICULA, -- N
                DATE_FORMAT(c2.start_date, "%d/%m/%Y") AS DATA_INICIO_PERIODO_LETIVO, -- O
                se.id AS CO_MATRICULA_REDE, -- P
                esvm2.organization_form_sgp AS TURMA_FORMA_ORGANIZACAO, -- Q
                esvm2.organization_total_quantity_sgp AS TURMA_ORGANIZACAO_QUANTIDADE_TOTAL, -- R
                CASE WHEN c.turn = 'I' THEN 1 ELSE 0 END AS ESTUDANTE_INTEGRAL, -- S
                '' AS ESTUDANTE_PPL -- T
            FROM
                student_identification si
            JOIN student_documents_and_address sdaa USING(id)
            JOIN student_enrollment se ON se.student_fk = si.id
            JOIN classroom c ON c.id = se.classroom_fk
            JOIN edcenso_stage_vs_modality esvm ON esvm.id = c.edcenso_stage_vs_modality_fk
            JOIN edcenso_stage_vs_modality esvm2 ON esvm2.id = esvm.edcenso_associated_stage_id
            JOIN calendar c2 ON c.calendar_fk = c2.id
            JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
            LEFT JOIN edcenso_organ_id_emitter eoie
                ON sdaa.rg_number_edcenso_organ_id_emitter_fk = eoie.id
            WHERE
                c.school_year = :year
            order by si2.inep_id, si.id, se.id;

        EOD;

        try {
            $command = Yii::app()->db->createCommand($query);
            $command->bindParam(':year', Yii::app()->user->year, PDO::PARAM_INT);
            $reader = $command->query();

            // Nome do arquivo
            $filename = 'alterar_matriculas' . date('Y-m-d_H-i-s') . '.xlsx';

            $exporter = new SimpleExcelExporter($filename);

            // Ler primeira linha para extrair cabeçalhos
            $firstRow = $reader->read();
            if ($firstRow !== false) {
                $exporter->setHeaders(array_keys($firstRow));
                $exporter->addRow(array_values($firstRow));
            }

            // Linhas restantes
            while (($row = $reader->read()) !== false) {
                $row = $this->parseChangeEnrollment($row);
                $exporter->addRow(array_values($row));
            }

            // Exportar
            $exporter->export();

            Yii::app()->end();
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    }

    // Cadastro e matricula
    public function actionExportRenrollment(): void
    {
        $query = <<<EOD
            SELECT
                '' as ID_SGP_MATRICULA,
                se.id as CO_MATRICULA_REDE,
                '' as EDITAR_DADOS,
                sdaa.cpf as ESTUDANTE_CPF,
                sdaa.nis as ESTUDANTE_NU_NIS,
                si.name as ESTUDANTE_NOME,
                si.name as ESTUDANTE_NOME_SOCIAL,
                si.birthday as ESTUDANTE_DT_NASCIMENTO,
                si.filiation_1 as ESTUDANTE_MAE_NOME,
                si2.inep_id as CO_ENTIDADE,
                si2.name as NO_ENTIDADE,
                sdaa.rg_number as ESTUDANTE_NU_RG,
                eoie.name as ESTUDANTE_ORGAO_EMISSOR_RG,
                '' as ESTUDANTE_NU_CNH,
                sdaa.civil_register_enrollment_number as ESTUDANTE_NU_CERTIDAO_NASCIMENTO,
                si.responsable_cpf as RESPONSAVEL_NU_CPF,
                si.responsable_nis as RESPONSAVEL_NU_NIS,
                sdaa.address as ESTUDANTE_DS_LOGRADOURO_RES,
                sdaa.neighborhood as ESTUDANTE_BAIRRO_RES,
                sdaa.`number` as ESTUDANTE_NU_ENDERECO_RES,
                sdaa.cep as ESTUDANTE_CEP_RES,
                ec.id AS ESTUDANTE_CO_MUNICIPIO_RES,
                ec.edcenso_uf_fk AS ESTUDANTE_CO_UF_RES,
                `si`.`color_race` as ESTUDANTE_RACA_COR,
                si.deficiency_type_low_vision as ESTUDANTE_DEFICIENCIA_BAIXA_VISAO,
                si.deficiency_type_blindness as ESTUDANTE_DEFICIENCIA_CEGUEIRA,
                si.deficiency_type_disability_hearing as ESTUDANTE_DEFICIENCIA_AUDITIVA,
                si.deficiency_type_phisical_disability as ESTUDANTE_DEFICIENCIA_FISICA,
                si.deficiency_type_intelectual_disability as ESTUDANTE_DEFICIENCIA_INTELECTUAL,
                si.deficiency_type_deafness as ESTUDANTE_DEFICIENCIA_SURDEZ,
                si.deficiency_type_deafblindness as ESTUDANTE_DEFICIENCIA_SURDOCEGUEIRA,
                si.deficiency_type_multiple_disabilities as ESTUDANTE_DEFICIENCIA_MULTIPLA,
                si.deficiency_type_autism as ESTUDANTE_TRANSTORNO_ESPECTRO_AUTISTA,
                si.deficiency_type_gifted as ESTUDANTE_ALTAS_HABILIDADES_SUPERDOTACAO,
                si.sex as ESTUDANTE_GENERO
            from
                student_identification si
            join student_documents_and_address sdaa
                    using(id)
            join student_enrollment se on
                se.student_fk = si.id
            join classroom c on
                c.id = se.classroom_fk
            join school_identification si2 on
                c.school_inep_fk = si2.inep_id
            JOIN edcenso_city ec ON ec.id = si.edcenso_city_fk
            left join edcenso_organ_id_emitter eoie on
                sdaa.rg_number_edcenso_organ_id_emitter_fk = eoie.id
            WHERE
                c.school_year = :year
        EOD;

        try {
            $command = Yii::app()->db->createCommand($query);
            $command->bindParam(':year', Yii::app()->user->year, PDO::PARAM_INT);
            $reader = $command->query();

            // Nome do arquivo
            $filename = 'cadastro_rematricula_estudantes_' . date('Y-m-d_H-i-s') . '.xlsx';

            $exporter = new SimpleExcelExporter($filename);

            // Ler primeira linha para extrair cabeçalhos
            $firstRow = $reader->read();
            if ($firstRow !== false) {
                $exporter->setHeaders(array_keys($firstRow));
                $exporter->addRow(array_values($firstRow));
            }

            // Linhas restantes
            while (($row = $reader->read()) !== false) {
                $row = $this->parseRowReenrolment($row);
                $exporter->addRow(array_values($row));
            }

            // Exportar
            $exporter->export();

            Yii::app()->end();
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    }

    private function parsePersonalData($row)
    {
        if (TagUtils::isStageEJA($row['ESTUDANTE_ETAPA_DE_ENSINO'])) {
            // @TODO: ADICIONAR CALCULO PARA DETERMINAR VALOR
            $row['ESTUDANTE_ANO_PERIODO'] = 2;
        }

        return $row;
    }

    private function parseChangeEnrollment($row)
    {
        $row['STATUS_MATRICULA'] = $this->isActiveEnrollment($row['MOTIVO_STATUS_MATRICULA']);

        $row['MOTIVO_STATUS_MATRICULA'] = $this->mapEnrollmentStatus($row['MOTIVO_STATUS_MATRICULA'])->value;

        if (TagUtils::isStageEJA($row['ESTUDANTE_ETAPA_DE_ENSINO'])) {
            // $row['TURMA_FORMA_ORGANIZACAO'] = 2;
            // $row['TURMA_ORGANIZACAO_QUANTIDADE_TOTAL'] = 2;
        }

        return $row;
    }

    private function parseRowReenrolment($row)
    {
        if (TagUtils::isStageEJA($row['ESTUDANTE_ETAPA_DE_ENSINO'])) {
            // $row['TURMA_FORMA_ORGANIZACAO'] = 2;
            // $row['TURMA_ORGANIZACAO_QUANTIDADE_TOTAL'] = 2;
        }

        return $row;
    }

    private function mapEnrollmentStatus($tagEnrollmentStatus)
    {
        if ($tagEnrollmentStatus == null) {
            return StudentEnrollmentStatusSGP::INFORMACAO_INCORRETA;
        }

        return match (EnrollemntStatusTAG::from($tagEnrollmentStatus)) {
            EnrollemntStatusTAG::STATUS_INDETERMINED => StudentEnrollmentStatusSGP::INFORMACAO_INCORRETA,

            EnrollemntStatusTAG::STATUS_TRANSFERRED => StudentEnrollmentStatusSGP::TRANSFERENCIA_MESMA_REDE,

            EnrollemntStatusTAG::STATUS_ABANDONED => StudentEnrollmentStatusSGP::ABANDONO,

            EnrollemntStatusTAG::STATUS_DEATH => StudentEnrollmentStatusSGP::OBITO_INFORMADO,

            EnrollemntStatusTAG::STATUS_ADVANCED => StudentEnrollmentStatusSGP::RECLASSIFICACAO,

            EnrollemntStatusTAG::STATUS_APPROVED => StudentEnrollmentStatusSGP::APROVADO,

            EnrollemntStatusTAG::STATUS_CONCLUDED => StudentEnrollmentStatusSGP::CONCLUINTE,

            EnrollemntStatusTAG::STATUS_DISAPPROVED => StudentEnrollmentStatusSGP::REPROVADO,

            EnrollemntStatusTAG::STATUS_CANCELED => StudentEnrollmentStatusSGP::TRANCAMENTO_CURSO_TECNICO,

            default => StudentEnrollmentStatusSGP::INFORMACAO_INCORRETA
        };
    }

    private function isActiveEnrollment($status)
    {
        $acceptedEnrollemnts = [
            null,
            EnrollemntStatusTAG::STATUS_ACTIVE->value,
            // EnrollemntStatusTAG::STATUS_TRANSFERRED->value,
            // EnrollemntStatusTAG::STATUS_CANCELED->value,
            // EnrollemntStatusTAG::STATUS_ABANDONED->value,
            EnrollemntStatusTAG::STATUS_RESTORED->value,
            EnrollemntStatusTAG::STATUS_APPROVED->value,
            EnrollemntStatusTAG::STATUS_APPROVEDBYCOUNCIL->value,
            EnrollemntStatusTAG::STATUS_DISAPPROVED->value,
            EnrollemntStatusTAG::STATUS_CONCLUDED->value,
            // EnrollemntStatusTAG::STATUS_INDETERMINED->value,
            // EnrollemntStatusTAG::STATUS_DEATH->value,
            EnrollemntStatusTAG::STATUS_ADVANCED->value,
            EnrollemntStatusTAG::STATUS_REINTEGRATED->value,
        ];

        $statusList = new CList($acceptedEnrollemnts, true);
        return $statusList->contains($status) ? 'ATIVO' : 'INATIVO';
    }
}
