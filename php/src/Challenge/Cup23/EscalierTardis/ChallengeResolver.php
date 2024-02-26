<?php

namespace App\Challenge\Cup23\EscalierTardis;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_EscalierTardis/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main()
    {
        $stairs = 62;
        $steps = 30;

        if ($this->isTestMode()) {
            $stairs = 6;
            $steps = 3;
        }

        return $this->countCombinations($stairs, $steps);
    }

    public function countCombinations($targetSum, $numDigits)
    {
        $dp = array_fill(0, $targetSum + 1, array_fill(0, $numDigits + 1, 0));
        $dp[0][0] = 1;

        for ($i = 1; $i <= $targetSum; ++$i) {
            for ($j = 1; $j <= $numDigits; ++$j) {
                for ($k = 1; $k <= 3; ++$k) {
                    if ($i - $k >= 0) {
                        $dp[$i][$j] += $dp[$i - $k][$j - 1];
                    }
                }
            }
        }

        return $dp[$targetSum][$numDigits];
    }
}
