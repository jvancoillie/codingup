<?php

namespace App\Challenge\Cup22\VampireSurvivors;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C22_VampireSurvivors/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main()
    {
        $data = ['c' => 0, 's' => 0, 'z' => 0, 'f' => 0];
        $antonio = ['c' => 2, 's' => 1, 'z' => 1, 'f' => 1];

        for ($i = 1; $i <= 3000; ++$i) {
            // pop de chauve souris
            if (0 === $i % 2) {
                $data['c'] += 10;
            }
            // pop de Skellingtons
            if (0 === $i % 5) {
                $data['s'] += 5;
            }
            // pop de zombies
            if (0 === $i % 6) {
                $data['z'] += 4;
            }
            // pop de fontome
            if (0 === $i % 10) {
                $data['f'] += 3;
            }

            // antonio de chauve souris
            if (0 === $i % 6) {
                $data['c'] -= $antonio['c'];
            }
            // antonio de Skellingtons
            if (0 === $i % 20) {
                $data['s'] -= $antonio['s'];
            }
            // antonio de zombies
            if (0 === $i % 30) {
                $data['z'] -= $antonio['z'];
            }
            // antonio de fantome
            if (0 === $i % 40) {
                $data['f'] -= $antonio['f'];
            }

            // increase antonio
            if (0 === $i % 240) {
                $antonio['c'] += 2;
                ++$antonio['s'];
                ++$antonio['z'];
                ++$antonio['f'];
            }
        }

        return implode(', ', $data);
    }
}
