<?php

namespace App\Challenge\Defis\Herculito06Oiseaux;

use App\Challenge\AbstractChallengeResolver;
use App\Utils\Image;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/Herculito06Oiseaux/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): int
    {
        $ans = 0;
        $imageName = $this->isTestMode() ? 'exlake.png' : 'lake.png';

        $image = Image::createFromPNG(__DIR__."/input/{$imageName}");
        [$width, $height] = $image->getSize();

        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                $ans += $image->getGrayLevel($x, $y);
            }
        }

        return $ans;
    }
}
