<?php
class DefaultController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionExportPersonalData(): void
    {
        $query = <<<EOD
            SELECT
                '' as ID_SGP_MATRICULA,
                '' as CO_MATRICULA_REDE,
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
                '' as RESPONSAVEL_NU_NIS,
                sdaa.address as ESTUDANTE_DS_LOGRADOURO_RES,
                sdaa.neighborhood as ESTUDANTE_BAIRRO_RES,
                sdaa.`number` as ESTUDANTE_NU_ENDERECO_RES,
                sdaa.cep as ESTUDANTE_CEP_RES,
                '' as ESTUDANTE_CO_MUNICIPIO_RES,
                '' as ESTUDANTE_CO_UF_RES,
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
                $exporter->addRow(array_values($row));
            }

            // Exportar
            $exporter->export();
            Yii::app()->end();
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    }

    public function actionExportRenrollment(): void
    {
        $query = <<<EOD
            SELECT
                sdaa.cpf AS ESTUDANTE_CPF,
                sdaa.nis AS ESTUDANTE_NU_NIS,
                si.name AS ESTUDANTE_NOME,
                si.name AS ESTUDANTE_NOME_SOCIAL,
                si.birthday AS ESTUDANTE_DT_NASCIMENTO,
                si.filiation_1 AS ESTUDANTE_MAE_NOME,
                si2.inep_id AS CO_ENTIDADE,
                si2.name AS NO_ENTIDADE,
                '' AS CO_MATRICULA_REDE,
                c2.start_date AS DATA_INICIO_PERIODO_LETIVO,
                se.enrollment_date AS DATA_INICIO_MATRICULA,
                c.school_year AS NU_ANO_MATRICULA,
                esvm.edcenso_associated_stage_id AS ESTUDANTE_ETAPA_DE_ENSINO,
                1 AS TURMA_FORMA_ORGANIZACAO,
                '' AS TURMA_ORGANIZACAO_QUANTIDADE_TOTAL,
                '' AS ESTUDANTE_ANO_PERIODO,
                '' AS ESTUDANTE_E_MAIL,
                case when c.turn = 'I' then 1 else 0 end AS ESTUDANTE_INTEGRAL,
                sdaa.rg_number AS ESTUDANTE_NU_RG,
                eoie.name AS ESTUDANTE_ORGAO_EMISSOR_RG,
                '' AS ESTUDANTE_NU_CNH,
                sdaa.civil_register_enrollment_number AS ESTUDANTE_NU_CERTIDAO_NASCIMENTO,
                si.responsable_cpf AS RESPONSAVEL_NU_CPF,
                '' AS RESPONSAVEL_NU_NIS,
                sdaa.address AS ESTUDANTE_DS_LOGRADOURO_RES,
                sdaa.neighborhood AS ESTUDANTE_BAIRRO_RES,
                sdaa.`number` AS ESTUDANTE_NU_ENDERECO_RES,
                sdaa.cep AS ESTUDANTE_CEP_RES,
                '' AS ESTUDANTE_CO_MUNICIPIO_RES,
                '' AS ESTUDANTE_CO_UF_RES,
                si.color_race AS ESTUDANTE_RACA_COR,
                si.deficiency_type_low_vision AS ESTUDANTE_DEFICIENCIA_BAIXA_VISAO,
                si.deficiency_type_blindness AS ESTUDANTE_DEFICIENCIA_CEGUEIRA,
                si.deficiency_type_disability_hearing AS ESTUDANTE_DEFICIENCIA_AUDITIVA,
                si.deficiency_type_phisical_disability AS ESTUDANTE_DEFICIENCIA_FISICA,
                si.deficiency_type_intelectual_disability AS ESTUDANTE_DEFICIENCIA_INTELECTUAL,
                si.deficiency_type_deafness AS ESTUDANTE_DEFICIENCIA_SURDEZ,
                si.deficiency_type_deafblindness AS ESTUDANTE_DEFICIENCIA_SURDOCEGUEIRA,
                si.deficiency_type_multiple_disabilities AS ESTUDANTE_DEFICIENCIA_MULTIPLA,
                si.deficiency_type_autism AS ESTUDANTE_TRANSTORNO_ESPECTRO_AUTISTA,
                si.deficiency_type_gifted AS ESTUDANTE_ALTAS_HABILIDADES_SUPERDOTACAO,
                si.sex AS ESTUDANTE_GENERO,
                '' AS ESTUDANTE_PPL
            FROM
                student_identification si
            JOIN student_documents_and_address sdaa USING(id)
            JOIN student_enrollment se ON se.student_fk = si.id
            JOIN classroom c ON c.id = se.classroom_fk
            join edcenso_stage_vs_modality esvm on esvm.id = c.edcenso_stage_vs_modality_fk
            JOIN calendar c2 ON c.calendar_fk = c2.id
            JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
            LEFT JOIN edcenso_organ_id_emitter eoie ON sdaa.rg_number_edcenso_organ_id_emitter_fk = eoie.id
            WHERE
                c.school_year = :year;
        EOD;
        try {
            $command = Yii::app()->db->createCommand($query);
            $command->bindParam(':year', Yii::app()->user->year, PDO::PARAM_INT);
            $reader = $command->query();

            // Nome do arquivo
            $filename = 'cadastro_rematricula_estudantes_' . date('Y-m-d_H-i-s') . '.csv';

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

    private function parseRowReenrolment($row)
    {
        if (TagUtils::isStageEJA($row['ESTUDANTE_ETAPA_DE_ENSINO'])) {
            $row['TURMA_FORMA_ORGANIZACAO'] = 2;
            $row['TURMA_ORGANIZACAO_QUANTIDADE_TOTAL'] = 2;
        }

        return $row;
    }
}
