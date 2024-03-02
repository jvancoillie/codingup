<?php

namespace App\Challenge\Cup22\BwaCode01;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C22_BwaCode01/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main()
    {
        $data = implode('', $this->getInput()->getArrayData());

        $split = preg_split("/BWA\wA/", $data);

        return array_reduce($split, function ($l, $c) {
            return $l.$c;
        });
    }
}
