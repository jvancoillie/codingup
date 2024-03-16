<?php

namespace App\Challenge\Defis\C23BrouillagePhone;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_BrouillagePhone/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): false|string
    {
        $trans = [
            'A' => 2,
            'B' => 2,
            'C' => 2,
            'D' => 3,
            'E' => 3,
            'F' => 3,
            'G' => 4,
            'H' => 4,
            'I' => 4,
            'J' => 5,
            'K' => 5,
            'L' => 5,
            'M' => 6,
            'N' => 6,
            'O' => 6,
            'P' => 7,
            'Q' => 7,
            'R' => 7,
            'S' => 7,
            'T' => 8,
            'U' => 8,
            'V' => 8,
            'W' => 9,
            'X' => 9,
            'Y' => 9,
            'Z' => 9,
        ];

        $data = $this->getInput()->getArrayData();
        $r = [];
        foreach ($data as $mot) {
            $n = '';

            for ($i = 0; $i < strlen($mot); ++$i) {
                $n .= $trans[$mot[$i]];
            }

            if (isset($r[$n])) {
                ++$r[$n];
            } else {
                $r[$n] = 1;
            }
        }
        $max = max($r);
        $f = array_filter($r, fn ($e) => $e === $max);
        ksort($f);

        return json_encode(array_keys($f));
    }
}
