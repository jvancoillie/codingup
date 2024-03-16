<?php

namespace App\Challenge\Defis\C23QRShaked;

use App\Challenge\AbstractChallengeResolver;
use App\Utils\Generator;
use App\Utils\Grid;
use App\Utils\Image;
use Zxing\QrReader;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_QRShaked/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $image = Image::createFromPNG(__DIR__.'/input/qrshaked.png');
        $shakes = 20;
        $moves = 6;

        if ($this->isTestMode()) {
            $image = Image::createFromPNG(__DIR__.'/input/qrcode_example_04.png');
            $shakes = 5;
            $moves = 3;
        }

        $initialGrid = $this->createArray($image);

        $ranges = array_reverse(range(0, 20));
        $turn = 0;
        $generator = Generator::combinationsFixedSize($ranges, $moves);
        foreach ($generator as $indexes) {
            ++$turn;
            $grid = $initialGrid;

            for ($i = 0; $i < $shakes; ++$i) {
                $grid = $this->reversedShake($indexes, $grid);
                if ($i < $shakes - 1) {
                    $indexes = $this->updateIndexes($indexes, true);
                }
            }

            $image = $this->createImageFromGrid($grid);

            $reader = new QrReader($image->getResource(), QrReader::SOURCE_TYPE_RESOURCE);

            if (false !== $text = $reader->text()) {
                return $text;
            }
        }

        return 'Not found :(';
    }

    private function createArray(Image $image): array
    {
        $cellSize = 3;

        $grid = [];
        [$width, $height] = $image->getSize();
        for ($y = 0; $y < $height; $y += $cellSize) {
            for ($x = 0; $x < $width; $x += $cellSize) {
                $grid[$y][$x] = $image->getColorat($x, $y);
            }
        }

        $grid = array_map(fn (array $r) => array_values($r), $grid);

        return array_values($grid);
    }

    private function createImageFromGrid(array $grid): Image
    {
        $image = Image::create(count($grid[0]), count($grid));
        $black = $image->createColor(0, 0, 0);
        $white = $image->createColor(255, 255, 255);

        foreach ($grid as $y => $row) {
            foreach ($row as $x => $color) {
                $image->setPixel($x, $y, 0 === $color ? $black : $white);
            }
        }

        return $image;
    }

    private function reversedShake(array $indexes, array $grid): array
    {
        foreach ($indexes as $i) {
            $r = $grid[$i];
            $t = array_shift($r);
            $r[] = $t;
            $grid[$i] = $r;
        }

        $grid = Grid::rotate($grid);

        foreach ($indexes as $i) {
            $r = $grid[$i];
            array_unshift($r, array_pop($r));
            $grid[$i] = $r;
        }

        return Grid::rotate($grid, false);
    }

    private function updateIndexes(mixed $indexes, $reversed = false): array
    {
        if ($reversed) {
            return array_map(fn (int $int) => ($int - 1) < 0 ? 20 : $int - 1, $indexes);
        }

        return array_map(fn (int $int) => ($int + 1) % 21, $indexes);
    }
}
