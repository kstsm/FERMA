<?php

class DateHelper
{
    public function getFirstFridayInMonth($year, $month): int
    {
        $firstFriday = strtotime("first friday of $year-$month");
        return date('j', $firstFriday);
    }

    public function formatDate($day, $month, $year): string
    {
        $monthNames = [
            1 => 'янв.', 5 => 'мая ', 9 => 'сен.',
            2 => 'фев.', 6 => 'июн.', 10 => 'окт.',
            3 => 'мар.', 7 => 'июл.', 11 => 'ноя.',
            4 => 'апр.', 8 => 'авг.', 12 => 'дек.'
        ];

        return "$day-е $monthNames[$month] $year";
    }
}

class TableChairCounter
{

    public function __construct(private $dateHelper)
    {
    }

    public function getDates($year)
    {
        if ($year < 2000) {
            echo "Ошибка: Введенный год должен быть больше или равен 2000 году.\n";
            exit;
        }

        $dayTable = [];
        $countTable = 0;
        $countChair = 0;

        // Считает и выводит акционные дни столов с 2000 года до указанного.
        for ($currentYear = 2000; $currentYear <= $year; $currentYear++) {
            for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
                $firstFriday = $this->dateHelper->getFirstFridayInMonth($currentYear, $currentMonth);
                $formatDate = $this->dateHelper->formatDate($firstFriday, $currentMonth, $currentYear);
                if ($firstFriday % 2) {
                    $countTable++;
                    $dayTable[] = $formatDate;
                } else {
                    $countChair++;
                }
            }
        }

        // Если каких-то акционных дней больше, то он меняет их местами до тех пор, пока они не станут равными.
        while ($countTable !== $countChair) {
            if ($countTable < $countChair) {
                [$countTable, $countChair] = [$countChair, $countTable];
            }
            for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
                $firstFriday = $this->dateHelper->getFirstFridayInMonth($currentYear, $currentMonth);
                if ($firstFriday % 2) {
                    $countChair++;
                } else {
                    $countTable++;
                }
            }
            if ($countTable !== $countChair) {
                $currentYear++;
            }
        }

        return [
            'currentYear' => $currentYear,
            'countTable' => $countTable,
            'countChair' => $countChair,
            'dayTable' => $dayTable,
        ];
    }
}

$dateHelper = new DateHelper();
$counter = new TableChairCounter($dateHelper);

echo "Введите год: ";
$year = fgets(STDIN);
$result = $counter->getDates($year);

foreach ($result['dayTable'] as $date) {
    echo $date . "\n";
}

echo "\nАкционый дни столов и стульев сровняются в {$result['currentYear']} и будут равны: \nСтолы:{$result['countTable']} дня\nСтулья:{$result['countChair']} дня\n";



