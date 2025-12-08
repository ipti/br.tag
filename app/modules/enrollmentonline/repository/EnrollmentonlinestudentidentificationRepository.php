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
        $this->studentIdentification->cep = preg_replace($cleanPattern, '', $this->studentIdentification->cep);

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
        $enrollmentSolicitation->status = 1;
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

        $classroom = $this->findAvailableClassroom();

        if (!$classroom) {
            return $this->jsonError("Não há vagas disponíveis para esta etapa/modalidade.");
        }

        $existingStudent = $this->findExistingStudent();

        if ($existingStudent === null) {
            return $this->createNewStudentAndEnrollment($classroom);
        }

        return $this->createEnrollmentForExistingStudent($existingStudent, $classroom);
    }

    private function findAvailableClassroom()
    {
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

        return Classroom::model()->find($criteria);
    }

    private function findExistingStudent()
    {
        $si = $this->studentIdentification;

        // 1. Tentar buscar por CPF
        if (!empty($si->cpf)) {
            $student = StudentDocumentsAndAddress::model()->findByAttributes([
                'cpf' => $si->cpf
            ]);
            if ($student) return StudentIdentification::model()->findByPk($student->student_fk);
        }

        // 2. Buscar pelos demais dados
        return StudentIdentification::model()->findByAttributes([
            'responsable_cpf'  => $si->responsable_cpf,
            'name'             => $si->name,
            'responsable_name' => $si->responsable_name
        ]);
    }

    private function createNewStudentAndEnrollment($classroom)
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            // Criar models
            $studentIdentification = new StudentIdentification();
            $studentDocuments = new StudentDocumentsAndAddress();
            $studentEnrollment = new StudentEnrollment();

            // Preencher atributos
            $si = $this->studentIdentification;
            $studentIdentification->attributes = $si->attributes;
            $studentDocuments->attributes = $si->attributes;

            $studentIdentification->deficiency = 0;
            $studentIdentification->school_inep_id_fk = Yii::app()->user->school;
            $studentIdentification->send_year = date('Y');
            $studentIdentification->edcenso_uf_fk = null;
            $studentIdentification->edcenso_city_fk = null;

            $studentDocuments->school_inep_id_fk = Yii::app()->user->school;

            // Validar
            if (!$studentIdentification->validate() || !$studentDocuments->validate()) {
                throw new Exception("Dados inválidos. Verifique o formulário.");
            }

            // Salvar identificação
            if (!$studentIdentification->save()) {
                throw new Exception("Erro ao salvar identificação.");
            }

            // Salvar documentos
            $studentDocuments->student_fk = $studentIdentification->id;
            if (!$studentDocuments->save()) {
                throw new Exception("Erro ao salvar documentos/endereço.");
            }

            // Salvar EOSI com referência do estudante
            $si->student_fk = $studentIdentification->id;
            if (!$si->save()) {
                throw new Exception("Erro ao atualizar identificação com FK do estudante.");
            }

            // Criar matrícula
            $this->saveEnrollment($studentEnrollment, $studentIdentification->id, $classroom);

            // Atualizar solicitação
            $this->updateSolicitationStatus($si->id);

            $transaction->commit();

            return $this->jsonSuccess("O Cadastro de {$studentIdentification->name} foi criado com sucesso!");
        } catch (Exception $e) {
            $transaction->rollback();
            return $this->jsonError($e->getMessage());
        }
    }


    private function createEnrollmentForExistingStudent($existingStudent, $classroom)
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $studentEnrollment = new StudentEnrollment();

            // Criar matrícula
            $this->saveEnrollment($studentEnrollment, $existingStudent->id, $classroom);

            // Atualizar solicitação
            $this->updateSolicitationStatus($this->studentIdentification->id);

            $transaction->commit();

            return $this->jsonSuccess(
                "O Cadastro de {$existingStudent->name} foi criado com sucesso!"
            );
        } catch (Exception $e) {
            $transaction->rollback();
            return $this->jsonError($e->getMessage());
        }
    }

    private function saveEnrollment($enrollment, $studentId, $classroom)
    {
        $enrollment->student_fk = $studentId;
        $enrollment->school_inep_id_fk = Yii::app()->user->school;
        $enrollment->classroom_fk = $classroom->id;
        $enrollment->status = 1;
        $enrollment->create_date = date('Y-m-d');
        $enrollment->enrollment_date = date('Y-m-d');
        $enrollment->daily_order = $enrollment->getDailyOrder();

        if (!$enrollment->save()) {
            throw new Exception("Erro ao salvar a matrícula.");
        }
    }

    private function updateSolicitationStatus($id)
    {
        $solicitation = EnrollmentOnlineEnrollmentSolicitation::model()->findByAttributes([
            'enrollment_online_student_identification_fk' => $id,
            'school_inep_id_fk' => Yii::app()->user->school
        ]);

        $solicitation->status = EnrollmentOnlineEnrollmentSolicitation::ACCEPTED;

        if (!$solicitation->save()) {
            throw new Exception("Erro ao atualizar solicitação de matrícula.");
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
