<?php

namespace App\Challenge\Defis\DeuxOuTroisFacteurs;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/DeuxOuTroisFacteurs/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main()
    {
        $targetIndex = (int) $this->getInput()->getData();
        $n = 0;
        $products = [];

        while (true) {
            $f = $this->primeFactor(++$n);

            if (
                ((2 === count($f) && 2 === count(array_unique($f))) || (3 === count($f) && 3 === count(array_unique($f))))
                && !in_array($n, $products)) {
                $products[] = $n;
            }

            if (count($products) >= $targetIndex) {
                return $products[$targetIndex - 1];
            }
        }
    }

    public function primeFactor($n): array
    {
        $p = [];
        for ($c = 2; $n > 1; ++$c) {
            for (; 0 === $n % $c; $n /= $c) {
                $p[] = $c;
            }
        }

        return $p;
    }
}
