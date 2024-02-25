<?php

namespace App\Challenge\Cup23\Videotex;

use App\Challenge\AbstractChallengeResolver;
use App\Utils\Image;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_Videotex/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main(): string
    {
        $data = explode(', ', $this->getInput()->getData());
        dump($data);
        dump(count($data));
        $n = count($data);
        $d = [];
        foreach ($data as $o) {
            $d[] = $this->decode(dechex((int) $o));
        }

        for ($i = 1; $i < $n; ++$i) {
            if (is_int($n / $i)) {
                $width = ($n / $i) * 2;
                $height = $i * 3;

                $image = Image::create($width, $height);
                $black = $image->createColor(0, 0, 0);
                $white = $image->createColor(255, 255, 255);
                $y = $x = 0;

                foreach ($d as $c => $item) {
                    $p = array_chunk(str_split($item), 2);
                    foreach ($p as $u => $r) {
                        $image->setPixel($x, $y + $u, 0 == $r[0] ? $white : $black);
                        $image->setPixel($x + 1, $y + $u, 0 == $r[1] ? $white : $black);
                    }
                    if (($x + 2) >= $width) {
                        $y += 3;
                    }

                    $x = ($x + 2) % $width;
                }
                $image->saveAs(__DIR__.'/output/result-'.$width.'.png');
            }
        }

        return 'image generated';
    }

    public function decode(string $n): string
    {
        $trans = [
            '20' => '000000',
            '21' => '100000',
            '22' => '010000',
            '23' => '110000',
            '24' => '001000',
            '25' => '101010',
            '26' => '011000',
            '27' => '111000',
            '28' => '000100',
            '29' => '100100',
            '2a' => '010100',
            '2b' => '110100',
            '2c' => '001100',
            '2d' => '101100',
            '2e' => '011100',
            '2f' => '111100',
            '30' => '000010',
            '31' => '100010',
            '32' => '010010',
            '33' => '110010',
            '34' => '001010',
            '35' => '101010',
            '36' => '011010',
            '37' => '111010',
            '38' => '000110',
            '39' => '100110',
            '3a' => '010110',
            '3b' => '110110',
            '3c' => '001110',
            '3d' => '101110',
            '3e' => '011110',
            '3f' => '111110',
            '60' => '000001',
            '61' => '100001',
            '62' => '010001',
            '63' => '110001',
            '64' => '001001',
            '65' => '101001',
            '66' => '011001',
            '67' => '111001',
            '68' => '000101',
            '69' => '100101',
            '6a' => '010101',
            '6b' => '110101',
            '6c' => '001101',
            '6d' => '101101',
            '6e' => '011101',
            '6f' => '111101',
            '70' => '000011',
            '71' => '100011',
            '72' => '010011',
            '73' => '110011',
            '74' => '001011',
            '75' => '101011',
            '76' => '011011',
            '77' => '111011',
            '78' => '000111',
            '79' => '100111',
            '7a' => '010111',
            '7b' => '110111',
            '7c' => '001111',
            '7d' => '101111',
            '7e' => '011111',
            '7f' => '111111',
        ];

        return $trans[$n];
    }
}
