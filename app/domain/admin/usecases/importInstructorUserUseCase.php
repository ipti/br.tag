<?php

/**
 * Caso de uso para importação de professor do edcenso e criação de usuário.
 */
class ImportInstructorUserUseCase
{
    private $modelInstructorIdentification = new InstructorIdentification();
    private $modelInstructorDocumentsAndAddress = new InstructorDocumentsAndAddress();

    public function __construct($instructorIdentification, $instructorDocumentsAndAddress)
    {
        $this->modelInstructorDocumentsAndAddress = $instructorDocumentsAndAddress;
        $this->modelInstructorIdentification = $instructorIdentification;
    }

    public function exec()
    {
        $user = $this->createUser($this->modelInstructorIdentification, $this->modelInstructorDocumentsAndAddress);

        if ($user->save()) {
            $this->modelInstructorIdentification->users_fk = $user->id;
            $this->createUserSchool($user, $this->modelInstructorIdentification);
        }
    }

    public function getUpdatedInstructorIdentificationModel()
    {
        return $this->modelInstructorIdentification;
    }

    private function createUser($modelInstructorIdentification, $modelInstructorDocumentsAndAddress)
    {
        $user = new Users();
        $user->name = $modelInstructorIdentification->name;
        $user->username = $modelInstructorDocumentsAndAddress->cpf;
        $user->password = $this->hashBirthdayDate($modelInstructorIdentification->birthday_date);

        return $user;
    }

    private function createUserSchool($user)
    {
        $userSchool = new UsersSchool();
        $userSchool->user_fk = $user->id;
        $userSchool->school_fk = Yii::app()->user->school;

        if ($userSchool->save()) {
            $auth = Yii::app()->authManager;
            $auth->assign('instructor', $user->id);
        }
    }
}
