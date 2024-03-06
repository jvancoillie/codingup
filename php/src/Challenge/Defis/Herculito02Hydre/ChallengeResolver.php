<?php

namespace App\Challenge\Defis\Herculito02Hydre;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/Herculito02Hydre/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): int
    {
        $heads = (int) $this->getInput()->getData();
        $stroke = 0;
        while (0 !== $heads) {
            ++$stroke;
            $heads /= 2;

            if (1 === $heads) {
                ++$stroke;
                $heads = 0;
            }

            if (0 !== $heads % 2) {
                $heads *= 3;
                ++$heads;
            }
        }

        return $stroke;
    }
}
