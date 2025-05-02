<?php

class EnrollmentonlinestudentidentificationRepository
{

    private $studentIdentification;

    public function __construct($model)
    {
        $this->studentIdentification = $model;
    }

    public function sanvePreEnrollment()
    {
        // Limpeza dos campos
        $this->studentIdentification->cpf = preg_replace('/[\.\-\(\)\s]/', '', $this->studentIdentification->cpf);
        $this->studentIdentification->responsable_cpf = preg_replace('/[\.\-\(\)\s]/', '', $this->studentIdentification->responsable_cpf);
        $this->studentIdentification->responsable_telephone = preg_replace('/[\.\-\(\)\s]/', '', $this->studentIdentification->responsable_telephone);

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


            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            // Aqui você pode registrar o erro ou lançar novamente
            Yii::log("Erro ao salvar pré-matrícula: " . $e->getMessage(), CLogger::LEVEL_ERROR);
            throw $e; // ou lidando de outra forma
        }
    }
    private function saveSolicitations($inepId)
    {
        $enrollmentSolicitation = new EnrollmentOnlineEnrollmentSolicitation();
        $enrollmentSolicitation->status = 0;
        $enrollmentSolicitation->school_inep_id_fk = $inepId;
        $enrollmentSolicitation->enrollment_online_student_identification_fk = $this->studentIdentification->id;
        $enrollmentSolicitation->save();
    }
}
