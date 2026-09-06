<?php

namespace moDevsome\Palmo\Generators;

use DateTime;
use InvalidArgumentException;
use moDevsome\Palmo\Enums\DateTimeMoment;

class PalmoDate
{
    /**
     * Generate a random date
     * @param DateTimeMoment|null $moment - Optionnal moment
     * @param int $minimumYearDelta - Minimum diff in year between the current date and the generated date - COULD NOT BE LOWER THAN 1
     * @param int $maximumYearDelta - Maximum diff in year between the current date and the generated date - COULD NOT BE LOWER THAN 1 or LOWER THAN $minimumYearDelta
     * @throws InvalidArgumentException
     * @return DateTime A random date time object
     */
    public function gen(DateTimeMoment|null $moment = null, int $minimumYearDelta = 1, int $maximumYearDelta = 30): DateTime
    {

        if ($minimumYearDelta < 1 or $minimumYearDelta > $maximumYearDelta)
            throw new InvalidArgumentException('Minimum diff in year between the current date and the generated date COULD NOT BE LOWER THAN 1 or HIGHER THAN the Maximum diff');

        $usedMoment = $moment ?? [DateTimeMoment::FUTURE, DateTimeMoment::PAST][rand(0, 1)];
        $now = new DateTime();

        if ($usedMoment === DateTimeMoment::NOW)
            return $now;

        // Generate the year
        $delta = rand($minimumYearDelta, $maximumYearDelta);
        $yearTimestampDateTime = $usedMoment === DateTimeMoment::FUTURE ? '+ ' . $delta . ' year' : '- ' . $delta . ' year';
        $generatedYear = date('Y', strtotime($yearTimestampDateTime));

        // Generate the month
        $month = rand(1, 12);

        // Generate the day
        $day = match ($month) {
            2 => rand(0, 28),
            4, 6, 9, 11 => rand(0, 30),
            default => rand(0, 31)
        };

        $dateString = implode('-', [$generatedYear, $month <= 9 ? $month : '0' . $month, $day <= 9 ? '0' . $day : $day]);
        return new DateTime($dateString);
    }
}
