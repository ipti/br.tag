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
     * Preencher inicio e fim de ano letivo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addCalendarVinculadClass(AcceptanceTester $teste)
    {
        $builder = new CalendarBuilder();
        $dataBuilder = $builder->buildCompleted();

        $robots = new CalendarRobots($teste);
        $robots->pageCalendar();
        sleep(2);
        $robots->btnNewCalendar();
        sleep(2);
        $robots->title($dataBuilder['title']);
        $robots->stage($dataBuilder['stage_in_classroom']);
        $robots->btnSave();
        sleep(4);

        //Preencher inicio do ano letivo.
        $robots->searchTitleCalendar($dataBuilder['title']);
        sleep(2);
        $robots->clickStartYearSchooling();
        sleep(2);
        $robots->event($dataBuilder['event_start_name']);
        $robots->typeEvent($dataBuilder['event_start']);
        $robots->btnSaveEvent();
        sleep(2);
        $robots->confirmSave();
        sleep(4);

        //Preencher final do ano letivo.
        $robots->clickEndYearSchooling();
        sleep(2);
        $robots->event($dataBuilder['event_end_name']);
        $robots->typeEvent($dataBuilder['event_end']);
        $robots->btnSaveEvent();
        sleep(4);
        $robots->confirmSave();
        sleep(4);
    }
}
