<?php

namespace App\Challenge\Defis\ParIciLaMonnaie;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/ParIciLaMonnaie/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main()
    {
        $coins = [1, 2, 3, 7, 10, 20, 25];
        $amount = (int) $this->getInput()->getData();

        return $this->countWays($amount, $coins);
    }

    public function countWays($amount, $coins)
    {
        $dp = array_fill(0, $amount + 1, 0);
        $dp[0] = 1;

        foreach ($coins as $coin) {
            for ($i = $coin; $i <= $amount; ++$i) {
                $dp[$i] += $dp[$i - $coin];
            }
        }

        return $dp[$amount];
    }
}
