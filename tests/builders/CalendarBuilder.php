<?php
/**
 * @property [] $calendar
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class CalendarBuilder
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
        $this->calendar = [];
    }

    public function buildCompleted()
    {
        $this->calendar['title'] = $this->fakerCustom->titleCalendar();
        $this->calendar['event_start_name'] = 'Inicio';
        $this->calendar['event_end_name'] = 'Final';
        $this->calendar['event_start'] = '1000';
        $this->calendar['event_end'] = '1001';

        return $this->calendar;
    }

}
