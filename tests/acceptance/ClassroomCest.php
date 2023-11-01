<?php

require_once 'vendor/autoload.php';
require_once __DIR__ . "/../robots/LoginRobots.php";
require_once __DIR__ . "/../robots/ClassroomRobots.php";
require_once __DIR__ . "/../builders/ClassroomBuilder.php";

class ClassroomCest
{
    public function _before(AcceptanceTester $tester)
    {
        $user = "admin";
        $secret = "p@s4ipti";

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($secret);
        $robots->submit();
        sleep(2);
    }

    // tests
    public function addClassroom (AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new ClassroomRobots($teste);
        $robots->pageAddClassroom();

        $builder = new ClassroomBuilder();
        $dataStudent = $builder->buildCompleted();

        $robots->name($dataStudent['name']);
        $robots->stageVsModalaty($dataStudent['edcenso_stage_vs_modality_fk']);
        $robots->typeMediation($dataStudent['pedagogical_mediation_type']);
        $robots->modality($dataStudent['modality']);
        $robots->educationCourse($dataStudent['edcenso_professional_education_course_fk']);
        $robots->initialTime($dataStudent['initial_time']);
        $robots->finalTime($dataStudent['final_time']);
        $robots->turn($dataStudent['turn']);
        sleep(8);


    }

    /**
     * Adicionar turmas, não preenchendo nenhum campo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function fieldsNotFilledIn (AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new ClassroomRobots($teste);
        $robots->pageAddClassroom();
        $robots->btnCriar();

        $teste->see('Campo Nome é obrigatório.
        Campo Modalidade é obrigatório.
        Campo Etapa de Ensino é obrigatório.
        Campo Horário Inicial é obrigatório.
        Campo Horário Final é obrigatório.
        Campo Dias da semana é obrigatório. Selecione ao menos um dia.
        Campo Tipo de Mediação Didático-Pedagógica é obrigatório.
        Campo Tipos de Atendimento é obrigatório. Selecione ao menos uma opção.');
    }
}
