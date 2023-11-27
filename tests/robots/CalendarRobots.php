<?php

class CalendarRobots
{
    public AcceptanceTester $teste;

    public function __construct(AcceptanceTester $teste)
    {
        $this->teste = $teste;
    }

    /**
     * Url da página de calendário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageCalendar()
    {
        $this->teste->amOnPage('?r=calendar');
    }

    /**
     * Pesquisar pelo title do calendário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function searchTitleCalendar($titleCalendar)
    {
        $this->teste->executeJS("
        function clickElementWithTitle(targetTitle) {
            var titleElements = document.querySelectorAll('a.accordion-title');

            titleElements.forEach(function(element) {
                var titleText = element.innerText.trim();

                if (titleText.includes(targetTitle)) {
                    element.click();
                }
            });
        }
        clickElementWithTitle('$titleCalendar');
        ");
    }

    /**
     * Botão de adicionar novo calendário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnNewCalendar()
    {
        $this->teste->click('.new-calendar-button');
    }

    /**
     * Botão salvar calendário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnSave()
    {
        $this->teste->waitForElementVisible('.create-calendar', 10);
        $this->teste->executeJS("document.querySelector('.create-calendar').click();");
        $this->teste->wait(1);
    }


    /**
     * Botão cancelar.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnCancel()
    {
        $this->teste->click('.btn-default');
    }

    /**
     * Botão salvar evento.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnSaveEvent()
    {
        $this->teste->waitForElementVisible('.save-event');
        $this->teste->executeJS("document.querySelector('.save-event').click();");
    }

    /**
     * Preenche o titulo do calendário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function title($title)
    {
        $this->teste->executeJS("
            document.querySelector('.create-calendar-title').value = '$title';
            var event = new Event('input', { bubbles: true });
            document.querySelector('.create-calendar-title').dispatchEvent(event);
        ");
    }

    /**
     * Seleciona a etapa do calendário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function stage($stage)
    {
        $this->teste->executeJS("$('#stages').select2('val', $stage);");
    }

    /**
     * Selecione o calendário base.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function copy($copy)
    {
        $this->teste->selectOption('#copy', $copy);
    }

    /**
     * Clicar no inicio do ano escolar.
     * Dia 4 de janeiro.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function clickStartYearSchooling()
    {
        $seletorCss = ".row-fluid:nth-child(1) .span3:nth-child(1) > .row-fluid:nth-child(3) .span1-7:nth-child(5) .calendar-text";
        $this->teste->executeJS("document.querySelector('$seletorCss').click();");
        $this->teste->wait(1);
    }

    /**
     * Clicar no fim do ano escolar.
     * Dia 1 de março.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function clickEndYearSchooling()
    {
        $seletorCss = "#collapse47 .row-fluid:nth-child(1) .span3:nth-child(3) > .row-fluid:nth-child(3) .span1-7:nth-child(6) .calendar-text:nth-child(1)";
        $this->teste->executeJS("document.querySelector('$seletorCss').click();");
        $this->teste->wait(1);
    }

    public function event($event)
    {
        $this->teste->fillField('#CalendarEvent_name', $event);
    }

    public function dateStartEvent($dateStart)
    {
        $this->teste->fillField('#CalendarEvent_start_date', $dateStart);
    }

    public function dateEndEvent($dateEnd)
    {
        $this->teste->fillField('#CalendarEvent_end_date', $dateEnd);
    }

    public function typeEvent($type)
    {
        $this->teste->selectOption('#CalendarEvent_calendar_event_type_fk', $type);
    }

    public function confirmSave()
    {
        $this->teste->waitForElementVisible('.confirm-save-event', 10);
        $this->teste->executeJS("document.querySelector('.confirm-save-event').click();");
        $this->teste->wait(1);
    }
}
