<?php

namespace App\Challenge\Cup23\C23_AsciiDW;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_AsciiDW/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        [$l1,$l2] = explode("\n\n", $this->getInput()->getData());

        $distances1 = $this->parse($l1);
        $distances2 = $this->parse($l2);
        sort($distances1);
        sort($distances2);

        $diff = array_diff($distances1, $distances2);

        return implode(', ', $diff);
    }

    public function parse(string $logo): array
    {
        $logo = array_map(fn ($e) => str_split($e), explode("\n", $logo));
        $pos = [];
        $distances = [];
        for ($y = 0; $y < count($logo); ++$y) {
            for ($x = 0; $x < count($logo[$y]); ++$x) {
                if ('1' === $logo[$y][$x]) {
                    $pos[] = [$y, $x];
                }
            }
        }
        foreach ($pos as $a => [$yA, $xA]) {
            foreach ($pos as $b => [$yB, $xB]) {
                $dist = pow($yB - $yA, 2) + pow($xB - $xA, 2);
                $distances[$dist] = $dist;
            }
        }

        return $distances;
    }
}
