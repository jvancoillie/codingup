<?php

namespace App\Challenge\Defis\CodeCabine;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/CodeCabine/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): false|string
    {
        $number = 64224;

        $nextNumbers = [];
        $expected = [1, 2, 4, 6, 7];

        while (count($nextNumbers) < 3) {
            ++$number;
            $square = pow($number, 2);
            $r = array_map('intval', array_unique(str_split((string) $square)));
            sort($r);

            if ($r === $expected) {
                $nextNumbers[] = $number;
            }
        }

        return json_encode($nextNumbers);
    }
}
