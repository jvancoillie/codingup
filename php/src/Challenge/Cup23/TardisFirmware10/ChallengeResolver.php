<?php

namespace App\Challenge\Cup23\TardisFirmware10;

use App\Challenge\AbstractChallengeResolver;
use App\Utils\Image;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_TardisFirmware10/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): int
    {
        if ($this->isTestMode()) {
            $source = __DIR__.'/input/tardis_machine_01.png';
        } else {
            $source = __DIR__.'/input/tardis_machine_02.png';
        }

        $image = Image::createFromPNG($source);
        $pile = [];
        $write = [];
        for ($y = 0; $y < $image->getHeight(); $y += 20) {
            for ($x = 0; $x < $image->getWidth(); $x += 20) {
                $rgb = $image->getRGBA($x, $y);

                if (0 === $rgb['blue'] && 0 === $rgb['red'] && 0 === $rgb['green']) {
                    continue;
                }

                if (200 === $rgb['blue'] && 200 === $rgb['red'] && 200 === $rgb['green']) {
                    $a = array_pop($pile);
                    $pile[] = $a;
                    $write[] = $a;
                    continue;
                }

                if ($rgb['red'] > 0) {
                    // action push
                    $pile[] = $rgb['red'];
                    continue;
                }

                if (230 == $rgb['blue']) {
                    // action ADD
                    $a = array_pop($pile);
                    $b = array_pop($pile);
                    $pile[] = ($a + $b) % 256;
                    continue;
                }

                if (240 === $rgb['blue']) {
                    // action MUL
                    $a = array_pop($pile);
                    $b = array_pop($pile);
                    $pile[] = ($a * $b) % 256;
                }
            }
        }

        $a = $write[0];
        $b = $write[1];

        return (int) (256 * $a + $b - 10000);
    }
}
