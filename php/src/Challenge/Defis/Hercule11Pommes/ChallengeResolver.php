<?php

namespace App\Challenge\Defis\Hercule11Pommes;

use App\Challenge\AbstractChallengeResolver;
use App\Utils\Distance;
use App\Utils\Number;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/Hercule11Pommes/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public const DIRECTIONS = [[1, 0], [0, 1], [-1, 0], [0, -1]];

    public function main(): int
    {
        $data = (int) $this->getInput()->getData();

        $pos = [0, 0];
        $forward = 1;
        /** @var int<0,3> $direction */
        $direction = 0;

        foreach (Number::collatzGenerator($data) as $number) {
            if (0 === $number % 2) {
                $pos[0] += self::DIRECTIONS[$direction][0] * $forward * 1000;
                $pos[1] += self::DIRECTIONS[$direction][1] * $forward * 1000;
                ++$forward;
            } else {
                if (0 === $number % 3) {
                    ++$direction;
                    $direction %= count(self::DIRECTIONS);
                } else {
                    --$direction;
                    if ($direction < 0) {
                        $direction = count(self::DIRECTIONS) - 1;
                    }
                }
            }
        }

        return (int) round(Distance::euclidean([0, 0], $pos));
    }
}
