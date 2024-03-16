<?php

namespace App\Challenge\Defis\DrageesBertie;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/DrageesBertie/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): int
    {
        $candies = [
            'Aubergine', 'Bouillabaisse', 'Épinards', 'Chaussettes',
            'Glace', 'Foie', 'Morve de Troll', 'Œuf Pourri',
            'Herbe', 'Poubelle', 'Saucisse', 'Vomi',
        ];

        $shuffles = 0;
        $pick = 4;

        while (true) {
            $prevPick = ($pick - 1 < 0) ? 11 : ($pick - 1);
            $tmp = $candies[$pick];
            $candies[$pick] = $candies[$prevPick];
            $candies[$prevPick] = $tmp;
            $pick = ($pick + 5) % 12;
            ++$shuffles;

            if (0 === count(
                array_diff(
                    ['Aubergine', 'Épinards', 'Glace', 'Herbe'],
                    array_slice($candies, 0, 4)
                )
            )) {
                break;
            }
        }

        return $shuffles;
    }
}
