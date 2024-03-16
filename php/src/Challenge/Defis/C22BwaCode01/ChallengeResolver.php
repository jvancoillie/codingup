<?php

namespace App\Challenge\Defis\C22BwaCode01;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C22_BwaCode01/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $data = implode('', $this->getInput()->getArrayData());

        $split = preg_split("/BWA\wA/", $data);

        return array_reduce($split, function ($l, $c) {
            return $l.$c;
        });
    }
}
