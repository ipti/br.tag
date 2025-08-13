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
            $filename = 'alterar_dados_pessoais_estudantes' . date('Y-m-d_H-i-s') . '.csv';

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $output = fopen('php://output', 'w');

            // Cabeçalho: pega os nomes das colunas do primeiro registro
            $firstRow = $reader->read();
            if ($firstRow !== false) {
                fputcsv($output, array_keys($firstRow));
                fputcsv($output, $firstRow);
            }

            // Restante dos registros
            while (($row = $reader->read()) !== false) {
                fputcsv($output, $row);
            }

            fclose($output);
            Yii::app()->end();
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    }
}
