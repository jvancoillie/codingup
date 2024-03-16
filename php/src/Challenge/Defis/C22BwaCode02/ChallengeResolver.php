<?php

namespace App\Challenge\Defis\C22BwaCode02;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C22_BwaCode02/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public const ENCODE = [
        0 => 'BWA',
        1 => 'BWAHA',
        2 => 'BWAA',
    ];

    public function main(): string
    {
        $alpha = [];
        $alpha[0] = ' ';

        foreach (range('A', 'Z') as $l) {
            $alpha[] = $l;
        }

        $ans = [];
        foreach (str_split('STOP') as $l) {
            $pos = (string) array_search($l, $alpha);
            $b = base_convert($pos, 10, 3);
            foreach (str_split($b) as $i) {
                $ans[] = self::ENCODE[(int) $i];
            }
        }

        return implode(' ', $ans);
    }
}
