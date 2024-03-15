<?php

namespace App\Challenge\Defis\JourMagique;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/JourMagique/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): int
    {
        $dateFormatter = \IntlDateFormatter::create(
            'fr_FR',
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            'Europe/Paris',
            \IntlDateFormatter::GREGORIAN,
            'EEEE'
        );

        $date = \DateTime::createFromFormat('Y-m-d', '2000-01-01');
        $targetDate = '2024-03-15';
        $magique = 0;

        while ($date->format('Y-m-d') !== $targetDate) {
            $day = strlen($dateFormatter->format($date));
            $number = (int) $date->format('d');

            if (0 === ($day + $number) % 10) {
                ++$magique;
            }

            $date->modify('+1 day');
        }

        return $magique;
    }
}
