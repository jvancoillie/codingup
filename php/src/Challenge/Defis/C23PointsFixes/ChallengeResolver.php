<?php

namespace App\Challenge\Defis\C23PointsFixes;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_PointsFixes/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $rounds = [0 => 0, 1 => 0];
        $i = 0;
        foreach (range(2000, 9999) as $year) {
            $split = array_map('intval', str_split((string) $year));
            $s = array_sum(array_map(fn (int $n) => ($n > 4) ? $n - 4 : $n, $split)) + count($split) + 1;

            if (0 === $year % $s) {
                ++$rounds[$i];
            }

            if (2999 === $year) {
                $i = 1;
            }
        }

        $rounds[1] += $rounds[0];

        return (string) json_encode($rounds);
    }
}
