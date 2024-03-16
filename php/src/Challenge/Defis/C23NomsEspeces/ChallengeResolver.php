<?php

namespace App\Challenge\Defis\C23NomsEspeces;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_NomsEspeces/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $data = $this->getInput()->getArrayData();
        $s = [];

        foreach ($data as $nom) {
            $sanitize = strtolower(str_replace(' ', '', $nom));
            $c = count_chars($sanitize, 1);
            $m = count($c) / strlen($sanitize);
            $s[$nom] = $m;
        }
        asort($s);

        return implode(', ', array_slice(array_keys($s), 0, 10));
    }
}
