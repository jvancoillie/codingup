<?php

namespace App\Challenge\Defis\C22Obfuscate01;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C22_Obfuscate01/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $sentence = join($this->getInput()->getArrayData());
        $changed = true;
        while ($changed) {
            $changed = false;
            $data = str_split($sentence);
            for ($i = 0; $i < count($data) - 1; ++$i) {
                $f = $data[$i];
                $s = $data[$i + 1];
                if (ctype_upper($s) && ctype_lower($f) && strtolower($f) === strtolower($s)) {
                    $changed = true;
                    unset($data[$i]);
                    unset($data[$i + 1]);
                    $sentence = join($data);
                    break;
                }
            }
        }

        return $sentence;
    }
}
