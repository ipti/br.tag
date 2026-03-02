<?php

/**
 * Console command para remover duplicatas de professores que compartilham o mesmo CPF.
 *
 * ESTRATÉGIA:
 *   - Mantém o registro mais antigo (menor id) como definitivo (keeper)
 *   - Mescla dados do duplicado no keeper (campos NULL/vazios do keeper recebem valores do duplicado)
 *   - Redireciona todos os vínculos (turmas, faltas, schedule, class_board, substitute_instructor)
 *   - Remove os registros duplicados
 *
 * Uso:
 *   php yiic deduplicateinstructors           -- modo preview (dry-run, não altera nada)
 *   php yiic deduplicateinstructors --commit  -- aplica as alterações no banco
 *
 * !! FAÇA BACKUP DO BANCO ANTES DE USAR COM --commit !!
 */
class DeduplicateInstructorsCommand extends CConsoleCommand
{
    /** @var bool Se true, aplica as alterações. Se false (padrão), apenas mostra o que seria feito. */
    public $commit = false;

    public function run($args)
    {
        // Lê flag --commit dos argumentos
        if (in_array('--commit', $args)) {
            $this->commit = true;
        }

        $mode = $this->commit ? 'EXECUÇÃO REAL' : 'DRY-RUN (preview)';
        $this->log("=== Deduplicação de Professores — {$mode} ===\n");

        if (!$this->commit) {
            $this->log("  Nenhuma alteração será feita. Use --commit para aplicar.\n\n");
        }

        // Busca grupos de CPF com mais de um cadastro
        $groups = Yii::app()->db->createCommand("
            SELECT da.cpf, MIN(ii.id) AS keeper_id, COUNT(*) AS total
            FROM instructor_documents_and_address da
            JOIN instructor_identification ii ON ii.id = da.id
            WHERE da.cpf IS NOT NULL AND TRIM(da.cpf) != ''
            GROUP BY da.cpf
            HAVING COUNT(*) > 1
        ")->queryAll();

        if (empty($groups)) {
            $this->log("Nenhum CPF duplicado encontrado. Banco está limpo!\n");
            return 0;
        }

        $this->log(sprintf("Encontrados %d grupo(s) de CPF duplicados:\n\n", count($groups)));

        $totalResolved = 0;

        foreach ($groups as $group) {
            $cpf      = $group['cpf'];
            $keeperId = (int) $group['keeper_id'];

            // Busca todos os duplicados (não-keepers) do grupo
            $duplicates = InstructorDocumentsAndAddress::model()->findAll(
                'cpf = :cpf AND id != :keeper_id',
                [':cpf' => $cpf, ':keeper_id' => $keeperId]
            );

            $keeper    = InstructorIdentification::model()->findByPk($keeperId);
            $keeperDoc = InstructorDocumentsAndAddress::model()->findByPk($keeperId);

            $this->log(sprintf(
                "CPF: %s | Keeper: [ID %d] %s\n",
                $cpf,
                $keeperId,
                $keeper ? $keeper->name : '(não encontrado)'
            ));

            foreach ($duplicates as $dupDoc) {
                $dupInstructor = InstructorIdentification::model()->findByPk($dupDoc->id);
                $this->log(sprintf(
                    "  → Duplicata: [ID %d] %s — será REMOVIDA e vínculos migrados\n",
                    $dupDoc->id,
                    $dupInstructor ? $dupInstructor->name : '(não encontrado)'
                ));

                if ($this->commit) {
                    $txn = Yii::app()->db->beginTransaction();
                    try {
                        // 1. Mescla instructor_identification
                        $this->mergeIdentification($keeper, $dupInstructor);

                        // 2. Mescla instructor_documents_and_address
                        if ($keeperDoc && $dupDoc) {
                            $this->mergeDocuments($keeperDoc, $dupDoc);
                        }

                        // 3. Mescla instructor_variable_data
                        $keeperVd = InstructorVariableData::model()->findByPk($keeperId);
                        $dupVd    = InstructorVariableData::model()->findByPk($dupDoc->id);
                        if ($keeperVd && $dupVd) {
                            $this->mergeVariableData($keeperVd, $dupVd);
                        }

                        // 4. Migrar instructor_teaching_data (log interno no método)
                        $this->migrateTeachingData($keeperId, $dupDoc->id);

                        // 5. Migrar instructor_faults
                        $n = Yii::app()->db->createCommand()->update(
                            'instructor_faults',
                            ['instructor_fk' => $keeperId],
                            'instructor_fk = :dup',
                            [':dup' => $dupDoc->id]
                        );
                        $this->log("     migrar instructor_faults: {$n} registro(s)\n");

                        // 6. Migrar schedule
                        $n = Yii::app()->db->createCommand()->update(
                            'schedule',
                            ['instructor_fk' => $keeperId],
                            'instructor_fk = :dup',
                            [':dup' => $dupDoc->id]
                        );
                        $this->log("     migrar schedule: {$n} registro(s)\n");

                        // 7. Migrar class_board
                        $n = Yii::app()->db->createCommand()->update(
                            'class_board',
                            ['instructor_fk' => $keeperId],
                            'instructor_fk = :dup',
                            [':dup' => $dupDoc->id]
                        );
                        $this->log("     migrar class_board: {$n} registro(s)\n");

                        // 8. Migrar substitute_instructor
                        $n = Yii::app()->db->createCommand()->update(
                            'substitute_instructor',
                            ['instructor_fk' => $keeperId],
                            'instructor_fk = :dup',
                            [':dup' => $dupDoc->id]
                        );
                        $this->log("     migrar substitute_instructor: {$n} registro(s)\n");

                        // 9. Remover dados auxiliares do duplicado
                        if ($dupDoc) {
                            $dupDoc->delete();
                        }
                        if ($dupVd) {
                            $dupVd->delete();
                        }

                        // 10. Desvincular user antes de deletar o instructor
                        if ($dupInstructor) {
                            $dupInstructor->users_fk = null;
                            $dupInstructor->save(false, ['users_fk']);
                            $dupInstructor->delete();
                        }

                        $txn->commit();
                        $this->log("     ✓ Migração concluída com sucesso\n");

                    } catch (Exception $e) {
                        $txn->rollback();
                        $this->log("     ✗ ERRO: " . $e->getMessage() . "\n");
                        $this->log("       Transação revertida. Continuando com próximo grupo...\n");
                    }
                } else {
                    // Dry-run: mostra contagens sem alterar nada
                    $counts = Yii::app()->db->createCommand("
                        SELECT
                            (SELECT COUNT(*) FROM instructor_teaching_data WHERE instructor_fk = :dup) AS teaching_data,
                            (SELECT COUNT(*) FROM instructor_faults        WHERE instructor_fk = :dup) AS faults,
                            (SELECT COUNT(*) FROM schedule                 WHERE instructor_fk = :dup) AS schedule,
                            (SELECT COUNT(*) FROM class_board              WHERE instructor_fk = :dup) AS class_board,
                            (SELECT COUNT(*) FROM substitute_instructor    WHERE instructor_fk = :dup) AS substitute
                    ")->queryRow(true, [':dup' => $dupDoc->id]);

                    $this->log("     [preview] instructor_teaching_data: {$counts['teaching_data']} vínculo(s) a migrar\n");
                    $this->log("     [preview] instructor_faults:         {$counts['faults']} registro(s) a migrar\n");
                    $this->log("     [preview] schedule:                  {$counts['schedule']} registro(s) a migrar\n");
                    $this->log("     [preview] class_board:               {$counts['class_board']} registro(s) a migrar\n");
                    $this->log("     [preview] substitute_instructor:     {$counts['substitute']} registro(s) a migrar\n");
                }

                $totalResolved++;
            }

            $this->log("\n");
        }

        $this->log(sprintf("Total de duplicatas %s: %d\n", $this->commit ? 'resolvidas' : 'encontradas', $totalResolved));

        if (!$this->commit) {
            $this->log("\nPara aplicar, execute:\n  php yiic deduplicateinstructors --commit\n");
        }

        return 0;
    }

    /**
     * Mescla campos de InstructorIdentification:
     * campos NULL/vazios no keeper recebem os valores do duplicado.
     */
    private function mergeIdentification($keeper, $dup)
    {
        if (!$keeper || !$dup) return;

        $fields = ['civil_name', 'email', 'nis', 'inep_id', 'filiation_1', 'filiation_2', 'id_indigenous_people'];
        $changed = false;

        foreach ($fields as $field) {
            if (empty($keeper->$field) && !empty($dup->$field)) {
                $this->log("     merge identification.{$field}: '{$dup->$field}'\n");
                $keeper->$field = $dup->$field;
                $changed = true;
            }
        }

        // Campos numéricos (0 é considerado "vazio")
        $numFields = ['edcenso_uf_fk', 'edcenso_city_fk', 'edcenso_nation_fk'];
        foreach ($numFields as $field) {
            if (empty($keeper->$field) && !empty($dup->$field)) {
                $this->log("     merge identification.{$field}: {$dup->$field}\n");
                $keeper->$field = $dup->$field;
                $changed = true;
            }
        }

        if ($changed) {
            $keeper->save(false);
        }
    }

    /**
     * Mescla campos de InstructorDocumentsAndAddress.
     */
    private function mergeDocuments($keeper, $dup)
    {
        $fields  = ['cep', 'address', 'address_number', 'complement', 'neighborhood'];
        $changed = false;

        foreach ($fields as $field) {
            if (empty($keeper->$field) && !empty($dup->$field)) {
                $this->log("     merge documents.{$field}: '{$dup->$field}'\n");
                $keeper->$field = $dup->$field;
                $changed = true;
            }
        }

        $numFields = ['area_of_residence', 'diff_location', 'edcenso_uf_fk', 'edcenso_city_fk'];
        foreach ($numFields as $field) {
            if (empty($keeper->$field) && !empty($dup->$field)) {
                $this->log("     merge documents.{$field}: {$dup->$field}\n");
                $keeper->$field = $dup->$field;
                $changed = true;
            }
        }

        if ($changed) {
            $keeper->save(false);
        }
    }

    /**
     * Mescla campos de InstructorVariableData.
     */
    private function mergeVariableData($keeper, $dup)
    {
        $numFields = [
            'scholarity',
            'high_education_situation_1', 'high_education_course_code_1_fk', 'high_education_institution_code_1_fk',
            'high_education_situation_2', 'high_education_course_code_2_fk', 'high_education_institution_code_2_fk',
            'high_education_situation_3', 'high_education_course_code_3_fk', 'high_education_institution_code_3_fk',
        ];
        $changed = false;

        foreach ($numFields as $field) {
            if (empty($keeper->$field) && !empty($dup->$field)) {
                $this->log("     merge variable_data.{$field}: {$dup->$field}\n");
                $keeper->$field = $dup->$field;
                $changed = true;
            }
        }

        if ($changed) {
            $keeper->save(false);
        }
    }

    /**
     * Migra vínculos de turma do duplicado para o keeper,
     * evitando duplicar vínculos já existentes para a mesma turma.
     */
    private function migrateTeachingData($keeperId, $dupId)
    {
        $dupTeachings = InstructorTeachingData::model()->findAllByAttributes(['instructor_fk' => $dupId]);

        foreach ($dupTeachings as $td) {
            // Verifica se o keeper já tem vínculo com essa turma
            $exists = InstructorTeachingData::model()->findByAttributes([
                'instructor_fk'    => $keeperId,
                'classroom_id_fk'  => $td->classroom_id_fk,
            ]);

            if ($exists) {
                $this->log("     skip teaching_data classroom_id={$td->classroom_id_fk} (keeper já vinculado)\n");
                $td->delete();
            } else {
                $this->log("     migrar teaching_data classroom_id={$td->classroom_id_fk} → keeper\n");
                $td->instructor_fk = $keeperId;
                $td->save(false, ['instructor_fk']);
            }
        }
    }

    private function log($msg)
    {
        echo $msg;
        Yii::log(rtrim($msg), CLogger::LEVEL_INFO, 'application.dedup');
    }

    public function getHelp()
    {
        return <<<EOD
USAGE
  yiic deduplicateinstructors [--commit]

DESCRIPTION
  Identifica professores cadastrados mais de uma vez com o mesmo CPF,
  mantém o registro mais antigo (menor ID) e:
    - Mescla campos dos duplicados no keeper (onde keeper tiver NULL/vazio)
    - Redireciona vínculos de turma, faltas, schedule, class_board e substitute_instructor
    - Remove os registros duplicados

  Sem --commit: apenas exibe o que seria feito (dry-run)
  Com --commit: aplica as alterações com transação por duplicata

  FAÇA BACKUP DO BANCO ANTES DE USAR COM --commit!

EXEMPLOS
  php yiic deduplicateinstructors
  php yiic deduplicateinstructors --commit
EOD;
    }
}
