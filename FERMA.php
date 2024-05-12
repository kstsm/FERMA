<?php

class DateHelper
{
    public function __construct()
    {
        setlocale(LC_ALL, 'ru_RU.utf8');
    }

    public function getFirstFridayInMonth($year, $month): int
    {
        $firstFriday = strtotime("first friday of $year-$month");
        return date('j', $firstFriday);
    }

    public function format($day, $month, $year): string
    {
        return strftime("%e-е %b. %Y", mktime(0, 0, 0, $month, $day, $year));
    }
}

class TableChairCounter
{
    private DateHelper $dateHelper;

    public function __construct(DateHelper $dateHelper)
    {
        $this->dateHelper = $dateHelper;
    }

    public function getDates($year): array
    {
        $dates = [];
        $tables = 0;
        $chairs = 0;


        for ($currentYear = 2000; $currentYear <= $year; $currentYear++) {
            for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
                $firstFriday = $this->dateHelper->getFirstFridayInMonth($currentYear, $currentMonth);
                $date = $this->dateHelper->format($firstFriday, $currentMonth, $currentYear);
                if ($firstFriday % 2 !== 0) {
                    $tables++;
                    $dates[] = $date;
                } else {
                    $chairs++;
                }
            }
        }

        while ($tables !== $chairs) {
            if ($tables < $chairs) {
                [$tables, $chairs] = [$chairs, $tables];
            }
            for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
                $firstFriday = $this->dateHelper->getFirstFridayInMonth($currentYear, $currentMonth);
                $date = $this->dateHelper->format($firstFriday, $currentMonth, $currentYear);
                if ($firstFriday % 2 === 0) {
                    $tables++;
                    $dates[] = $date;
                } else {
                    $chairs++;
                }
            }
            $currentYear++;
        }

        return [
            'tables' => $tables,
            'chairs' => $chairs,
            'dates' => $dates
        ];
    }
}

$dateHelper = new DateHelper();
$counter = new TableChairCounter($dateHelper);
$result = $counter->getDates(2000);

foreach ($result['dates'] as $date) {
    echo $date . "\n";
}

echo "Столы: {$result['tables']}\nСтулья: {$result['chairs']}\n";