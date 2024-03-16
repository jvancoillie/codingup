<?php

namespace App\Challenge\Defis\TrajetVifOr;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/TrajetVifOr/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): false|string
    {
        for ($n = 2; $n <= 200; ++$n) {
            $turn = 1;
            $initialPosition = [0, 0, 1];
            $pos = [0, 0, 1];
            do {
                $pos = [$pos[1], $pos[2], array_sum($pos) % $n];
                ++$turn;
            } while ($pos !== $initialPosition);
            $values[$n] = $turn;
        }
        asort($values);

        return json_encode(array_slice(array_keys($values), -10));
    }
}
