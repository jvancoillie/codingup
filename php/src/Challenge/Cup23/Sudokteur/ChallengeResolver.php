<?php

namespace App\Challenge\Cup23\Sudokteur;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_Sudokteur/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $number = array_fill(0, 10, 0);
        $ans = '';
        $t = 250;

        $data = array_map(fn ($e) => str_split($e), $this->getInput()->getArrayData());

        foreach ($data as $row) {
            foreach ($row as $n) {
                if ('.' === $n) {
                    continue;
                }
                ++$number[(int) $n];
            }
        }

        foreach ($number as $n => $c) {
            if ($c < $t) {
                $ans .= str_repeat((string) $n, $t - $c);
            }
        }

        return $ans;
    }
}
