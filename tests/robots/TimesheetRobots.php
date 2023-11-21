<?php

class TimesheetRobots
{
    public AcceptanceTester $tester;
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    public function pageTimesheet()
    {
        $this->tester->amOnPage('?r=timesheet');
    }

    public function classroom($classroom)
    {
        $this->tester->selectOption('#classroom_fk', $classroom);
    }

    public function replicate()
    {
        $this->tester->click('.replicate-actions-checkbox replicate-actions');
    }
}
