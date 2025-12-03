<?php

class EnrollmentonlinestudentidentificationRepository
{
    private $studentIdentification;
    private $user;

    public function __construct($model)
    {
        $this->studentIdentification = $model;
    }

    public function savePreEnrollment()
    {
        // Regex para remover pontos, traços, parênteses e espaços
        $cleanPattern = '/[\.\-\(\)\s]/';

        // Limpeza dos campos
        $this->studentIdentification->cpf = preg_replace($cleanPattern, '', $this->studentIdentification->cpf);
        $this->studentIdentification->responsable_cpf = preg_replace($cleanPattern, '', $this->studentIdentification->responsable_cpf);
        $this->studentIdentification->responsable_telephone = preg_replace($cleanPattern, '', $this->studentIdentification->responsable_telephone);

        $transaction = Yii::app()->db->beginTransaction();

        try {
            if (!$this->studentIdentification->save()) {
                throw new CException('Falha ao salvar identificação do estudante.');
            }

            if (
                !$this->saveSolicitations($_POST['school_1']) ||
                !$this->saveSolicitations($_POST['school_2']) ||
                !$this->saveSolicitations($_POST['school_3'])
            ) {
                throw new CException('Falha ao salvar solicitações de matrícula.');
            }

            if (
                !$this->createUser()
            ) {
                throw new CException('Falha ao criar usuário');
            }

            $transaction->commit();

            return $this->user;
        } catch (Exception $e) {
            $transaction->rollback();

            Yii::log('Erro ao salvar pré-matrícula: ' . $e->getMessage(), CLogger::LEVEL_ERROR);
            throw $e;
        }
    }

    private function saveSolicitations($inepId)
    {
        $enrollmentSolicitation = new EnrollmentOnlineEnrollmentSolicitation();
        $enrollmentSolicitation->status = 0;
        $enrollmentSolicitation->school_inep_id_fk = $inepId;
        $enrollmentSolicitation->enrollment_online_student_identification_fk = $this->studentIdentification->id;
        return $enrollmentSolicitation->save();
    }

    private function createUser()
    {
        $user = Users::model()->findByAttributes(['username' => preg_replace('/\D/', '', $this->studentIdentification->responsable_cpf)]);
        if ($user) {
            $this->studentIdentification->user_fk = $user->id;
            $this->user = $user;
            return $this->studentIdentification->save();
        }
        $user = new Users();
        $passwordHasher = new PasswordHasher();
        $password = $passwordHasher->bcriptHash(str_replace('/', '', $this->studentIdentification->responsable_cpf));
        $user->password = $password;
        $user->name = $this->studentIdentification->responsable_name;
        $user->active = 1;
        $cpf = preg_replace('/\D/', '', $this->studentIdentification->responsable_cpf);
        $user->username = $cpf;
        if ($user->save()) {
            $auth = new AuthAssignment();
            $auth->itemname = 'guardian';
            $auth->userid = $user->id;
            $auth->save();

            $this->studentIdentification->user_fk = $user->id;
            $this->user = $user;
            return $this->studentIdentification->save();
        }
    }

    public function getStudentList()
    {

        $criteria = new CDbCriteria();
        $criteria->condition = 'user_fk = :user_fk';
        $criteria->params = [':user_fk' => Yii::app()->user->loginInfos->id];

        $students = EnrollmentOnlineStudentIdentification::model()->findAll($criteria);
        $reuslt = [];
        foreach ($students as $student) {
            $wasAcept = EnrollmentOnlineEnrollmentSolicitation::model()->findByAttributes([
                'enrollment_online_student_identification_fk' => $student->id,
                'status' => EnrollmentOnlineEnrollmentSolicitation::ACCEPTED
            ]);

            $hasNonRejected = EnrollmentOnlineEnrollmentSolicitation::model()->exists(
                'enrollment_online_student_identification_fk = :id AND status != :status',
                [
                    ':id' => $student->id,
                    ':status' => EnrollmentOnlineEnrollmentSolicitation::REJECTED
                ]
            );
            $allRejected = !$hasNonRejected;

            if ($wasAcept) {
                $reuslt[] = [$student, 'status' => 'Aprovado', 'school' => $wasAcept->schoolInepIdFk->name];
            } else if ($allRejected) {
                $reuslt[] = [$student, 'status' => 'Rejeitado'];
            } else {
                $reuslt[] = [$student, 'status' => 'Em processamento'];
            }
        }

        return $reuslt;
    }

    public function getStatus($studentId)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'enrollment_online_student_identification_fk = :studentId';
        $criteria->params = [':studentId' => $studentId];

        return EnrollmentOnlineEnrollmentSolicitation::model()->findAll($criteria);
    }

    public function confirmEnrollment()
    {
        // ---------- 1. Buscar turma com vaga ----------
        $criteria = new CDbCriteria();
        $criteria->alias = 'c';

        $criteria->join = '
        INNER JOIN school_identification si
            ON c.school_inep_fk = si.inep_id
        INNER JOIN enrollment_online_student_identification eosi
            ON c.edcenso_stage_vs_modality_fk = eosi.edcenso_stage_vs_modality_fk
        LEFT JOIN student_enrollment se
            ON se.classroom_fk = c.id
    ';

        $criteria->condition = '
        si.inep_id = :school
        AND c.edcenso_stage_vs_modality_fk = :stage
    ';

        $criteria->params = [
            ':school' => Yii::app()->user->school,
            ':stage'  => $this->studentIdentification->edcenso_stage_vs_modality_fk
        ];

        $criteria->addCondition('(se.status IN (1,2,6,7,8,9,10) OR se.status IS NULL)');
        $criteria->group = 'c.id';
        $criteria->having = 'COUNT(se.id) < c.capacity';

        $classroom = Classroom::model()->find($criteria);

        if (!$classroom) {
            return $this->jsonError("Não há vagas disponíveis para esta etapa/modalidade.");
        }

        // ---------- 2. Criar models ----------
        $studentIdentification = new StudentIdentification();
        $studentDocuments = new StudentDocumentsAndAddress();
        $studentEnrollment = new StudentEnrollment();

        $studentIdentification->attributes = $this->studentIdentification->attributes;
        $studentDocuments->attributes = $this->studentIdentification->attributes;
        $studentIdentification->deficiency = 0;

        $studentIdentification->school_inep_id_fk = Yii::app()->user->school;
        $studentIdentification->send_year = date('Y');
        $studentIdentification->edcenso_uf_fk = null;
        $studentIdentification->edcenso_city_fk = null;
        $studentIdentification->send_year = date('Y');

        $studentDocuments->school_inep_id_fk = Yii::app()->user->school;

        $transaction = Yii::app()->db->beginTransaction();

        try {

            // ---------- 3. Validar ----------
            if (!$studentIdentification->validate() || !$studentDocuments->validate()) {
                throw new Exception("Dados inválidos. Verifique o formulário.");
            }

            // ---------- 4. Salvar StudentIdentification ----------
            if (!$studentIdentification->save()) {
                throw new Exception("Erro ao salvar identificação.");
            }

            // ---------- 5. Salvar documentos ----------
            $studentDocuments->student_fk = $studentIdentification->id;
            if (!$studentDocuments->save()) {
                throw new Exception("Erro ao salvar documentos/endereço.");
            }

            // ---------- 6. Salvar identificação ----------
            $this->studentIdentification->student_fk = $studentIdentification->id;
            if (!$this->studentIdentification->save()) {
                throw new Exception("Erro ao atualizar identificação com FK do estudante.");
            }

            // ---------- 7. Salvar matrícula ----------
            $studentEnrollment->student_fk = $studentIdentification->id;
            $studentEnrollment->school_inep_id_fk = Yii::app()->user->school;
            $studentEnrollment->classroom_fk = $classroom->id;
            $studentEnrollment->status = 1;
            $studentEnrollment->create_date = date('Y-m-d');
            $studentEnrollment->enrollment_date = date('Y-m-d');
            $studentEnrollment->daily_order = $studentEnrollment->getDailyOrder();

            if (!$studentEnrollment->save()) {
                throw new Exception("Erro ao salvar a matrícula.");
            }

            $solicitation = EnrollmentOnlineEnrollmentSolicitation::model()->findByAttributes([
                'enrollment_online_student_identification_fk' => $this->studentIdentification->id,
                'school_inep_id_fk' => Yii::app()->user->school
            ]);
            $solicitation->status = EnrollmentOnlineEnrollmentSolicitation::ACCEPTED; // Aprovado

            // ---------- 7. Salvar matrícula ----------
            if (!$solicitation->save()) {
                throw new Exception("Erro ao atualizar solicitação de matrícula.");
            }


            // Commit final
            $transaction->commit();

            return $this->jsonSuccess(
                "O Cadastro de {$studentIdentification->name} foi criado com sucesso!"
            );
        } catch (Exception $e) {
            $transaction->rollback();
            return $this->jsonError($e->getMessage());
        }
    }

    private function jsonSuccess($msg)
    {
        header('Content-Type: application/json');
        echo json_encode(["status" => "success", "message" => $msg]);
        Yii::app()->end();
    }

    private function jsonError($msg)
    {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => $msg]);
        Yii::app()->end();
    }

    public function rejectedEnrollment()
    {
        $solicitation = EnrollmentOnlineEnrollmentSolicitation::model()->findByAttributes([
            'enrollment_online_student_identification_fk' => $this->studentIdentification->id,
            'school_inep_id_fk' => Yii::app()->user->school
        ]);
        $solicitation->status = EnrollmentOnlineEnrollmentSolicitation::REJECTED; // Rejeitado

        // ---------- 7. Salvar matrícula ----------
        if (!$solicitation->save()) {
            return $this->jsonError("Erro ao atualizar solicitação de matrícula.");
        }

        return $this->jsonSuccess(
            "A matrícula de {$this->studentIdentification->name} foi rejeitada com sucesso!"
        );
    }
}
