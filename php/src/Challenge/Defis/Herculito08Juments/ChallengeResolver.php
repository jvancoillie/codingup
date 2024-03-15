<?php

namespace App\Challenge\Defis\Herculito08Juments;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/Herculito08Juments/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $target = 4 * 131;
        $maxBags = 20;
        $bags = [7, 11, 13];

        if ($this->isTestMode()) {
            $target = 4 * 23;
            $maxBags = 7;
            $bags = [5, 7, 9];
        }

        $minTotalBags = PHP_INT_MAX;
        $optimalBags = [0, 0, 0];

        // Iterate through all numbers of bags of each weight
        for ($bagsA = 0; $bagsA <= $maxBags; ++$bagsA) {
            for ($bagsB = 0; $bagsB <= $maxBags; ++$bagsB) {
                for ($bagsC = 0; $bagsC <= $maxBags; ++$bagsC) {
                    $totalWeight = $bagsA * $bags[0] + $bagsB * $bags[1] + $bagsC * $bags[2];

                    if ($totalWeight == $target) {
                        $totalBags = $bagsA + $bagsB + $bagsC;
                        if ($totalBags < $minTotalBags) {
                            $minTotalBags = $totalBags;
                            $optimalBags = [$bagsA, $bagsB, $bagsC];
                        } elseif ($totalBags == $minTotalBags && $bagsC < $optimalBags[2]) {
                            $optimalBags = [$bagsA, $bagsB, $bagsC];
                        }
                    }
                }
            }
        }

        return json_encode($optimalBags);
    }
}
