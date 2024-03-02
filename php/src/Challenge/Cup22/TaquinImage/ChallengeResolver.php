<?php

namespace App\Challenge\Cup22\TaquinImage;

use App\Challenge\AbstractChallengeResolver;
use App\Utils\Image;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C22_TaquinImage/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $image = Image::createFromPNG(__DIR__.'/input/m-taquin-m.png');

        $data = $this->getInput()->getArrayData();
        $array = [];
        $start = 0;

        foreach (range(0, 24) as $i) {
            $array[] = range($start, $start + 24);
            $start += 25;
        }

        foreach ($data as $entry) {
            $move = explode(', ', $entry);
            $array = $this->move($move, $array);
        }

        $this->createImage($image, $array);

        return 'image generated in output folder.';
    }

    public function createImage(Image $image, array $array): void
    {
        $newImage = Image::create($image->getWidth(), $image->getHeight());

        foreach ($array as $x => $row) {
            foreach ($row as $y => $col) {
                $dst_y = $x * 20;
                $dst_x = $y * 20;

                [$src_x, $src_y] = $this->getCoordForIndex($col, $image->getWidth() / 20);
                $newImage->merge($image, $src_x, $src_y, $dst_x, $dst_y);
            }
        }

        $newImage->saveAs(__DIR__.'/output/result.png');
    }

    public function move($move, array $array): array
    {
        $direction = $move[0];
        $bandlette = $move[1];
        $deplacement = $move[2];

        if ('v' === $direction) {
            $array = array_map(null, ...$array);
        }

        $change = $array[$bandlette];

        $gauche = array_slice($change, 0, -$deplacement);
        $droite = array_slice($change, -$deplacement);

        $array[$bandlette] = array_merge($droite, $gauche);

        if ('v' === $direction) {
            $array = array_map(null, ...$array);
        }

        return $array;
    }

    private function getCoordForIndex($index, $width): array
    {
        $x = $index % $width;
        $y = (int) ($index / $width);

        return [$x * 20, $y * 20];
    }
}
