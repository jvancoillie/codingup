<?php

namespace App\Challenge\Defis\C22Dungeons;

use App\Challenge\AbstractChallengeResolver;
use App\Utils\Image;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C22_Dungeons/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $image = Image::createFromPNG(__DIR__.'/input/img.png');
        [$width, $height] = $image->getSize();
        for ($i = 50; $i < 100; ++$i) {
            $newImage = Image::create($image->getWidth(), $image->getHeight());
            for ($y = 0; $y < $height; ++$y) {
                for ($x = 0; $x < $width; ++$x) {
                    $yiq = $image->getYIQ($x, $y);
                    $grayScale = round($yiq['y']);
                    $color = $image->createColor(255, 255, 255);
                    if ($grayScale < 128) {
                        $gmp = gmp_strval(gmp_mod(gmp_or(gmp_add(gmp_pow($x, 3), gmp_pow($y, 7)), $i), 256));
                        $color = $image->createColor($gmp, $gmp, $gmp);
                    }

                    $newImage->setPixel($x, $y, $color);
                }
            }

            $newImage->saveAs(__DIR__."/output/result-{$i}.png");
            dump("result-{$i}.png");
        }

        return 'check output folder';
    }
}
