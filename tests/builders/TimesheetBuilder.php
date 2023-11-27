<?php

/**
 * @property [] $timesheet
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class TimesheetBuilder
{
    private $faker = null;
    private $fakerCustom = null;

    /**
     * Summary of calendar
     * @var $calendar
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->timesheet = [];
    }

    public function buildCompleted()
    {
        $this->timesheet['classroom_fk'] = '571'; // DATA

        return $this->timesheet;
    }
}
