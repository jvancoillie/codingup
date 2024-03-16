<?php

namespace App\Challenge\Defis\C22GenList;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C22_GenList/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $data = explode(', ', $this->getInput()->getData());
        $data = array_map('intval', $data);

        sort($data);

        $first = array_shift($data);
        $previous = $first;

        while ($data) {
            $next = array_shift($data);
            if ($previous + 1 === $next) {
                $previous = $next;
                continue;
            }

            if ($first === $previous) {
                $res[] = "$first";
            } else {
                $res[] = "$first-$previous";
            }

            $first = $next;
            $previous = $first;
        }

        if ($first === $previous) {
            $res[] = "$first";
        } else {
            $res[] = "$first-$previous";
        }

        return implode(',', $res);
    }
}
