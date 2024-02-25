<?php

namespace App\Challenge\Cup23\TardisFirmware20;

use App\Challenge\AbstractChallengeResolver;
use App\Utils\Image;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_TardisFirmware20/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public const PUSH = 'PUSH';
    public const ADD = 'ADD';
    public const MUL = 'MUL';
    public const WRITE = 'WRITE';
    public const NOP = 'NOP';
    public const POP = 'POP';
    public const SWAP = 'SWAP';
    public const CMPSWAP = 'CMPSWAP';
    public const MOVE = 'MOVE';

    public function main(): false|string
    {
        if ($this->isTestMode()) {
            $source = __DIR__.'/input/tardis_machine_03.png';
        } else {
            $source = __DIR__.'/input/tardis_machine_04.png';
        }

        $image = Image::createFromPNG($source);
        $pointer = 0;
        $piles = [0 => [], 1 => []];
        $write = [];
        for ($y = 0; $y < $image->getHeight(); $y += 20) {
            for ($x = 0; $x < $image->getWidth(); $x += 20) {
                $rgb = $image->getRGBA($x, $y);
                $action = $this->getAction($rgb);

                switch ($action) {
                    case self::PUSH:
                        $piles[$pointer][] = $rgb['red'];
                        break;
                    case self::ADD:
                        $a = array_pop($piles[$pointer]);
                        $b = array_pop($piles[$pointer]);
                        $piles[$pointer][] = ($a + $b) % 256;
                        break;
                    case self::MUL:
                        $a = array_pop($piles[$pointer]);
                        $b = array_pop($piles[$pointer]);
                        $piles[$pointer][] = ($a * $b) % 256;
                        break;
                    case self::WRITE:
                        $write[] = end($piles[$pointer]);
                        break;
                    case self::NOP:
                        break;
                    case self::POP:
                        array_pop($piles[$pointer]);
                        break;
                    case self::SWAP:
                        $pointer = (int) (!$pointer);
                        break;
                    case self::CMPSWAP:
                        if (0 === (end($piles[$pointer]) % 2)) {
                            $pointer = (int) (!$pointer);
                        }
                        break;
                    case self::MOVE:
                        $piles[$pointer][] = array_pop($piles[(int) !$pointer]);
                        break;
                }
            }
        }

        $ans = $this->convert($write);

        return json_encode($ans);
    }

    private function getAction(array $rgb): string
    {
        if ($rgb['red'] > 0 && 0 === $rgb['green'] && 0 === $rgb['blue']) {
            return self::PUSH;
        }

        if (0 === $rgb['red'] && 0 === $rgb['green'] && 230 === $rgb['blue']) {
            return self::ADD;
        }

        if (0 === $rgb['red'] && 0 === $rgb['green'] && 240 === $rgb['blue']) {
            return self::MUL;
        }

        if (200 === $rgb['red'] && 200 === $rgb['green'] && 200 === $rgb['blue']) {
            return self::WRITE;
        }

        if (0 === $rgb['red'] && 0 === $rgb['green'] && 0 === $rgb['blue']) {
            return self::NOP;
        }

        if (0 === $rgb['red'] && 0 === $rgb['green'] && 220 === $rgb['blue']) {
            return self::POP;
        }

        if (0 === $rgb['red'] && 255 === $rgb['green'] && 0 === $rgb['blue']) {
            return self::SWAP;
        }

        if (255 === $rgb['red'] && 255 === $rgb['green'] && 0 === $rgb['blue']) {
            return self::CMPSWAP;
        }

        if (0 === $rgb['red'] && 0 === $rgb['green'] && 210 === $rgb['blue']) {
            return self::MOVE;
        }

        throw new \Exception('code should not be reached');
    }

    private function convert(array $write): array
    {
        if (count($write) < 10) {
            return ['coord' => 0, 'day' => 0, 'month' => 0, 'year' => 0];
        }
        $lat = round(($write[1] + $write[2] / 60) * (0 === $write[0] ? -1 : 1), 2);
        $long = round(($write[4] + $write[5] / 60) * (0 === $write[3] ? -1 : 1), 2);
        $day = $write[6];
        $month = $write[7];
        $year = (256 * $write[8] + $write[9]) - 10000;

        return ['coord' => sprintf('%.02f,%.02f', $lat, $long), 'day' => $day, 'month' => $month, 'year' => $year];
    }
}
