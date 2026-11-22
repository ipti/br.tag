<?php

/**
 * Alunos de uma escola para os sistemas externos.
 */
class ApiStudentService
{
    /**
     * Situações de matrícula que contam como aluno da escola no ano, as mesmas
     * dos relatórios de alunos: matriculado, aprovado, aprovado pelo conselho,
     * reprovado, concluinte, indeterminado ou sem situação.
     */
    private const ACTIVE_ENROLLMENT_CONDITION = '(se.status IN (1, 6, 7, 8, 9, 10) OR se.status IS NULL)';

    /**
     * Alunos com matrícula ativa em alguma turma da escola no ano letivo.
     * O aluno aparece uma vez só, mesmo com mais de uma matrícula na escola
     * (ex.: turma regular e AEE).
     *
     * @return array<int, array{aluno_id: int, nome: string}>
     */
    public function listBySchool(string $schoolInep, int $year): array
    {
        $rows = Yii::app()->db->createCommand()
            ->selectDistinct('si.id AS aluno_id, si.name AS nome')
            ->from('student_enrollment se')
            ->join('classroom c', 'c.id = se.classroom_fk')
            ->join('student_identification si', 'si.id = se.student_fk')
            ->where(
                'c.school_inep_fk = :school AND c.school_year = :year AND ' . self::ACTIVE_ENROLLMENT_CONDITION,
                [':school' => $schoolInep, ':year' => $year]
            )
            ->order('si.name, si.id')
            ->queryAll();

        return array_map(
            static fn (array $row): array => [
                'aluno_id' => (int) $row['aluno_id'],
                'nome' => $row['nome'],
            ],
            $rows
        );
    }
}
