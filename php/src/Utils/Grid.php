<?php

namespace App\Utils;

class Grid
{
    public static array $crossDirections = [[0, 1], [0, -1], [-1, 0], [1, 0]];
    public static array $fullDirections = [[-1, -1], [-1, 0], [-1, 1], [0, -1], [0, 0], [0, 1], [1, -1], [1, 0], [1, 1]];

    /**
     * @psalm-param 7|50|1000 $x
     * @psalm-param 3|6|1000 $y
     * @psalm-param ' '|'.'|0 $fill
     *
     * @return array[]
     *
     * @psalm-return array<0|positive-int, array<0|positive-int, mixed>>
     */
    public static function create(int $x, int $y, string|int $fill): array
    {
        $grid = [];

        for ($gy = 0; $gy < $y; ++$gy) {
            for ($gx = 0; $gx < $x; ++$gx) {
                $grid[$gy][$gx] = $fill;
            }
        }

        return $grid;
    }

    public static function dump(array $array, $separator = '|', $withLineNumber = true): void
    {
        $maxLineNumber = strlen((string) count($array));

        foreach ($array as $n => $lines) {
            if ($withLineNumber) {
                echo str_pad($n, $maxLineNumber, '0', STR_PAD_LEFT).' '.$separator;
            }
            echo implode($separator, $lines)."\n";
        }
        echo "\n";
    }

    /**
     * @psalm-param '#' $needle
     *
     * @psalm-return 0|positive-int
     */
    public static function count($grid, string $needle): int
    {
        $count = 0;

        foreach ($grid as $lines) {
            foreach ($lines as $cell) {
                if ($cell === $needle) {
                    ++$count;
                }
            }
        }

        return $count;
    }

    public static function rotate(array $grid, $clockwise = true, int $degres = 90): array
    {
        if (0 !== $degres % 90) {
            throw new \Exception('degrees must be a modulo of 90');
        }

        $rotations = ($degres / 90) % 4;

        for ($r = 0; $r < $rotations; ++$r) {
            array_unshift($grid, null);
            $grid = call_user_func_array('array_map', $grid);
            $grid = $clockwise ? array_map('array_reverse', $grid) : array_reverse($grid);
        }

        return $grid;
    }

    public static function flip(array $grid, $vertical = true): array
    {
        if($vertical){
            return array_map('array_reverse', $grid);
        }

        return array_reverse($grid);
    }
}
