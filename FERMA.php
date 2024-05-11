<?php

class DateHelper
{
    public function __construct()
    {
        setlocale(LC_TIME, 'ru_RU.utf8');
        $this->formatter = new IntlDateFormatter(
            'ru_RU',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            'Europe/Moscow',
            IntlDateFormatter::GREGORIAN,
            "d-е MMM Y"
        );
    }

    public function getFirstFridayInMonth($year, $month)
    {
        $firstFriday = strtotime("first friday of $year-$month");
        return date('j', $firstFriday);
    }

    public function format($day, $month, $year)
    {
        $dateTimeObj = new DateTime("$year-$month-$day");
        return $this->formatter->format($dateTimeObj);
    }
}

class TableChairCounter
{
    private DateHelper $dateHelper;

    public function __construct(DateHelper $dateHelper)
    {
        $this->dateHelper = $dateHelper;
    }

    public function getDates($year)
    {
        $dates = [];
        $tables = 0;
        $chairs = 0;

        for ($currentYear = 2000; $currentYear <= $year; $currentYear++) {
            for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
                $firstFridayDay = $this->dateHelper->getFirstFridayInMonth($currentYear, $currentMonth);
                $date = $this->dateHelper->format($firstFridayDay, $currentMonth, $currentYear);
                $dates[] = $date;
                if ($firstFridayDay % 2 == 0) {
                    $tables++;
                } else {
                    $chairs++;
                }
            }
        }

        while ($tables !== $chairs) {
            if ($tables < $chairs) {
                list($tables, $chairs) = [$chairs, $tables];
            }
            for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
                $firstFridayDay = $this->dateHelper->getFirstFridayInMonth($currentYear, $currentMonth);
                $date = $this->dateHelper->format($firstFridayDay, $currentMonth, $currentYear);
                $dates[] = $date;
                if ($firstFridayDay % 2 == 0) {
                    $tables++;
                } else {
                    $chairs++;
                    $this->dateHelper->format($firstFridayDay, $currentMonth, $currentYear);
                }
            }
            $currentYear++;
        }
        return $dates;
    }
}

$dateHelper = new DateHelper();
$counter = new TableChairCounter($dateHelper);
$dates = $counter->getDates(2000);
foreach ($dates as $date) {
    echo $date . "\n";
}