<?php

namespace App\Challenge\Defis\Herculito03Biche;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/Herculito03Biche/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main()
    {
        $data = json_decode($this->getInput()->getData());
        $coordinates = array_chunk($data, 2);
        $position = array_shift($coordinates);

        $distance = 0;
        while (!empty($coordinates)) {
            $to = array_shift($coordinates);
            $distance += $this->manhattan($position, $to);
            $position = $to;
        }

        return $distance;
    }

    public function manhattan(array $a, array $b): int
    {
        return (int) (abs($a[0] - $b[0]) + abs($a[1] - $b[1]));
    }
}
