<?php

/**
 * @property GradeResults $gradeResult
 * @property GradeRules $gradeRule
 * @property integer $numUnities
 * @property integer $frequency
 * @property integer $stage
 */
class ChageStudentStatusByGradeUsecase
{

    private $gradeResult;
    private $gradeRule;
    private $numUnities;
    private $frequency;
    private $stage;

    private const SITUATION_APPROVED = "APROVADO";
    private const SITUATION_DISPPROVED = "REPROVADO";
    private const SITUATION_RECOVERY = "RECUPERAÇÃO";


    public function __construct($gradeResult, $gradeRule, $numUnities, $stage, $frequency = null)
    {
        $this->gradeResult = $gradeResult;
        $this->gradeRule = $gradeRule;
        $this->numUnities = $numUnities;
        $this->frequency = $frequency;
        $this->$stage = $stage;
    }

    public function exec()
    {

            $enrollment = $this->getStudentEnrollment($this->gradeResult->enrollment_fk);

            TLog::info("Começando o processo de atualizar o status da matricula do aluno", ["enrollment" => $enrollment->id]);

            if (!$this->isEnrollmentStatusAllowed($enrollment)) {
                $this->gradeResult->situation = $enrollment->getCurrentStatus();
                $this->gradeResult->save();
                return;
            }

            if (!$this->hasAllGrades()) {
                $this->gradeResult->situation = StudentEnrollment::STATUS_ACTIVE;
                $this->gradeResult->save();
                return;
            }

            $this->updateStudentSituation();
    }

    private function getStudentEnrollment($enrollmentId)
    {
        return StudentEnrollment::model()->findByPK($enrollmentId);
    }

    /**
     * Verifica se a inscrição do aluno tem um status permitido.
     */
    private function isEnrollmentStatusAllowed($enrollment)
    {
        $allowedStatus = [
            null,
            StudentEnrollment::STATUS_ACTIVE,
            StudentEnrollment::STATUS_APPROVED,
            StudentEnrollment::STATUS_DISAPPROVED
        ];
        return in_array($enrollment->getCurrentStatus(), $allowedStatus);
    }

    /**
     * Verifica se todas as notas estão preenchidas.
     */
    private function hasAllGrades()
    {
        for ($i = 1; $i <= $this->numUnities; $i++) {
            if (!isset($this->gradeResult["grade_" . $i]) || $this->gradeResult["grade_" . $i] === "") {
                return false;
            }
        }
        return true;
    }

    /**
     * Atualiza a situação do aluno com base nas regras definidas.
     */
    private function updateStudentSituation()
    {
        if ($this->gradeResult->final_media === null || $this->gradeResult->final_media === "") {
            throw new Exception("Aluno não tem média final", 1);
        }

        $approvedSituation = self::SITUATION_APPROVED;
        $disapprovedSituation = self::SITUATION_DISPPROVED;
        $recoverySituation = self::SITUATION_RECOVERY;

        $finalMedia = $this->gradeResult->final_media;
        $approvationMedia = $this->gradeRule->approvation_media;
        $frequency = $this->frequency;

        $this->gradeResult->situation = $disapprovedSituation;

        if ($finalMedia >= $approvationMedia && ($frequency >= '75' || $frequency == null)) {
            $this->gradeResult->situation = $approvedSituation;
        } elseif ($this->gradeRule->has_final_recovery) {
            $recoveryMedia = $this->gradeResult->rec_final;
            $finalRecoveryMedia = $this->gradeRule->final_recover_media;

            $hasRecoveryGrade = isset($recoveryMedia) && $recoveryMedia !== "";
            if (!$hasRecoveryGrade) {
                $this->gradeResult->situation = $recoverySituation;
            } elseif ($recoveryMedia >= $finalRecoveryMedia) {
                $this->gradeResult->situation = $approvedSituation;
            }

        }

        if($this->gradeResult->save()){
            $updateEnrollment = new ChangeEnrollmentStatusUsecase($this->gradeResult->enrollment_fk);
            $updateEnrollment->exec();
        }
        TLog::info("Status da matrícula", ["gradeResult" => $this->gradeResult->situation]);
    }

}
