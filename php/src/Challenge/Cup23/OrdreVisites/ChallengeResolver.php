<?php

namespace App\Challenge\Cup23\OrdreVisites;

use App\Challenge\AbstractChallengeResolver;
use App\Utils\Algorithm\TopologicalSorting;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_OrdreVisites/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $data = $this->getInput()->getArrayData();
        $graph = [];

        foreach ($data as $constraint) {
            preg_match("/^Visiter (\d+) avant (\d+)$/", $constraint, $matches);
            [,$prev, $next] = $matches;

            if (!isset($graph[$prev])) {
                $graph[$prev] = [$next];
            } else {
                $graph[$prev][] = $next;
            }
        }

        $topologicalSorting = new TopologicalSorting($graph);
        $result = $topologicalSorting->sort();

        return (string) json_encode(array_map('intval', $result));
    }
}
