<?php

namespace App\Challenge\Defis\C22KonamiCode;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C22_KonamiCode/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $data = $this->getInput()->getArrayData();

        $decode = [];

        for ($i = 0; $i < 36; ++$i) {
            $d = explode(' ->', $data[$i]);
            $t = trim($d[1]);
            if ('' === $t) {
                $t = ' ';
            }
            $decode[$d[0]] = $t;
        }

        $string = '';

        for ($i = 37; $i < 60; ++$i) {
            $string .= $data[$i];
        }

        $res = '';

        foreach (mb_str_split($string, 2) as $e) {
            $res .= $decode[$e];
        }

        return $res;
    }
}
