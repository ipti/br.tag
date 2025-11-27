<?php

Yii::import('application.modules.enrollmentonline.repository.*');
class EnrollmentOnlineManagerController extends Controller
{

    public $layout = '//layouts/column2';


    public function accessRules()
    {
        return [
            [
                'allow',  // allow all users to perform 'index', 'view', and 'create' actions
                'actions' => ['index', 'view', 'create', 'getCities', 'getSchools'],
                'users' => ['*'],
            ],
            [
                'allow', // allow authenticated user to perform 'update' actions
                'actions' => ['update', 'StudentStatus'],
                'users' => ['@'],
            ],
            [
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['admin', 'delete'],
                'users' => ['admin'],
            ],
        ];
    }

    public function init()
    {
        if (!Yii::app()->user->isGuest) {
            $authTimeout = Yii::app()->user->getState('authTimeout', SESSION_MAX_LIFETIME);
            Yii::app()->user->authTimeout = $authTimeout;

            Yii::app()->sentry->setUserContext([
                'id' => Yii::app()->user->loginInfos->id,
                'username' => Yii::app()->user->loginInfos->username,
                'role' => Yii::app()->authManager->getRoles(Yii::app()->user->loginInfos->id)
            ]);
        }
    }

    public function actionIndex()
    {
        $countSql = "
            SELECT COUNT(t.id)
            FROM enrollment_online_student_identification AS t
            JOIN edcenso_stage_vs_modality esvm
                ON t.edcenso_stage_vs_modality_fk = esvm.id
        ";

        $sql = "
        SELECT
            t.id,
            t.name,
            t.cpf,
            t.responsable_name,
            t.responsable_cpf,
            t.edcenso_stage_vs_modality_fk AS etapa,
            p.status_matricula,
            p.prioridade_1,
            p.prioridade_2,
            p.prioridade_3
        FROM
            enrollment_online_student_identification AS t
        JOIN
            edcenso_stage_vs_modality esvm
            ON t.edcenso_stage_vs_modality_fk = esvm.id
        LEFT JOIN (
            SELECT
                p_inner.enrollment_online_student_identification_fk,
                MAX(
                    CASE
                        WHEN p_inner.prioridade_num = 1 THEN p_inner.status
                        ELSE NULL
                    END
                ) AS status_matricula,
                MAX(
                    CASE
                        WHEN p_inner.prioridade_num = 1 THEN p_inner.school_name
                        ELSE NULL
                    END
                ) AS prioridade_1,
                MAX(
                    CASE
                        WHEN p_inner.prioridade_num = 2 THEN p_inner.school_name
                        ELSE NULL
                    END
                ) AS prioridade_2,
                MAX(
                    CASE
                        WHEN p_inner.prioridade_num = 3 THEN p_inner.school_name
                        ELSE NULL
                    END
                ) AS prioridade_3
            FROM
            (
                SELECT
                    sol.enrollment_online_student_identification_fk,
                    si.name AS school_name,
                    sol.status,
                    @row_num := IF(@current_student = sol.enrollment_online_student_identification_fk, @row_num + 1, 1) AS prioridade_num,
                    @current_student := sol.enrollment_online_student_identification_fk

                /*
                 * INÍCIO DA CORREÇÃO:
                 * Trocamos a vírgula (,) por um JOIN explícito (CROSS JOIN).
                 */
                FROM
                    enrollment_online_enrollment_solicitation AS sol
                JOIN
                    school_identification AS si
                    ON sol.school_inep_id_fk = si.inep_id /* <--- ATENÇÃO AQUI */
                CROSS JOIN
                    (SELECT @row_num := 0, @current_student := '') AS vars
                /* FIM DA CORREÇÃO */

                ORDER BY
                    sol.enrollment_online_student_identification_fk, sol.id ASC
            ) AS p_inner
            GROUP BY
                p_inner.enrollment_online_student_identification_fk
        ) AS p ON t.id = p.enrollment_online_student_identification_fk
    ";

        $dataProvider = new CSqlDataProvider($sql, [
            'totalItemCount' => Yii::app()->db->createCommand($countSql)->queryScalar(),
            'keyField' => 'id',
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $columns = [
            ['name' => 'id', 'header' => 'ID'],
            ['name' => 'name', 'header' => "Nome"],
            ['name' => 'cpf', 'header' => 'Cpf'],
            ['name' => 'responsable_name', 'header' => 'Responsável'],
            ['name' => 'responsable_cpf', 'header' => 'Cpf do responsável'],
            ['name' => 'etapa', 'header' => 'Etapa escolar'],
            ['name' => 'status_matricula', 'header' => 'Status da matrícula'],
            ['name' => 'prioridade_1', 'header' => 'Prioridade 1'],
            ['name' => 'prioridade_2', 'header' => 'Prioridade 2'],
            ['name' => 'prioridade_3', 'header' => 'Prioridade 3'],
        ];

        $this->render('index', [
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]);
    }

    public function actionUpdateEnrollmentStatus($studentId, $status)
    {
        $query = '
            UPDATE enrollment_online_enrollment_solicitation eoes
            JOIN enrollment_online_student_identification eosi
                ON eosi.id = eoes.enrollment_online_student_identification_fk
            SET eoes.status = :value
            WHERE eosi.id = :id';
        Yii::app()->db->createCommand($query)->execute([
            ':value' => $status,
            ':id' => $studentId
        ]);

    }
}
