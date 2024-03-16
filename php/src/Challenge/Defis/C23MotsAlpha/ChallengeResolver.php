<?php

namespace App\Challenge\Defis\C23MotsAlpha;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_MotsAlpha/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): int
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();

        foreach ($data as $nom) {
            $clean = $this->cleanUp($nom);

            if (strlen($clean) < 3) {
                continue;
            }

            $prev = ord($clean[0]);
            $correct = true;

            for ($i = 1; $i < strlen($clean); ++$i) {
                $current = ord($clean[$i]);

                if ($prev > $current) {
                    $correct = false;
                    break;
                }

                $prev = $current;
            }

            if ($correct) {
                ++$ans;
            }
        }

        return $ans;
    }

    public function cleanUp($string): string
    {
        $search = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ'];
        $replace = ['A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y'];

        return strtolower(str_replace($search, $replace, $string));
    }
}
