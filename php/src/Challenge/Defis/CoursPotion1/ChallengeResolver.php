<?php

namespace App\Challenge\Defis\CoursPotion1;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/CoursPotion1/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): false|string
    {
        $fioles = [20, 20, 20, 0];

        $current = 0;
        for ($i = 0; $i < 12; ++$i) {
            for ($j = 1; $j <= 4; ++$j) {
                $tier = $fioles[$current] / 3;

                $next_fiole = ($current + $j) % 4;
                $fioles[$next_fiole] += $tier;
                $fioles[$current] -= $tier;

                if ($fioles[$next_fiole] > 25) {
                    $fioles[$current] += $fioles[$next_fiole] - 25;
                    $fioles[$next_fiole] = 25;
                }
            }
            $current = ($current + 1) % 4;
            $fioles = array_map(fn (float $number) => round($number, 1), $fioles);
        }

        return json_encode($fioles);
    }
}
