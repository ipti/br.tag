<?php

class calendarWidget extends CWidget
{
    public $calendar;

    public function run()
    {
        $this->render('calendar', [
            'calendar' => $this->calendar,
        ]);
    }
}
