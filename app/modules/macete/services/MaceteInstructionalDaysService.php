<?php

/**
 * Calcula os dias letivos de uma turma a partir do calendário escolar
 * vinculado a ela (calendar.start_date/end_date + classroom.week_days_* +
 * calendar_event de feriado/recesso/ponto facultativo), sem depender da
 * tabela schedule (quadro de horário por disciplina).
 *
 * Mesma regra de negócio usada em TimesheetController::actionGenerateTimesheet
 * (tipos de evento 101 = feriado, 102 = recesso/férias, 104 = ponto
 * facultativo bloqueiam o dia), mas sem gravar nada em schedule.
 */
class MaceteInstructionalDaysService
{
    private const BLOCKED_EVENT_TYPES = [101, 102, 104];

    private const MONTH_NAMES = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
    ];

    public function getAvailableMonths(Classroom $classroom): array
    {
        $calendar = $this->calendarOf($classroom);
        if ($calendar === null) {
            return [];
        }

        $begin = new DateTime($calendar->start_date);
        $begin->modify('first day of this month');
        $end = new DateTime($calendar->end_date);
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);

        $months = [];
        foreach ($period as $date) {
            $months[] = [
                'month' => (int) $date->format('n'),
                'year' => (int) $date->format('Y'),
                'label' => self::MONTH_NAMES[(int) $date->format('n')] . '/' . $date->format('Y'),
            ];
        }

        return $months;
    }

    /**
     * @return int[] dias do mês (1-31) que são letivos para a turma
     */
    public function getInstructionalDays(Classroom $classroom, int $month, int $year): array
    {
        $calendar = $this->calendarOf($classroom);
        if ($calendar === null) {
            return [];
        }

        $weekDays = $this->getWeekDays($classroom);
        if (empty($weekDays)) {
            return [];
        }

        $calendarStart = new DateTime($calendar->start_date);
        $calendarEnd = new DateTime($calendar->end_date);
        $monthStart = new DateTime(sprintf('%04d-%02d-01', $year, $month));
        $monthEnd = (clone $monthStart)->modify('last day of this month');

        $rangeStart = $calendarStart > $monthStart ? $calendarStart : $monthStart;
        $rangeEnd = $calendarEnd < $monthEnd ? $calendarEnd : $monthEnd;
        if ($rangeStart > $rangeEnd) {
            return [];
        }

        $blockedDates = $this->getBlockedDates((int) $calendar->id);

        $days = [];
        $cursor = clone $rangeStart;
        $rangeEndExclusive = (clone $rangeEnd)->modify('+1 day');
        while ($cursor < $rangeEndExclusive) {
            $weekDay = (int) $cursor->format('w');
            if (in_array($weekDay, $weekDays, true) && !isset($blockedDates[$cursor->format('Y-m-d')])) {
                $days[] = (int) $cursor->format('j');
            }
            $cursor->modify('+1 day');
        }

        return $days;
    }

    private function calendarOf(Classroom $classroom): ?Calendar
    {
        if ($classroom->calendar_fk === null) {
            return null;
        }

        return $classroom->calendarFk;
    }

    private function getWeekDays(Classroom $classroom): array
    {
        $map = [
            0 => $classroom->week_days_sunday,
            1 => $classroom->week_days_monday,
            2 => $classroom->week_days_tuesday,
            3 => $classroom->week_days_wednesday,
            4 => $classroom->week_days_thursday,
            5 => $classroom->week_days_friday,
            6 => $classroom->week_days_saturday,
        ];

        $weekDays = [];
        foreach ($map as $weekDay => $enabled) {
            if ($enabled) {
                $weekDays[] = $weekDay;
            }
        }

        return $weekDays;
    }

    /**
     * @return array<string, bool> datas (Y-m-d) bloqueadas por feriado/recesso/ponto facultativo
     */
    private function getBlockedDates(int $calendarId): array
    {
        $events = Yii::app()->db->createCommand(
            'select start_date, end_date from calendar_event
             where calendar_fk = :calendar_fk and calendar_event_type_fk in (' . implode(',', self::BLOCKED_EVENT_TYPES) . ')'
        )
            ->bindParam(':calendar_fk', $calendarId)
            ->queryAll();

        $blocked = [];
        foreach ($events as $event) {
            $start = new DateTime($event['start_date']);
            $end = (new DateTime($event['end_date']))->modify('+1 day');
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start, $interval, $end);
            foreach ($period as $date) {
                $blocked[$date->format('Y-m-d')] = true;
            }
        }

        return $blocked;
    }
}
