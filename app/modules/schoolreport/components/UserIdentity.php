<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private function schoolReportPassword($name)
    {
        if (!empty($name)) {
            $names = explode(' ', trim($name));
            $pass = '';
            foreach ($names as $n) {
                $pass .= $n[0];
            }
            //var_dump($pass);exit;
            return strtoupper($pass);
        }
        return '';
    }

    /**
     * Authenticates a user.
     *
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $students = StudentIdentification::model()->findAllByAttributes(['responsable_cpf' => $this->username]);
        if ($students[0]->responsable == 0) {
            $responsible = $students[0]->filiation_2;
        } elseif ($students[0]->responsable == 1) {
            $responsible = $students[0]->filiation_1;
        } else {
            $responsible = $students[0]->responsable_name;
        }

        if (count($students) === 0) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (strtoupper($this->password) !== $this->schoolReportPassword($responsible)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->setState('info', ['cpf' => $students[0]->responsable_cpf, 'name' => $responsible]);
            $result = [];
            foreach ($students as $student) {
                /* @var $student StudentIdentification
                 * @var $enrollment StudentEnrollment
                 * @var $classroom Classroom */
                $enrollments = StudentEnrollment::model()->findAllByAttributes(['student_fk' => $student->id]);
                foreach ($enrollments as $enrollment) {
                    $classroom = $enrollment->classroomFk;
                    if (!isset($result[$classroom->school_year])) {
                        $result[$classroom->school_year] = [];
                    }
                    $result[$classroom->school_year][$enrollment->id] = [
                        'name' => $student->name,
                        'classroom_id' => $classroom->id,
                        'classroom_name' => $classroom->name
                    ];
                }
            }
            $this->setState('enrollments', $result);
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
}
