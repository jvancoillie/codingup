<?php

namespace App\Challenge\Defis\Herculito05Ecuries;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/Herculito05Ecuries/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): int
    {
        $rooms = $this->isTestMode() ? 10 : 103;
        $water = [2];
        $done = 0;
        $total = 0;
        while ($done < $rooms) {
            $total += array_sum($water);

            if (0 === $done % 3) {
                $water = [$water[0] * 7, $water[0] * 5];
                $done += 2;
                continue;
            }

            $water = [(int) substr((string) array_sum($water), -2)];
            ++$done;
        }

        return $total;
    }
}
