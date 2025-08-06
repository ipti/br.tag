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

            Yii::log('Erro ao salvar pré-matrícula: '.$e->getMessage(), CLogger::LEVEL_ERROR);
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
        $user = new Users();
        $passwordHasher = new PasswordHasher();
        $password = $passwordHasher->bcriptHash(str_replace('/', '', $this->studentIdentification->birthday));
        $user->password = $password;
        $user->name = $this->studentIdentification->responsable_name;
        $user->active = 1;
        $cpf = preg_replace('/\D/', '', $this->studentIdentification->responsable_cpf);
        $user->username = $this->studentIdentification->id.$cpf;
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
}
