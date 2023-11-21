<?php
require_once __DIR__ . '\\ClassroomCest.php';
class CalendarCest
{
    public function _before(AcceptanceTester $tester)
    {
        $builder = new LoginBuilder();
        $login = $builder->buildCompleted();

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($login['user']);
        $robots->fieldPassword($login['secret']);
        $robots->submit();
        sleep(2);
    }

    // tests

    /**
     * Adicionar calendário contemplando a etapa da turma selecionada.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addCalendarVinculadClass(AcceptanceTester $teste)
    {
        $classroom = new ClassroomCest();
        $addClassroom = $classroom->addClassroomEAD($teste);

        $builder = new CalendarBuilder();
        $dataBuilder = $builder->buildCompleted();

        $robots = new CalendarRobots($teste);
        $robots->pageCalendar();
        sleep(2);
        $robots->btnNewCalendar();
        sleep(2);
        $robots->title($dataBuilder['title']);
        $robots->stage('6');
        $robots->btnSave();
        sleep(4);
        $robots->searchTitleCalendar($dataBuilder['title']);
        sleep(2);
        $robots->clickStartYearSchooling();
        sleep(2);
        $robots->event($dataBuilder['event']);
        $robots->typeEvent($dataBuilder['event_start']);
        $robots->btnSaveEvent();
        sleep(4);
        $robots->confirmSave();

        sleep(10); //ver se funcionou
    }

}
