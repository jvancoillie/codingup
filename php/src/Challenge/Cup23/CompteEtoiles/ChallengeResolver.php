<?php

namespace App\Challenge\Cup23\CompteEtoiles;

use App\Challenge\AbstractChallengeResolver;
use App\Utils\Image;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_CompteEtoiles/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main()
    {
        if ($this->isTestMode()) {
            $source = __DIR__.'/input/ciel_10.png';
        } else {
            $source = __DIR__.'/input/ciel.png';
        }

        $image = Image::createFromPNG($source);
        $hot = 0;

        for ($x = 0; $x < $image->getWidth(); ++$x) {
            for ($y = 0; $y < $image->getHeight(); ++$y) {
                $rgb = $image->getRGBA($x, $y);
                if ($rgb['blue'] > $rgb['red'] && $rgb['blue'] > $rgb['green']) {
                    ++$hot;
                }
            }
        }

        return $hot;
    }
}
