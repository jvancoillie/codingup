<?php

namespace App\Challenge\Defis\Herculito12Cerbere;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/Herculito12Cerbere/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $hypotenuse = $this->isTestMode() ? 50 : 13979;

        for ($i = 1; $i < $hypotenuse; ++$i) {
            $a = $i;
            $b = sqrt(pow($hypotenuse, 2) - pow($a, 2));
            $d = explode('.', (string) $b); // TODO find better way to check if it is a whole number

            if (1 === count($d)) {
                return json_encode([$a, $b]);
            }
        }

        return 'not found :(';
    }
}
